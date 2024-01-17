var resultado = document.getElementById('historial');
var ajax = new XMLHttpRequest();
ajax.open('POST', '../php/mesa/mostrarhi.php');
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