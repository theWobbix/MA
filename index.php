<?php
    include_once "php/db_connection.php";
    include_once "php/alert.php";

    session_start();
?>

<!DOCTYPE html>

<!-- Login -->

<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" media="screen" href="index.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>

<body>  
    <form class="loginformular" method="POST">

        <label id="titel">Anmeldung</label>

        <!-- Benutzername -->
        <div class="inputfield">
            <label>Benutzername</label>
            <input type="text" id="username" name="username" placeholder="Benutzername">
        </div>

        <!-- Passwort -->
        <div class="inputfield">
            <label>Passwort</label>
            <input type="password" id="PW" name="passwort" placeholder="Passwort">
        </div>

        <!-- Anmeldungs-Button -->
        <input type="submit" value="Anmelden" id="anmelden" name="submit">

    </form>

<?php

if(isset($_POST["submit"])){
    $username = mysqli_real_escape_string($connection, $_POST["username"]);
    $password = mysqli_real_escape_string($connection, $_POST["passwort"]);


    $username = explode(" ", $username);

    //Wenn ein Nutzer 2 Vornamen hat, werden diese innerhalb des If-Statements wieder zu einem String zusammengefügt,
    //sodass die zweite position im Array den Nachnamen erhält
    if(sizeof($username) == 3){
        $username = array($username[0]." ".$username[1], $username[2]);
    }

    //Def SQL Befehle
    $sql_schüler = "SELECT * FROM sus WHERE Vorname='$username[0]' AND Nachname='$username[1]';";
    $sql_lehrer = "SELECT * FROM lehrpersonen WHERE Vorname='$username[0]' AND Nachname='$username[1]';";

    $result = mysqli_query($connection, $sql_schüler);

    //Der folgende If-Block wird nur ausgeführt, wenn $result ein Ergebnis enthält (nicht 0 ist), also nur wenn die Suche nach dem User erfolgreich war
    if(mysqli_num_rows($result) > 0){
        $data = mysqli_fetch_assoc($result);

        if($data["pw"] == md5($password)){
            #Alles in diesem if-block wird nur ausgeführt, wenn pw und username korrekt sind.   
            
            #Speichert anmeldungsdaten
            $_SESSION["IDPerson"] = $data["IDPerson"];
            $_SESSION["StatusPerson"] = "Schüler";

            header("Location: Schüler/absenzenformular.php");
        }
    }
    else{
        //Da in der SchülerDB kein ergebnis gefunden wurde, wird nun in der LehrerDB gesucht.
        $result = mysqli_query($connection, $sql_lehrer);
        
        if(mysqli_num_rows($result) > 0){
            $data = mysqli_fetch_assoc($result);

            if($data["pw"] == md5($password)){
                #Alles in diesem if-block wird nur ausgeführt, wenn pw und username korrekt sind.

                #Speichert anmeldungsdaten
                $_SESSION["IDPerson"] = $data["IDPerson"];
                $_SESSION["StatusPerson"] = "Lehrer";
                
                #TODO: User zu Homepage weiterleiten mit:
                header("Location: Lehrer/AbsenzEintragen.php");
            }
        }
    }
    
    //Dieser Code wird nur erreicht, wenn Passwort oder Username falsch waren
    alert("Passwort oder Benutzername falsch");
}

?>

</body>

</html>