<?php

//Diese Seite Testet das Abrufen von Daten auf der DB

include_once "../php/db_connection.php";

//Query funktioniert nur, wenn "Hannes" mit '-Klammern eingefasst ist!!
$sql = "SELECT * FROM sus WHERE Vorname='Hannes';";
$result = mysqli_query($connection, $sql);

if(mysqli_num_rows($result)){
    $row = mysqli_fetch_assoc($result);
    echo $row["Vorname"]," ",$row["Nachname"];
}
else{
    echo "ERROR";
}