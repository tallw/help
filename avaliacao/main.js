/*var radios = document.querySelectorAll('input');

    for(var i = 0; i < radios.length; i++){
        if (radios.checked) {
            // do whatever you want with the checked radio
            console.log(radios.value);
            
            break;
        } 
    }
*/

/* CONTAGEM REGRESSIVA*/


var min = 10, seg = 11,
    elemento = document.querySelector("span");
    
    window.addEventListener("load", function()
    {
        elemento.innerHTML = min + ":" + seg;

          var intervalo = setInterval(function(){
       	    seg--;
            elemento.innerHTML = min + ":" + seg;
            //Acrescentando 0 antes dos valores menores que 10
            if(seg < 10 && min < 10) elemento.innerHTML = "0" + min + ":" + "0" + seg;
          	else if(seg < 10) elemento.innerHTML = min + ":" + "0" + seg;
            else if(min < 10) elemento.innerHTML = "0" + min + ":" + seg;
            
            //Parar a contagem caso os valores do minuto e do segundo forem igual a 0
            if(seg == 0 && min == 0){
            	clearInterval(intervalo);
            }
            //Decrementar o valor do min caso o valor do seg for 0
            else if(seg < 0){
              min--;
              seg = 59;
              if(seg == 59){ elemento.innerHTML = "0" + min + ":" + seg;}
            }
          }, 1000);
        
    });
	
    
