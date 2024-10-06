
import hid
import time
# Listar dispositivos HID conectados
# for device in hid.enumerate():
#     print(f"Device:{device['product_string']} Vendor ID: {device['vendor_id']}, Product ID: {device['product_id']}")


vid = 4292  # Reemplazar con tu vendor_id
pid = 6169  # Reemplazar con tu product_id
def connect():
    try:
        # Abrir conexión con el dispositivo
        device = hid.device()
        device.open(vid, pid)
        device.set_nonblocking(1)  # Configurar modo no bloqueante
        return device       

    except OSError as e:
        print(f"Error al conectar con el dispositivo HID: {e}")
        return False

def sendComands(device):
    
    commands = [
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x03, 0x82, 0x81]]
    for command in commands:
        device.write(command)
        time.sleep(1)

    

if __name__ == "__main__":
    print("Cerrando comunicacion con el dispositivo")
    device=connect()
    
    if device:
        sendComands(device)
        device.close()
        print("Conexión cerrada")
