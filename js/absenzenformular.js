function addRow(){
    var table = document.getElementById("fachTable");

    var zeilenanzahl = table.rows.length;
    
    if(zeilenanzahl >= 10){
        alert("Maximal 10 Fächer");
    }
    else{
        var zeile = table.insertRow(zeilenanzahl);
        var neueZelle = zeile.insertCell(0);
        neueZelle.innerHTML = table.rows[0].cells[0].innerHTML;

        //Scrollt runter damit das neue Feld direkt auf der höhe des Cursors ist
        window.scrollBy(0, 135);
    }
}