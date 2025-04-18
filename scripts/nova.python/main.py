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
import textwrap

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
    return "<p>Servidor On!</p>"

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
    stop_script()
    try:
        # Inicia el script en segundo plano
        process=subprocess.Popen(['python', 'votes.py'])
        return jsonify({"status": "Success","message":"Proceso ejecutado"}), 200
    except Exception as e:
        return jsonify({"status": "Error", "message": str(e)}), 500

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
    return jsonify({"status": "Error", "message": "El script no esta corriendo"}), 400

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
        return jsonify({"status": "Error", "message": "Error al conectar con el dispositivo"}), 400


@app.route('/stop-if-voting', methods=['GET'])
def stopIfVoting():
    global process
    if process is not None:
        stop_script()
        print('Cerrando dispositivo')
        closeDevice()
    return jsonify({"status": "Success", "message": "El script no esta corriendo"}), 200


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
    if len(values) > 6:
        width = 20
        height = 9
    else:
        width = 15
        height = 7.5

    if len(values) == 1:
        bar_width = 0.3
    else:
        bar_width = 0.8

    fig, ax = plt.subplots(figsize=(width, height))
    a, b = 'áéíóúüñÁÉÍÓÚÜ', 'aeiouunAEIOUU'
    trans = str.maketrans(a, b)
    for i in range(0, len(labels)):
        s = labels[i]
        labels[i] = s.translate(trans)
    title = title.translate(trans)


    # Ajustar el título si es demasiado largo
    if len(title) > 60:
        title = textwrap.fill(title[:135], width=90)  # Truncar y dividir en líneas
        title_fontsize = 15 # Reducir el tamaño de fuente a un tercio
    else:
        title_fontsize = 28

    # Configuración de barras
    bars = ax.bar(labels, values, width=bar_width, color=['red', 'orange', 'green', 'yellow', 'purple', 'cyan', 'saddlebrown', 'pink', 'lime'], edgecolor='black', zorder=3)


    # Añadir los values sobre las columnas
    for bar in bars:
        yval = bar.get_height()
        ax.text(bar.get_x() + bar.get_width() / 2, yval, yval,
                ha='center', va='bottom', fontsize=24)

    if len(bars)==1:
        bar = bars[0]
        bar.set_width(0.05)
        bar.set_x(0-(bar.get_width() / 2))



    # Cargar la imagen que se usará como marca de agua
    img = mpimg.imread('C:/xampp/htdocs/nova/scripts/nova.python/watermark2.png')
    ax_img = fig.add_axes([0.87, .02, 0.12, 0.12], zorder=0)  # Z-order 0 coloca la imagen detrás
    ax_img.imshow(img, extent=[0, 10, 0, 10 ], aspect='auto', alpha=0.8)
    ax_img.axis('off')


    # Añadir cuadrícula y configurar el gráfico
    ax.grid(True, linestyle='--', linewidth=0.7, axis='y', color='gray', zorder=0)
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_visible(True)
    ax.yaxis.set_visible(True)
    ax.set_title(title, fontsize=title_fontsize, pad=20)
    ax.set_xlabel(nameAsamblea)
    ax.tick_params(axis='both', which='major', labelsize=20)

    # Ajustar etiquetas de x
    max_chars = 25  # Máximo de caracteres
    wrapped_labels = [textwrap.fill(label[:max_chars], width=12) for label in labels]  # Divide en líneas

    # Aplicar etiquetas truncadas y ajustadas
    ax.set_xticks(range(len(labels)))
    ax.set_xticklabels(wrapped_labels, fontsize=14, ha='center', rotation=0)

    plt.savefig(output_path)
    plt.close(fig)


@app.route('/create-plot/', methods=['POST'])
def create():
    data = request.get_json()
    create_plot(data['title'], data['labels'], data['values'], data['output'],data['nameAsamblea'])
    return "200"
    # return render_template('hello.html', person=name,person2=name2)



def create_plot_elecciones(title,labels,values,output_path,nameAsamblea,delegados,blanco):



    # Ordenar los datos de mayor a menor
    labels.insert(0,'VOTO EN BLANCO')
    values.insert(0,blanco)

    max_valor = max(values)
    limite_x = max_valor / 0.95
    colores = [  '#00C040'if i <= delegados else '#318CE7' for i in range(len(labels))]
    colores.reverse()
    # colores.append("#AFCDEA")
    colores.insert(0,'#bceeff')
    # Ajustar tamaño de la figura dinámicamente según la cantidad de datos
    fig, ax = plt.subplots(figsize=(14, 0.5 * len(labels) + 2))
    ax.set_xlim(0, limite_x)
    

    # Crear gráfico de barras horizontales
    ax.barh(labels, values, color=colores, edgecolor='black', height=0.6)

    
    # Agregar values al final de cada barra
    for i, v in enumerate(values):
        ax.text(v + limite_x  * 0.02, i, str(v), ha='left', va='center', fontsize=10, color='black')

    # Mejoras en la presentación
    ax.set_position([0.2, 0.1, 0.6, 0.8])

    ax.set_title(title.upper(), fontsize=14,loc='center')

    # Quitar bordes innecesarios para un diseño más limpio

    # Cargar la imagen que se usará como marca de agua
    img = mpimg.imread('C:/xampp/htdocs/nova/scripts/nova.python/watermark2.png')
    ax_img = fig.add_axes([0.87, .02, 0.12, 0.12], zorder=0)  # Z-order 0 coloca la imagen detrás
    ax_img.imshow(img, extent=[0, 10, 0, 10 ], aspect='auto', alpha=0.8)
    ax_img.axis('off')


    # Añadir cuadrícula y configurar el gráfico

    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_visible(True)
    ax.yaxis.set_visible(True)


    ax.set_xlabel(nameAsamblea)
    ax.tick_params(axis='both', which='major', labelsize=20)

    wrapped_labels = [textwrap.fill(label[:28], width=28) for label in labels]  # Divide en líneas

    # Aplicar etiquetas truncadas y ajustadas
    ax.set_yticks(range(len(labels)))
    ax.set_yticklabels(wrapped_labels, fontsize=10)

    plt.savefig(output_path)
    plt.close(fig)

@app.route('/create-plot-elecciones',methods=['POST'])
def createPlotElecciones():
    data = request.get_json()
    
    create_plot_elecciones(data['title'], data['labels'], data['values'], data['output'],data['nameAsamblea'],data['delegados'],data['blanco'])
    return "200"
