import sys
import matplotlib.pyplot as plt
import matplotlib.image as mpimg

def dividir_lista(json_str):
    # Convertir el string JSON a una lista
    json_str = json_str.strip('[]')

    lista = json_str.split(',')

    lista = [element.strip().strip("'\"") for element in lista]
    punto_medio = len(lista) // 2

    # Dividir la lista en dos mitades
    primera_mitad = lista[:punto_medio]
    segunda_mitad = lista[punto_medio:]

    return primera_mitad, segunda_mitad



def getArgs():
    # Verifica si hay suficientes argumentos
    if len(sys.argv) > 1:
        # Obtiene el primer argumento (titulo)
        title = sys.argv[1]
        # Obtiene el segundo argumento (values & labels)
        valuesLabels= sys.argv[2]
        #obtiene el tercer argumento (output_path)
        output_path=sys.argv[3]

        nameAsamblea=sys.argv[4]

        #separa values de labels
        labels,values=dividir_lista(valuesLabels)
        if 'nominal'in output_path:
            values=[int(elemento) for elemento in values]
        else:
            values=[float(elemento) for elemento in values]




        return title,output_path,labels,values,nameAsamblea
    else:
        print("1000")

# def getArgsProof():
#     title="Aprobacion del Acta"
#     values=[ 13 , 14 , 2 , 0 , 1 ]
#     output_path="C:/Asambleas/Asambleas/Miradores_2024.07.25/Pregunta_18/nominalChart.png"
#     labels=[ 'Aprobado' , 'No Aprobado' , 'Abstencion' , 'Ausente' , 'Nulo' ]
#     return title,output_path,labels,values


def create_plot(title, labels, values, output_path,nameAsamblea):

    

    if len(values)>6:
        width=20
        height=9
    else:
        width =15
        height=7.5
    fig, ax = plt.subplots(figsize=(width,height))

    bars=ax.bar(labels, values, color=['blue', 'orange', 'green', 'red', 'purple','cyan','saddlebrown','pink','lime'], edgecolor='black')

    # Añadir los valores sobre las columnas
    for bar in bars:
        yval = bar.get_height()
        ax.text(bar.get_x() + bar.get_width() / 2, yval, yval,
                ha='center', va='bottom',fontsize=24)

    # Cargar la imagen que se usará como marca de agua
    img = mpimg.imread('C:/xampp/htdocs/nova/scripts/watermark.png')



    # Proporción deseada

    # Obtener los límites del gráfico
    xlim = ax.get_xlim()
    ylim = ax.get_ylim()


    ax_img = fig.add_axes([0.89, 0.01, 0.05, 0.05], anchor='NE')
    ax_img.imshow(img)
    ax_img.axis('off')
    ax.spines['top'].set_visible(False)
    ax.spines['right'].set_visible(False)
    ax.spines['left'].set_visible(False)


    ax.yaxis.set_visible(False)


    ax.set_title(title,fontsize=28,pad=30)
    ax.set_xlabel(nameAsamblea)

    ax.tick_params(axis='both', which='major', labelsize=20)
    plt.savefig(output_path)
    plt.close(fig)

if __name__ == "__main__":

    # Leer los argumentos de la línea de comandos
    data={'title': 'APROBACION DE ORDEN DEL DIA', 'output': 'C:/Asambleas/Asambleas/Miradores_2024.09.19/Preguntas/4_APROBACION DE ORDEN DEL DIA/nominalChart.png', 'labels': ['', '', '', 'APROBADO', '', 'NO APROBADO', 'Abstencion', 'Ausente', 'Nulo'], 'values': [0, 0, 0, 1, 0, 0, 5, 0, 6], 'nameAsamblea': 'Miradores_2024.09.19'}
    create_plot(data['title'], data['labels'], data['values'], data['output'],data['nameAsamblea'])
    print ('200')
