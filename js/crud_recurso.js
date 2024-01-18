var resultado = document.getElementById('recurso');
var ajax = new XMLHttpRequest();
ajax.open('POST', '../php/reserva/proc_recurso.php');
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