/*______________________________________TIPO SALA______________________________________*/
var resultado = document.getElementById('dropdown1');
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

var tiposala = document.getElementById('dropdown1');
tiposala.addEventListener('change', ()=> {
    saladato = tiposala.value;
    num_sala_silla(saladato);
    actualizarLeyenda()
})

function num_sala_silla(valor){
    /* NUMEROS DE SALA */
    var resultado = document.getElementById('dropdown2');
    var formdata = new FormData();
    formdata.append('tipo_sala',valor);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/ajax/num_sala.php');
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
    /* NUMEROS DE SILLAS */
    var resultado2 = document.getElementById('dropdown3');
    var formdata2 = new FormData();
    formdata2.append('tipo_sala',valor);
    var ajax2 = new XMLHttpRequest();
    ajax2.open('POST', '../php/ajax/num_silla.php');
    ajax2.onload = function(){
        if(ajax2.status == 200){
            resultado2.innerHTML = "";
            var json2 = JSON.parse(ajax2.responseText);
            resultado2.innerHTML = json2;
        } else{
            resultado2.innerText = "Error"; 
        }
    }
    ajax2.send(formdata2);

    var resultado3 = document.getElementById('dropdown4');
    var ajax3 = new XMLHttpRequest();
    ajax3.open('POST', '../php/ajax/estado.php');
    ajax3.onload = function(){
        if(ajax3.status == 200){
            resultado3.innerHTML = "";
            var json3 = JSON.parse(ajax3.responseText);
            resultado3.innerHTML = json3;
        } else{
            resultado3.innerText = "Error"; 
        }
    }
    ajax3.send();

    var formdata4 = new FormData();
    formdata4.append('tipo_sala',valor);
    var resultado4 = document.getElementById('fondo_sala');
    var ajax4 = new XMLHttpRequest();
    ajax4.open('POST', '../php/ajax/fondo.php');
    ajax4.onload = function(){
        if(ajax4.status == 200){
            var json4 = JSON.parse(ajax4.responseText);
            resultado4.style.cssText = json4;
        } else{
            resultado4.innerText = "Error"; 
        }
    }
    ajax4.send(formdata4);
    /* LEYENDA */
    var formdata5 = new FormData();
    formdata5.append('tipo_sala',valor);
    var resultado5 = document.getElementById('leyenda');
    var ajax5 = new XMLHttpRequest();
    ajax5.open('POST', '../php/ajax/leyenda.php');
    ajax5.onload = function(){
        if(ajax5.status == 200){
            resultado5.innerHTML = "";
            var json5 = JSON.parse(ajax5.responseText);
            resultado5.innerHTML = json5;
        } else{
            resultado5.innerText = "Error"; 
        }
    }
    ajax5.send(formdata5);
}
/*______________________________________MESAS Y LEYENDA______________________________________*/
var numsala = document.getElementById('dropdown2');
var mesas = document.getElementById('dropdown3');
var estados = document.getElementById('dropdown4');

numsala.addEventListener('change', actualizarLeyenda);
mesas.addEventListener('change', actualizarLeyenda);
estados.addEventListener('change', actualizarLeyenda);

function actualizarLeyenda() {
    var saladato = tiposala.value;
    var numsaladato = numsala.value;
    var mesadato = mesas.value;
    var estadodato = estados.value;
    leyenda_mesa(saladato, numsaladato, mesadato, estadodato);
}

function leyenda_mesa(valor1, valor2, valor3, valor4) {
    var resultado = document.getElementById('leyenda');
    var resultado2 = document.getElementById('mapa');

    var formdata = new FormData();
    var formdata2 = new FormData();
    if (valor1 && valor1 !== "Tipo de Sala") {
        formdata.append('tipo_sala', valor1);
        formdata2.append('tipo_sala', valor1);
    }
    if (valor2 && valor2 !== "Numero de sala") {
        formdata.append('num_sala', valor2);
        formdata2.append('num_sala', valor2);
    }
    if (valor3 && valor3 !== "Numero de persona") {
        formdata.append('mesa', valor3);
        formdata2.append('mesa', valor3);
    }
    if (valor4 && valor4 !== "Estado de la mesa") {
        formdata.append('estado', valor4);
        formdata2.append('estado', valor4);
    }


    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/ajax/leyenda.php');
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

    var ajax2 = new XMLHttpRequest();
    ajax2.open('POST', '../php/mesa/proc_mesas.php');
    ajax2.onload = function(){
        if(ajax2.status == 200){
            resultado2.innerHTML = "";
            var json2 = JSON.parse(ajax2.responseText);
            resultado2.innerHTML = json2;
        } else{
            resultado2.innerText = "Error"; 
        }
        var formularios = document.querySelectorAll('button[name="btnenviarid"]');
        formularios.forEach(function(btn) {
            btn.addEventListener('click', function(event) {
                event.preventDefault();
                var id_mesa = this.getAttribute('id');
                ocupar(id_mesa);
            });
        });
    }
    ajax2.send(formdata2);
}

function ocupar(id_mesa) {
    var formdata = new FormData();
    formdata.append('id_mesa', id_mesa);
    var ajax = new XMLHttpRequest();
    ajax.open('POST', '../php/mesa/ocupada.php');
    ajax.onload = function(){
        if(ajax.status == 200){
            Swal.fire({
                title: "Correcto",
                text: "Hecho",
                icon: "success"
              });
            actualizarLeyenda()
        }
    }
    ajax.send(formdata);
}


