from flask import Flask,jsonify,render_template,request
import hid
import time
import subprocess
import matplotlib
matplotlib.use('Agg')
import matplotlib.pyplot as plt
import matplotlib.image as mpimg
import os
import signal


process = None
def connectHid(pid,vid):
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
app = Flask(__name__)

@app.route("/")
def hello_world():
    return "<p>Hello, World!</p>"

@app.route('/run-votes', methods=['GET'])
def run_script():
    global process
    if process is None or process.poll() is not None:
        try:
            # Inicia el script en segundo plano
            process=subprocess.Popen(['python', 'votes.py'])
            return jsonify({"status": "Success","message":"Proceso ejecutado"}), 200
        except Exception as e:
            return jsonify({"status": "Error", "message": str(e)}), 500
    return jsonify({"status": "Error", "message": "El script ya está corriendo"}), 400

@app.route('/verify-device', methods=['GET'])
def verify_device():
    vid = 4292  # Reemplazar con tu vendor_id
    pid = 6169  # Reemplazar con tu product_id
    try:
        device = hid.device()
        device.open(vid, pid)
        device.set_nonblocking(1)  # Configurar modo no bloqueante
        return jsonify({"status": "Success","message":"Dispositivo conectado"}), 200
    except Exception as e:
        return jsonify({"status": "Error", "message": "No existe el dispositivo"}), 500


def stop_script():
    global process
    if process is not None:
        try:
            # Lee el PID del archivo y envía una señal de terminación
            with open('script_pid.txt', 'r') as f:
                pid = int(f.read())
                os.kill(pid, signal.SIGTERM)  # Envía la señal de terminación
            process.wait()  # Espera a que el proceso se detenga
            os.remove("script_pid.txt")
            return jsonify({"status":"Success","message": "Script detenido"}), 200
        except Exception as e:
            return jsonify({"status": "Error", "message": str(e)}), 500
    return jsonify({"status": "Error", "message": "El script no está corriendo"}), 400

@app.route('/stop-votes', methods=['GET'])
def closeDevice():
    stop_script()
    vid = 4292  # Reemplazar con tu vendor_id
    pid = 6169  # Reemplazar con tu product_id
    device=connectHid(pid,vid)
    if device:
        sendComands(device)
        device.close()
        return jsonify({"status":"Success","message": "Dispositivo desconectado"}), 200
    else:
        jsonify({"status": "Error", "message": "Error al conectar con el dispositivo"}), 400

# @app.route('/hello/')
# @app.route('/hello/<name>/')
# @app.route('/login', methods=['POST'])
# def hello():

#     name='Fabi'
#     name2='Fabi2'
#     return  f"Usuario: {name}, Contraseña: {name2}"
    # return render_template('hello.html', person=name,person2=name2)


# def dividir_lista(json_str):
#     # Convertir el string JSON a una lista
#     json_str = json_str.strip('[]')

#     lista = json_str.split(',')

#     lista = [element.strip().strip("'\"") for element in lista]
#     punto_medio = len(lista) // 2

#     # Dividir la lista en dos mitades
#     primera_mitad = lista[:punto_medio]
#     segunda_mitad = lista[punto_medio:]

#     return primera_mitad, segunda_mitad

def create_plot(title, labels, values, output_path,nameAsamblea):


    if len(values)>6:
        width=20
        height=9
    else:
        width =15
        height=7.5
    fig, ax = plt.subplots(figsize=(width,height))

    bars=ax.bar(labels, values, color=['blue', 'orange', 'green', 'red', 'purple','cyan','saddlebrown','pink','lime'], edgecolor='black',zorder=3)

    # Añadir los valores sobre las columnas
    for bar in bars:
        yval = bar.get_height()
        ax.text(bar.get_x() + bar.get_width() / 2, yval, yval,
                ha='center', va='bottom',fontsize=24)

    # Cargar la imagen que se usará como marca de agua
    img = mpimg.imread("./watermark.png")
    # Proporción deseada
    ax_img = fig.add_axes([0.89, 0.01, 0.05, 0.05], anchor='NE')
    ax_img.imshow(img)
    ax_img.axis('off')


    # Obtener los límites del gráfico
    xlim = ax.get_xlim()
    ylim = ax.get_ylim()

    # Añadir líneas punteadas de cuadrícula en el fondo
    ax.grid(True, linestyle='--', linewidth=0.7, axis='y', color='gray',zorder=0)

    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_visible(True)

    ax.yaxis.set_visible(True)

    ax.set_title(title,fontsize=28,pad=30)
    ax.set_xlabel(nameAsamblea)

    ax.tick_params(axis='both', which='major', labelsize=20)
    plt.savefig(output_path)
    plt.close(fig)


@app.route('/create-plot/', methods=['POST'])
def create():
    data = request.get_json()
    create_plot(data['title'], data['labels'], data['values'], data['output'],data['nameAsamblea'])
    return "200"
    # return render_template('hello.html', person=name,person2=name2)