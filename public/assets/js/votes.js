function splitNumber(number) {

    // Convertir el número a su representación binaria de 8 bits
    let binary = number.toString(2).padStart(8, '0');

    // Obtener los primeros 4 bits y los últimos 4 bits
    let first4Bits = binary.slice(1, 4);
    let last4Bits = binary.slice(4, 8);

    // Convertir los primeros 4 bits y los últimos 4 bits de binario a decimal
    let first4Decimal = parseInt(first4Bits, 2);
    let last4Decimal = parseInt(last4Bits, 2);
    votes = ['A', 'B', 'C', 'D', 'E', 'F']
    voteString = votes[first4Decimal]
    return {
        vote: voteString,
        number: last4Decimal
    };
}
function handleReport(data, id) {


    let result = splitNumber(data[1]);

    control = (data[0] - 32) * 16 + result.number
    return {
        control: control,
        vote: result.vote
    }

}

document.getElementById('connectBtn').addEventListener('click', async () => {
    try {

        // Solicitar el acceso a un dispositivo HID
        const devices = await navigator.hid.requestDevice({ filters: [] });

        if (devices.length === 0) {
            console.log('No device selected');
            return;
        }

        // Conectar al dispositivo seleccionado
        const device = devices[0];
        await device.open();
        console.log(`Connected to ${device.productName}`);

        // Mostrar información del dispositivo
        const deviceInfo = document.getElementById('deviceInfo');


        // Escuchar los datos del dispositivo
        const dataStream = document.getElementById('dataStream');
        flag = 4;
        let ignoreSecond = false;
        let awaitSecond = false;
        let head = 0
        device.oninputreport = (event) => {
            flag++;

            if (flag > 4) {

                if (ignoreSecond) {
                    ignoreSecond = false;
                    return;
                }

                // Extraer el buffer de datos y el reportId del evento
                const { data, reportId } = event;
                console.log('reportId', reportId);
                const dataArray = new Uint8Array(data.buffer);

                // Obtener los primeros 3 elementos
                let receivedData = dataArray.slice(0, 3);
                // hay tres casos de respuesta
                //1. caso con reportID=3, viene un unico mensaje con toda la informacion correcta
                //2. caso con reportId=2, vienen dos mensajes el primero tiene reportId=2 y el otro en 1, solo el primero tiene la informacion correcta
                //3. caso con reportId=1, vienen dos, ambos tienen la informacion parcial, se tiene en cuenta el
                //primer elementol de cada uno

                //solo se maneja si no es 3
                if (reportId != 3) {
                    //si es uno se guarda el primer elemento y pasa
                    if (reportId == 1) {
                        awaitSecond = true;
                        head = receivedData[0]
                        return;
                    }
                    //si es dos ordena ignorar el siguiente
                    else if (reportId == 2 && !awaitSecond) {
                        ignoreSecond = true;
                    }
                    //es el segundo mensaje del caso 3
                    else if (reportId == 2 && awaitSecond ) {
                        let dataPass = [head, receivedData[0]]
                        receivedData = dataPass;
                        awaitSecond = false;
                    }
                }
                let dataResult = handleReport(receivedData, reportId)
                if(['A','B','C','D','E','F'].includes(dataResult.vote)){
                    dataStream.value += `\n${dataResult.control}-${dataResult.vote}`;
                }


            }
        };



        // Enviar comandos al dispositivo
        document.getElementById('sendCommandBtn').addEventListener('click', async () => {
            flag = 0;
            if ($controles = 100) {
                first = [0x03, 0x74, 0x86, 0xf2]
            }

            const commands = [

                [0x03, 0x55, 0x80, 0xd5],
                [0x0b, 0x5e, 0x80, 0xde, 0xb2, 0xb0, 0xb0, 0xb2, 0xb0, 0xb1, 0xb1, 0xb7],
                [0x06, 0x35, 0x96, 0x02, 0x10, 0x00, 0x10],
                [0x03, 0x03, 0x89, 0x8a],
                [0x03, 0x52, 0x86, 0xd4],
                [0x03, 0x5a, 0x80, 0xda]
            ];
            for (const command of commands) {
                try {


                    // Convertir el comando en un array de bytes
                    const commandArray = new Uint8Array(command);

                    // Enviar el comando al dispositivo
                    await device.sendReport(0x6, commandArray); // El primer argumento es el report ID, ajusta si es necesario
                    console.log('Command sent:', command);

                    // Esperar un poco antes de enviar el siguiente comando
                    await new Promise(resolve => setTimeout(resolve, 500));
                } catch (error) {
                    console.log('Error al escribir el comando: ', error)
                }

            }

        });

        // Enviar comandos al dispositivo
        document.getElementById('stopReadMode').addEventListener('click', async () => {

            const commands = [
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x5b, 0x80, 0xdb],
                [0x03, 0x03, 0x82, 0x81]
            ];
            for (const command of commands) {
                try {
                    // Convertir el comando en un array de bytes
                    const commandArray = new Uint8Array(command);

                    // Enviar el comando al dispositivo
                    await device.sendReport(0x6, commandArray); // El primer argumento es el report ID, ajusta si es necesario
                    console.log('Command sent:', command);

                    // Esperar un poco antes de enviar el siguiente comando
                    await new Promise(resolve => setTimeout(resolve, 500));
                } catch (error) {
                    console.log('Error al escribir el comando: ', error)
                }

            }

        });

        document.getElementById('cleanBtn').addEventListener('click', async () => {
            dataStream.value = ``;
            flag = 4
        });


    } catch (error) {
        console.error('Error accessing HID device:', error);
    }
});


