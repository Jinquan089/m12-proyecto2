
recargar('');

function recargar() {
    var resultado = document.getElementById('usuarios');
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/usuario/proc_usuario.php');
    ajax.onload = function () {
        if (ajax.status == 200) {
            resultado.innerHTML = "";
            json = JSON.parse(ajax.responseText);
            var options = '';
            json.forEach(function (row) {
                var id = row.id_user;
                options += "<tr>";
                options += "<td>" + row.user + "</td>";
                options += "<td>" + row.nombre + "</td>";
                options += "<td>" + row.ape1 + "</td>";
                options += "<td>" + row.ape2 + "</td>";
                options += "<td>" + row.correo + "</td>";
                options += "<td>" + row.telf + "</td>";
                options += "<td>" + row.rol + "</td>";
                options += "<td> <button type='button' class='btn btn-warning' data-id='" + id + "'>Modificar</button> </td>";
                options += "</tr>";
            });
            resultado.innerHTML = options;
            addClickEventToButtons();
        } else {
            resultado.innerText = "Error";
        }
    }
    ajax.send();
}


function addClickEventToButtons() {
    var buttons = document.querySelectorAll('.btn-warning');
    buttons.forEach(function (button) {
        button.addEventListener('click', function (event) {
            var id_user = event.target.dataset.id;
            showModifyForm(id_user);
        });
    });
}

function showModifyForm(id_user) {
    var modificar = document.getElementById('modificar');
    var ajax = new XMLHttpRequest();
    var formData = new FormData();
    formData.append('id_user', id_user);
    ajax.open('POST', '../php/usuario/proc_usuario.php');
    ajax.onload = function () {
        if (ajax.status == 200) {
            modificar.innerHTML = "";
            json = JSON.parse(ajax.responseText);
            json.forEach(function (row) {
                var id = row.id_user;
                    modificar.innerHTML = `
                    <div class='columna1 card'>
                        <form action='' method='post' id='frm'>
                            <div class='form-group'>
                                <input type='hidden' name='id_user' id='id_user' value='${id}'>
                                <label for='user'>Usuario</label><br>
                                <input type='text' name='user' id='user' placeholder='Usuario' class='form-control' value='${row.user}'>
                            </div>
                            <div class='form-group'>
                                <label for='nombre'>Nombre</label><br>
                                <input type='text' name='nombre' id='nombre' placeholder='Nombre' class='form-control' value='${row.nombre}'>
                            </div>
                            <div class='form-group'>
                                <label for='ape1'>Apellido</label><br>
                                <input type='text' name='ape1' id='ape1' placeholder='Apellido' class='form-control' value='${row.ape1}'>
                            </div>
                            <div class='form-group'>
                                <label for='ape2'>Apellido2</label><br>
                                <input type='text' name='ape2' id='ape2' placeholder='Apellido2' class='form-control' value='${row.ape2}'>
                            </div>
                            <div class='form-group'>
                                <label for='correo'>Correo</label><br>
                                <input type='text' name='correo' id='correo' placeholder='Correo' class='form-control' value='${row.correo}'>
                            </div>
                            <div class='form-group'>
                                <label for='telf'>Telefono</label><br>
                                <input type='text' name='telf' id='telf' placeholder='Telefono' class='form-control' value='${row.telf}'>
                            </div>
                            <div class='form-group'>
                                <br><input type='button' value='Enviar' id='Enviar-${id}' class='btn btn-primary btn-block' class='form-control'>
                            </div>
                        </form>
                    </div>
                `;
            });
            var enviarButton = document.getElementById('Enviar-'+ id_user);
                enviarButton.addEventListener('click', function () {
                    var formData = new FormData(document.getElementById('frm'));
                    var ajaxModificar = new XMLHttpRequest();
                    ajaxModificar.open('POST', '../php/usuario/modificar.php');
                    ajaxModificar.onload = function () {
                        if (ajaxModificar.status == 200) {
                            Swal.fire({
                                title: "Modificado",
                                text: "Modificado Correctamente",
                                icon: "success"
                              });
                              recargar();
                              quitarfomulario();
                        }
                    };
                    ajaxModificar.send(formData);
                });
        }
    }
    ajax.send(formData);
}

function quitarfomulario() {
    var modificar = document.getElementById('modificar');
    modificar.innerHTML = "";
}