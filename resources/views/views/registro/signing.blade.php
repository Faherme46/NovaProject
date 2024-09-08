<div class="">
    <style>
        body {
            background-color: rgb(178, 178, 178);
        }
    </style>
    <x-alerts />


    <div class="position-fixed z-2 top-0 start-0 px-4 pt-2 d-flex justify-content-between align-items-center">
        <button wire:click='goBack' class="btn btn-primary py-0">
            <i class="bi bi-arrow-bar-left fs-3"></i>
        </button>
    </div>
    <div class="mt-5 ">

        <div class=" py-5  my-5 d-flex justify-content-center align-content-center">
            <div class=" px-3">
                <div class="row mb-3 justify-content-center">
                    <button class="btn btn-danger d-flex align-items-center w-auto border border-light " id="clearButton"
                        type="button">
                        <i class="bi bi-trash me-1"></i>
                        <h4 class="mb-0">Borrar</h4>
                    </button>
                    <button class="btn btn-success d-flex align-items-center w-auto ms-4 border border-light "
                        id="saveButton" type="button">
                        <i class="bi bi-floppy me-1"></i>
                        <h4 class="mb-0">Guardar</h4>
                    </button>
                </div>


                <canvas id="drawing-canvas" class="bg-light"></canvas>
            </div>

        </div>

    </div>

</div>
<script>
    // Obtener el canvas y el contexto de dibujo 2D
    const canvas = document.getElementById('drawing-canvas');
    const ctx = canvas.getContext('2d');

    // Establecer el tamaño del canvas al 60% del tamaño de la ventana
    canvas.width = window.innerWidth * 0.6;
    canvas.height = window.innerHeight * 0.6;

    function drawLine() {
        // Dibujar una línea horizontal (simulando un <hr>)
        ctx.beginPath();
        ctx.moveTo(canvas.width / 4, canvas.height - canvas.height / 4); // Punto de inicio
        ctx.lineTo(canvas.width - canvas.width / 4, canvas.height - canvas.height / 4); // Punto final
        ctx.lineWidth = 1; // Grosor de la línea
        ctx.strokeStyle = '#000000'; // Color de la línea
        ctx.stroke();

        const text = "FIRMA";
        ctx.font = "Bold 10pt  Arial "; // Fuente y tamaño de texto
        ctx.textAlign = "center"; // Alineación centrada
        ctx.fillStyle = "#000000"; // Color del texto
        ctx.fillText(text, canvas.width / 2, canvas.height - canvas.height / 4 + 20);
    }

    drawLine();
    // Variables para controlar el dibujo
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Función para comenzar el dibujo
    // Función para iniciar el dibujo (tanto para mouse como táctil)
    function startDrawing(e) {
        isDrawing = true;
        const {
            offsetX,
            offsetY
        } = getEventPosition(e);
        [lastX, lastY] = [offsetX, offsetY]; // Guardar la posición inicial
    }

    // Función para dibujar en el canvas (tanto para mouse como táctil)
    function draw(e) {
        if (!isDrawing) return; // Si no se está dibujando, salir de la función

        const {
            offsetX,
            offsetY
        } = getEventPosition(e);

        ctx.strokeStyle = '#000000'; // Color de la línea
        ctx.lineWidth = 2; // Grosor de la línea
        ctx.lineJoin = 'round';
        ctx.lineCap = 'round';

        ctx.beginPath();
        ctx.moveTo(lastX, lastY); // Mover a la última posición registrada
        ctx.lineTo(offsetX, offsetY); // Dibujar línea hacia la nueva posición
        ctx.stroke();

        // Actualizar la posición
        [lastX, lastY] = [offsetX, offsetY];
    }

    // Función para detener el dibujo (tanto para mouse como táctil)
    function stopDrawing() {
        isDrawing = false;
    }

    // Función para obtener la posición del evento (mouse o táctil)
    function getEventPosition(e) {
        let offsetX, offsetY;
        if (e.type.includes('touch')) {

            const touch = e.touches[0] || e.changedTouches[0];
            const rect = canvas.getBoundingClientRect();
            console.log(touch)
            offsetX = touch.clientX - rect.left + window.scrollX ;
            offsetY = touch.clientY - rect.top + window.scrollY ;
        } else {
            offsetX = e.offsetX;
            offsetY = e.offsetY;

        }

        return {
            offsetX,
            offsetY
        };
    }
    // Eventos del ratón





    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    // Listeners para eventos táctiles
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);
    canvas.addEventListener('touchcancel', stopDrawing);

    function clearCanvas() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawLine();
    }

    function saveCanvasAsImage() {
        // Convertir canvas a data URL
        image = canvas.toDataURL('image/png');
        // Llama al componente Livewire y envía el Blob
        Livewire.dispatch('uploadCanvasImage', {
            image: image
        });

    }
    document.getElementById('saveButton').addEventListener('click', saveCanvasAsImage);
    document.getElementById('clearButton').addEventListener('click', clearCanvas);
</script>
