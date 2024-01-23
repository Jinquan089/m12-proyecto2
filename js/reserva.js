var resultado = document.getElementById("tiposala");
var ajax = new XMLHttpRequest();
ajax.open('POST', '../php/ajax/tipo_sala.php');
ajax.onload = function(){
    if(ajax.status == 200){
        resultado.innerHTML = "";
        var json = JSON.parse(ajax.responseText);
        resultado.innerHTML = json;
    } else{
        resultado.innerText = "Error"; 
    }
}
ajax.send();

crudreserva('');

var tiposala = document.getElementById('tiposala');
tiposala.addEventListener('change', ()=> {
    var mesa = document.getElementById('mesa');
    mesa.innerHTML="<option selected disabled>Mesa</option>";
    saladato = tiposala.value;
    num_silla(saladato);
})

function num_silla(valor){
    /* NUMEROS DE SALA */
    var resultado = document.getElementById('personas');
    var formdata = new FormData();
    formdata.append('tipo_sala',valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/ajax/num_silla.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            resultado.innerHTML = "";
            var json = JSON.parse(ajax.responseText);
            resultado.innerHTML = json;
        } else{
            resultado.innerText = "Error"; 
        }
    }
    ajax.send(formdata);
    
    resultado.addEventListener('change', ()=> {
        silla = resultado.value;
        var hora = document.getElementById('hora');
        var fecha = document.getElementById('fecha');
        hora.addEventListener('change', FechaHora);
        fecha.addEventListener('input', FechaHora);

        function FechaHora() {
            var datahora = hora.value;
            var datafecha = fecha.value;
            if (datahora && datafecha && silla && valor) {
                mesa(valor,silla,datahora,datafecha)
            }
        }
    })
}

function mesa(valor1, valor2, valor3, valor4) {
    var resultado = document.getElementById('mesa');
    var formdata = new FormData();
    formdata.append('tipo_sala',valor1);
    formdata.append('silla',valor2);
    formdata.append('hora',valor3);
    formdata.append('fecha',valor4);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/reserva/proc_mesa.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            resultado.innerHTML = "";
            var json = JSON.parse(ajax.responseText);
            resultado.innerHTML = json;
        } else{
            resultado.innerText = "Error"; 
        }
    }
    ajax.send(formdata);
}

var enviar = document.getElementById('frmReserva');
enviar.addEventListener('submit', (event)=> {
    event.preventDefault();
    var nombre = document.getElementById("nombre").value;
    var silla = document.getElementById("personas").value;
    var hora = document.getElementById("hora").value;
    var fecha = document.getElementById("fecha").value;
    var mesa = document.getElementById("mesa").value;

    reserva(nombre,silla,hora,fecha,mesa);
})

function reserva(valor1,valor2,valor3,valor4,valor5) {
    var resultado = document.getElementById('mesa');
    var formdata = new FormData();
    formdata.append('nomCli',valor1);
    formdata.append('silla',valor2);
    formdata.append('hora',valor3);
    formdata.append('fecha',valor4);
    formdata.append('mesa',valor5);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/reserva/proc_reserva.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            Swal.fire({
                title: "Reservado",
                text: "Reserva hecho",
                icon: "success"
              });
            document.getElementById('frmReserva').reset();
            document.getElementById('mesa').innerHTML = "<option selected disabled>Mesa</option>"
            crudreserva();
        } else {
            resultado.innerText = "Error"; 
        }
    }
    ajax.send(formdata);
}

function crudreserva() {
    var resultado = document.getElementById("crdreservados");
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/reserva/crud_reserva.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            resultado.innerHTML = "";
            var json = JSON.parse(ajax.responseText);
            resultado.innerHTML = json;
        } else{
            resultado.innerText = "Error"; 
        }
        document.querySelectorAll('.btn-warning').forEach(function(boton) {
                boton.addEventListener('click', function() {
                var idBoton = boton.id;
                cancelar(idBoton);
            });
        });
    }
    ajax.send();
}

function cancelar(id_reser) {
    var formdata = new FormData;
    formdata.append('id_reser', id_reser);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/reserva/cancelar.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            Swal.fire({
                title: "Cancelado",
                text: "Cancelacion hecho",
                icon: "success"
              });
            crudreserva();
        }
    }
    ajax.send(formdata);
}

