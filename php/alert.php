<?php

//Simple Funktion, die eine Popup-Message via JS hervorruft
function alert($message){
    echo "<script type'text/javsscript'>alert('$message');</script>";
}

//Öffnet eine MessageBox am unteren Rand der Website
function messageBox($message){
    echo "<script type'text/javsscript'>
        document.getElementById('messageText').innerHTML = '$message';

        var element = document.getElementById('messageBox');

        setTimeout(() => {
            element.style.bottom = '0px';
        }, 200);

        setTimeout(() => {
            element.style.bottom = '-50px';
        }, 5000);
    </script>";    
}
