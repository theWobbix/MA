//Code für AddRow() von: http://techstream.org/Web-Development/PHP/Dynamic-Form-Processing-with-PHP
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
        window.scrollBy(0, 139);
    }
}

//Aktualisiert die Anzeige für die momentane Anzahl von Zeichen in der Textarea
function UpdateCharacterLimit(){
    var currentlenght = document.getElementById("begruendung").value.length;
    document.getElementById("characterlimit").innerText = currentlenght + "/100";
}
