
import serial.tools.list_ports
import hid
import time

vid = 4292  # Reemplazar con tu vendor_id
pid = 6169  # Reemplazar con tu product_id
i=0
REPORT_ID = 0x41
REPORT_LEN = 64
for device in hid.enumerate(vid, pid):
    print(f"Device :{device['serial_number']} ")
    device = hid.device()
    device.open(vid, pid)  
    device.set_nonblocking(1)  # Configurar modo no bloqueante
    time.sleep(.5)
    data = device.get_feature_report(REPORT_ID, REPORT_LEN + 1)
    
    print(f"data :{data}")
    data = [0x50, 0x00, 0x00, 0x70, 0x80, 0x00, 0x00, 0x03, 0x00]

    device.send_feature_report(data)
    device.get_feature_report(0x41,REPORT_LEN + 1)
    i += 1

# import subprocess
# import json

# def get_devices():
#     command = [
#         "powershell",
#         "-Command",
#         """
#         Get-PnpDevice -PresentOnly |
#         Where-Object {$_.InstanceId -like "*VID_10C4&PID_1819*"} |
#         ForEach-Object {
#             $dev = $_
#             $props = Get-PnpDeviceProperty -InstanceId $dev.InstanceId -KeyName 'DEVPKEY_Device_InstallDate'
#             [PSCustomObject]@{
#                 InstanceId = $dev.InstanceId
#                 InstallDate = $props.Data
#             }
#         } | ConvertTo-Json
#         """
#     ]

#     result = subprocess.run(command, capture_output=True, text=True)
#     return json.loads(result.stdout)

# devices = get_devices()

# for d in devices:
#     print(d)

# # Ordenar por fecha
# devices_sorted = sorted(devices, key=lambda x: x['InstallDate'])

# print("\nÚltimo conectado:")
# print(devices_sorted[-1])