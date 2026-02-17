import serial.tools.list_ports
import hid
import time
import mysql.connector
import sys
import os
from mysql.connector import Error
# Listar dispositivos HID conectados
# for device in hid.enumerate():
#     print(f"Device:{device['product_string']} Vendor ID: {device['vendor_id']}, Product ID: {device['product_id']}")

def connectDB():
    project_root = os.path.abspath(os.path.join(os.path.dirname(__file__), "..", ".."))
    file_path = os.path.join(project_root, "storage", "app", "db_host.txt")
    content=False
    # Verificar si el archivo existe y leerlo
    if os.path.exists(file_path):
        with open(file_path, "r") as file:
            content = file.read()

    if content:
        dbHost=content
    else:
        dbHost="localhost"
    # Conectarse a la base de datos
    try:
        conn = mysql.connector.connect(
            host=dbHost,
            user="root",
            password="",
            database="novaDB",
            connect_timeout=5  # Tiempo límite para la conexión
        )

        if not conn.is_connected():
            conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="novaDB",
            connect_timeout=5  # Tiempo límite para la conexión
            )

            print('Servicio mysql no encontrado en la ip, conectandose al localhost')
    except Exception as e:
        conn = mysql.connector.connect(
            host="localhost",
            user="root",
            password="",
            database="novaDB",
            connect_timeout=5  # Tiempo límite para la conexión
        )
        print('Servicio mysql no encontrado en la ip, conectandose al localhost')


    cursor = conn.cursor()
    return cursor,conn

def split_number(number):
    # Convertir el número a su representación binaria de 8 bits
    binary = format(number, '08b')

    # Obtener los primeros 4 bits y los últimos 4 bits
    first4_bits = binary[1:4]  # Aquí es del índice 0 al 3
    last4_bits = binary[4:]   # Aquí es del índice 4 al final

    # Convertir los primeros 4 bits y los últimos 4 bits de binario a decimal
    first4_decimal = int(first4_bits, 2)
    last4_decimal = int(last4_bits, 2)
    # Lista de votos
    votes = ['A', 'B', 'C', 'D', 'E', 'F']

    # Obtener el voto
    vote_string = votes[first4_decimal]

    return {
        'vote': vote_string,
        'number': last4_decimal+400
    }

def handleReport(data, id) :


    result = split_number(data[1])

    control = (data[0] - 32) * 16 + result['number']
    return {
        'control_id': control,
        'vote': result['vote']
    }

vid = 4292  # Reemplazar con tu vendor_id
pid = 6169  # Reemplazar con tu product_id
def connect(controls, serialHid):
    try:
        device = None
        if serialHid != '0' and serialHid != 0:
            for d in hid.enumerate(vid, pid):
                if d['serial_number'] == serialHid:
                    device = hid.device()
                    device.open_path(d['path'])  
            if device is None:
                print(f"1: No se encontró el dispositivo HID con el serial {serialHid}. Conectándose al primer dispositivo disponible.")
                device = hid.device()
                device.open(vid, pid)

        else:
        # Abrir conexión con el dispositivo
            device = hid.device()
            device.open(vid, pid)
        device.set_nonblocking(1)  # Configurar modo no bloqueante

  
        device.get_feature_report(REPORT_ID, REPORT_LEN + 1)
        
        data = [0x50, 0x00, 0x00, 0x70, 0x80, 0x00, 0x00, 0x03, 0x00]

        device.send_feature_report(data)
        device.send_feature_report([0x41, 0x01])
        return device

    except OSError as e:
        print(f"Error al conectar con el dispositivo HID: {e}")
        return False


    except OSError as e:
        print(f"Error al conectar con el dispositivo HID: {e} ")
        return False

def sendComands(device):

    commands = [
                [0x03, 0x70, 0x99, 0xe9],
                [0x00, 0x02, 0x78, 0x9a],
                [0x03, 0x55, 0x80, 0xd5],
                [0x0b, 0x5e, 0x80, 0xde, 0xb2, 0xb0, 0xb0, 0xb2, 0xb0, 0xb1, 0xb1, 0xb7],
                [0x06, 0x35, 0x96, 0x02, 0x10, 0x00, 0x10],
                [0x03, 0x03, 0x89, 0x8a],
                [0x03, 0x52, 0x86, 0xd4],
                [0x03, 0x5a, 0x80, 0xda]]
    for command in commands:
        device.write(command)
        time.sleep(.5)

def sendComandsToClose(device):

    commands = [
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x03, 0x82, 0x81]]
    for command in commands:
        device.write(command)
        time.sleep(.5)


def getArgs():
    # Verifica si hay suficientes argumentos
    if len(sys.argv) > 1:
        
        controls = sys.argv[1]
        if len(sys.argv) > 2:
            serialHid = sys.argv[2]
            return controls, serialHid
        else:
            return controls, 0
    else:
        return "400", 0


def setProcessId():
    pid_file = 'script_pid.txt'

    # Escribe el PID en un archivo
    with open(pid_file, 'w') as f:
        f.write(str(os.getpid()))

VID = 4292   # <-- cambia esto
PID = 6000   # <-- cambia esto

def find_com_port(vid, pid):
    for port in serial.tools.list_ports.comports():
        if port.vid == vid and port.pid == pid:
            return port.device
    return None

REPORT_ID = 0x46
REPORT_LEN = 64
def main(numControls, serialHid):
    try:
        print('Estableciendo Conexión con el Dispositivo y la BD...')
        cursor,conn = connectDB()
        device=connect(numControls, serialHid)
        if device:
            
            
            com_port = find_com_port(VID, PID)
            time.sleep(.5)
            sendComands(device)
            # El buffer DEBE incluir el Report ID como primer byte

            myFlag=0
            ignoreSecond = False
            awaitSecond = False
            head = 0
            i=0
            caso=0
            while True:

                

                data = device.read(64)  # Leer un bloque de datos de 64 bytes
                time.sleep(.5)
                if data and myFlag:
                    print(data[:4])
                    reportId=data[0]
                    values=[data[1],data[2],data[3]]
                    #caso 2.1 se ignora el siguiente mensaje si su report id es 1
                    if ignoreSecond:
                        ignoreSecond = False
                        if reportId == 1 or (values[0]>80 and reportId == 1) :
                            continue
                    # hay multiples casos de respuesta
                    #siempre necesitamos dos valores uno que indica el grupo: va de 32 a 80
                    #otro que indica el control y el voto
                    #CASO 1, comienza con reportId 1 y el siguiente mensaje es 2 o 3, se toma el primer valor de cada mensaje
                    if reportId == 1 and values[0]<=80  :
                        caso=1
                        awaitSecond = True
                        head = values[0]
                        continue
                    #CASO 1.1, se lee el siguiente mensaje y se calcula el resultado con eso
                    elif (reportId == 2 or reportId == 3)  and awaitSecond :

                        values = [head, values[0]]
                        awaitSecond = False
                        head=0
                    #CASO 2, vienen dos mensajes, el primer con id 2 y el otro en 1, se toman los dos primeros valores del primer mensaje
                    elif reportId == 2 and values[0]<80 :
                        caso=2
                        ignoreSecond = True
                    #CASO 3, con report id 2 viene un unico mensaje, se toma el ultimo valor y el primero en ese orden
                    elif reportId == 2 and values[2]<80:
                        caso=3
                        dataPass=[values[2],values[0]]
                        values=dataPass
                    #CASO 4, con reportId 3 viene un mensaje se toman sus dos primeros valores
                    elif reportId == 3 and values[0]<=80:
                        caso=4
                    #CASO 5, dos mensajes con reportId 3 y 1 los dos ultimos valores deben ser iguales y esos se toman
                    elif reportId == 3 and values[1]<80:
                        caso=5
                        dataPass =[values[1],values[2]]
                        awaitSecond= True
                        continue
                    #CASO 5.1, se recibe el segundo valor y se compara que sean iguales
                    elif reportId == 1 and values[0]>80 and awaitSecond:

                        awaitSecond= False
                        if dataPass[0]==values[1] and dataPass[1]==values[2]:
                            values=dataPass
                        else:
                            continue
                    else:
                        print('Desconocido')
                        continue



                    dataResult = handleReport(values, reportId)
                    # Crear una consulta SQL para insertar datos
                    print(dataResult,caso)


                    if dataResult['vote'] in ['A', 'B', 'C', 'D', 'E', 'F'] and dataResult['control_id']<=800:

                        sql = """UPDATE controls
                                SET `vote` = %s
                                WHERE `id` = %s"""
                        valores = (dataResult['vote'],dataResult['control_id'])
                        try:
                            # Ejecutar la consulta
                            cursor.execute(sql, valores)

                            # Guardar los cambios en la base de datos
                            conn.commit()
                        except Exception as e:
                            print(e)

                myFlag=1

    except KeyboardInterrupt:
        print("Interrupción recibida 1, deteniendo el script.")
        # sendComandsToClose(device)


    except Exception as e:
        print("ERROR:" )
        print(e)
        return 1

    finally:
        try:
            os.remove(pid_file)
        except:
            pass



if __name__ == "__main__":
    pid_file = 'script_pid.txt'
    controls, serialHid = getArgs()
    setProcessId()
    flag=1
    while flag:
        flag=main(controls, serialHid)
    # flag=main()
