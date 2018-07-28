function toggleArrow(id){
    var element_arrow = document.getElementById("arrow"+id);
    var element_klasse = document.getElementById("klasse_daten"+id);
    if(element_arrow.className == "arrowRight"){
        element_arrow.className = "arrowDown";
        element_klasse.style.maxHeight = "1000px";    
    }
    else{
        element_arrow.className = "arrowRight";
        element_klasse.style.maxHeight = "0px";
    }
}