function limitarLongitud(element, maxLength) {
    // Verifica si la longitud del valor del elemento es mayor que la longitud máxima
    if (element.value.length > maxLength) {
        // Si es mayor, recorta el valor del elemento a la longitud máxima
        element.value = element.value.slice(0, maxLength);
    }
}

function validaFormulario() {
    // Obtiene los valores de los campos de usuario y contraseña
    var user = document.getElementById("user").value;
    var pwd = document.getElementById("pwd").value;

    // Obtiene el elemento de error
    var error = document.getElementById("error");

    // Deja en blanco el elemento error
    error.textContent = '';

    // Inicializa una variable con valor 'true'
    var isValid = true;

    // Verifica si alguno de los campos está vacío
    if (user === '' || pwd === '') {
        // Si hay campos vacíos, establece un mensaje de error y marca el formulario como no válido
        error.textContent = "Hay uno o varios campos vacíos";
        isValid = false;
    }

    // Devuelve la validez del formulario
    return isValid;
}

