
import hid
import time
import mysql.connector
import sys
import os

# Listar dispositivos HID conectados
# for device in hid.enumerate():
#     print(f"Device:{device['product_string']} Vendor ID: {device['vendor_id']}, Product ID: {device['product_id']}")

def connectDB():
    # Conectarse a la base de datos
    conn = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="novaDB"
    )

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
        'number': last4_decimal
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
                [0x03, 0x78, 0x8c, 0xf4],
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
        # Obtiene el primer argumento (titulo)
        controls = sys.argv[1]
        return controls
    else:
        print("1000")


def setProcessId():
    pid_file = 'script_pid.txt'

    # Escribe el PID en un archivo
    with open(pid_file, 'w') as f:
        f.write(str(os.getpid()))

def main():
    try:
        cursor,conn = connectDB()
        device=connect()
        if device:
            print('Send Commands')
            sendComands(device)
            time.sleep(.5)
            myFlag=0
            ignoreSecond = False
            awaitSecond = False
            head = 0
            while True:

                data = device.read(64)  # Leer un bloque de datos de 64 bytes

                if data and myFlag:
                    
                    reportId=data[0]
                    values=[data[1],data[2]]
                    print(reportId,' : ',values)
                    if (ignoreSecond) :
                        ignoreSecond = False
                        continue

                    # hay tres casos de respuesta
                    #1. caso con reportID=3, viene un unico mensaje con toda la informacion correcta
                    #2. caso con reportId=2, vienen dos mensajes el primero tiene reportId=2 y el otro en 1, solo el primero tiene la informacion correcta
                    #3. caso con reportId=1, vienen dos, ambos tienen la informacion parcial, se tiene en cuenta el
                    #primer elementol de cada uno

                    #solo se maneja si no es 3
                    if reportId != 3 :
                        #si es uno se guarda el primer elemento y pasa
                        if reportId == 1 :
                            awaitSecond = True
                            head = values[0]
                            continue

                        #si es dos ordena ignorar el siguiente
                        elif reportId == 2 and not awaitSecond:
                            ignoreSecond = True

                        #es el segundo mensaje del caso 3
                        elif reportId == 2 and awaitSecond :
                            dataPass = [head, values[0]]
                            values = dataPass
                            awaitSecond = False
                            head=0



                    dataResult = handleReport(values, reportId)
                    # Crear una consulta SQL para insertar datos

                    if dataResult['vote'] in ['A', 'B', 'C', 'D', 'E', 'F'] and dataResult['control_id']<1000:
                        sql = """INSERT INTO votes (control_id, vote)
                                VALUES (%s, %s)
                                ON DUPLICATE KEY UPDATE vote = VALUES(vote)"""
                        valores = (dataResult['control_id'],dataResult['vote'])

                        # Ejecutar la consulta
                        cursor.execute(sql, valores)

                        # Guardar los cambios en la base de datos
                        conn.commit()
                myFlag=1

    except KeyboardInterrupt:
        print("Interrupción recibida 1, deteniendo el script.")
        sendComandsToClose(device)


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
    setProcessId()
    flag=1
    while flag:
        flag=main()
