document.addEventListener("DOMContentLoaded", function(event) {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))


    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link')

    function colorLink(){
    if(linkColor){
        linkColor.forEach(l=> l.classList.remove('active'))
        this.classList.add('active')
    }
    }
    linkColor.forEach(l=> l.addEventListener('click', colorLink))

     // Your code to run since DOM is loaded and ready
    });
    function noNumbers(event) {
        var charCode = event.charCode;
        if (charCode >= 48 && charCode <= 57) {
            return false; // Bloquear el input si es un número
        }
        return true; // Permitir el input si no es un número
    }

    function onlyNumbers(event) {
        var charCode = event.charCode;  
        if (charCode < 48 || charCode > 57) {
            return false; // Bloquear el input si no es un número
        }
        return true; // Permitir el input si es un número
    }
    function validateMultipleOf50(input) {
        sleep(2)
        let value = parseInt(input.value, 10);

        if (value % 50 !== 0) {
            // Ajustar el valor al múltiplo de 50 más cercano
            input.value = Math.round(value / 50) * 50;
        }
    }


    function debouncedValidateMultipleOf50(input) {
        let timeout;
        // Si ya hay un temporizador en ejecución, lo cancela
        clearTimeout(timeout);

        // Establece un nuevo temporizador para ejecutar la validación después de 2 segundos
        timeout = setTimeout(() => {
            validateMultipleOf50(input);
        }, 2000); // 2000 milisegundos = 2 segundos
    }

    function validateMultipleOf50(input) {

        let value = parseFloat(input.value);
        if (value>800) {
            return input.value=800;
        }
        // Si el campo está vacío, no hacer nada
        if (isNaN(value)) {
            return;
        }

        // Validar si el valor es un múltiplo de 50
        if (value % 50 !== 0) {
            // Ajustar el valor al múltiplo de 50 más cercano
            let adjustedValue = Math.ceil(value / 50) * 50;
            input.value = adjustedValue;
        }
    }
