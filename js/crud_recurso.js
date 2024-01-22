function recargar() {
    var resultado = document.getElementById('recurso');
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/recurso/proc_recurso.php');
    ajax.onload = function () {
        if (ajax.status == 200) {
            resultado.innerHTML = "";
            var json = JSON.parse(ajax.responseText);
            resultado.innerHTML = json;

            // Después de recargar, vuelve a agregar la funcionalidad de edición
            addEditEventToButtons();
        } else {
            resultado.innerText = "Error";
        }
    }
    ajax.send();
}

function addEditEventToButtons() {
    var editButtons = document.querySelectorAll('.edit-button');
    console.log(editButtons);
    editButtons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            var row = event.target.closest('tr');
            var sillasCell = row.querySelector('.sillas-cell');
            var sillasValue = sillasCell.textContent;

            // Reemplaza el contenido de la celda con un input para editar
            sillasCell.innerHTML = `<input type="number" value="${sillasValue}" id="editedSillas">`;

            // Agrega un botón "Guardar" a la fila
            var saveButton = document.createElement('button');
            saveButton.textContent = 'Guardar';
            saveButton.addEventListener('click', function () {
                // Obtiene el nuevo valor de sillas
                var editedSillas = document.getElementById('editedSillas').value;

                // Obtiene el id del recurso
                var idRecurso = row.querySelector('.id-recurso').textContent;

                // Realiza la solicitud AJAX para modificar el recurso
                var ajaxModificar = new XMLHttpRequest();
                ajaxModificar.open('POST', '../php/recurso/modificar.php');
                ajaxModificar.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                ajaxModificar.onload = function () {
                    if (ajaxModificar.status == 200) {
                        Swal.fire({
                            title: "Modificado",
                            text: "Modificado Correctamente",
                            icon: "success"
                        });

                        // Después de guardar, recarga la tabla
                        recargar();
                    } else {
                        resultado.innerText = "Error";
                    }
                };

                // Envía los datos necesarios al servidor
                var data = 'id=' + idRecurso + '&sillas=' + editedSillas;
                ajaxModificar.send(data);
            });

            // Agrega el botón "Guardar" a la celda de acciones
            row.querySelector('.actions-cell').appendChild(saveButton);
        });
    });
}

// Llama a recargar para cargar la tabla al cargar la página
recargar();
