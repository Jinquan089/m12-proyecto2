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
        FechaHora();
    })
}

function mesa(valor1, valor2, valor3, valor4) {
    console.log(valor1);
    console.log(valor2);
    console.log(valor3);
    console.log(valor4);
}

/* function mesa(valor1, valor2, valor3, valor4) {
    var resultado = document.getElementById('mesa');
    var formdata = new FormData();
    formdata.append('tipo_sala',valor1);
    formdata.append('silla',valor2);
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
} */