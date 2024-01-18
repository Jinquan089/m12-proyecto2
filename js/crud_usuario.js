var resultado = document.getElementById('usuarios');
var ajax = new XMLHttpRequest();
ajax.open('POST', '../php/reserva/proc_usuario.php');
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