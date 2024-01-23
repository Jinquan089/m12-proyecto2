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
    document.querySelectorAll('.edit-button').forEach(button => {
        button.addEventListener('click', () => {
            var row = button.closest('tr');
            var sillasCell = row.querySelector('.sillas-cell');
            var sillasValue = sillasCell.textContent;

            // Reemplaza el contenido de la celda con un input para editar
            sillasCell.innerHTML = `<input type="number" value="${sillasValue}" id="editedSillas">`;

            // Agrega un botón "Guardar" a la fila
            var saveButton = createSaveButton(row);

            // Agrega el botón "Guardar" a la celda de acciones y elimina el botón "Modificar"
            row.querySelector('.actions-cell').innerHTML = '';
            row.querySelector('.actions-cell').appendChild(saveButton);
        });
    });
}

function createSaveButton(row) {
    var saveButton = document.createElement('button');
    saveButton.textContent = 'Guardar';
    saveButton.classList.add('btn', 'btn-success');
    saveButton.addEventListener('click', () => {
        var editedSillas = document.getElementById('editedSillas').value;
        var idMesaInput = row.querySelector('.id-recurso');
        var idMesa = idMesaInput.id;
        sendAjaxRequest(idMesa, editedSillas);
    });

    return saveButton;
}

function sendAjaxRequest(idMesa, editedSillas) {
    var formdata = new FormData
    formdata.append('id_mesa', idMesa)
    formdata.append('sillas', editedSillas)
    var ajaxModificar = new XMLHttpRequest();
    ajaxModificar.open('POST', '../php/recurso/modificar.php');
    ajaxModificar.onload = function () {
        if (ajaxModificar.status === 200) {
            Swal.fire({
                title: "Modificado",
                text: "Modificado Correctamente",
                icon: "success"
            });
            recargar();
        } else {
            resultado.innerText = "Error";
        }
    };
    ajaxModificar.send(formdata);
}



recargar();
