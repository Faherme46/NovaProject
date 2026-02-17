import subprocess
import serial.tools.list_ports
import hid
import time
import json
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


vid = 4292  
pid = 6169  


def getArgs():
    # Verifica si hay suficientes argumentos
    if len(sys.argv) > 1:
        # Obtiene el primer argumento (titulo)
        controls = sys.argv[1]
        return controls
    else:
        print("400")


def setProcessId():
    pid_file = 'script_pid_detect.txt'

    # Escribe el PID en un archivo
    with open(pid_file, 'w') as f:
        f.write(str(os.getpid()))


def main():
    try:
        print('Estableciendo Conexión con el Dispositivo y la BD...')
        

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
    pid_file = 'script_pid_detect.txt'
    setProcessId()
    main()
    # flag=main()
