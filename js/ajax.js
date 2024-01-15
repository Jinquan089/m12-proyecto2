/*__________________________________________________________________*/
/* TIPO DE SALAS */
document.addEventListener("DOMContentLoaded", function() {
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

});

var tiposala = document.getElementById('dropdown1');
tiposala.addEventListener('change', ()=> {
    saladato = tiposala.value;
    num_sala_silla(saladato);
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
            resultado4.innerHTML = "";
            var json4 = JSON.parse(ajax4.responseText);
            resultado4.style.cssText = json4;
        } else{
            resultado4.innerText = "Error"; 
        }
    }
    ajax4.send(formdata4);

}
/*__________________________________________________________________*/

/* var btnfiltros = document.getElementById("btnfiltros");
btnfiltros.addEventListener('click', (event)=> {
    event.preventDefault();
    saladato = tiposala.value;
    fondo_mesas(saladato);
}) */


/* CARGAR FILTROS */