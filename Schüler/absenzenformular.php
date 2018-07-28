<?php
include_once "../php/alert.php";
include_once "../php/db_connection.php";
session_start();

//Leitet den User zur Login-Seite, wenn er nicht angemeldet ist.
if(!isset($_SESSION["IDPerson"])){
   header("Location: ../index.php");
}
elseif($_SESSION["StatusPerson"] != "Schüler"){
    header("Location: ../Lehrer/AbsenzEintragen.php");
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MA</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/absenzenformular.css" />
    <script src="JS/absenzenformular.js"></script>   
    <script src="../JS/SideMenu.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>

<!--
    Wireframe für diese Website: https://wireframe.cc/S5QzmB
-->

<body>
    
<header class="navbar" id="navbar">

    <div id="eingeloggt_als">
        <?php
            echo "Eingeloggt als: ", $_SESSION['IDPerson'];
        ?>
    </div>

    <span class="open-slide" id="open-slide">
        <a href="#" onclick="openSideMenu()">
            <svg width="30"height="30">
                <path d="M0,5 30,5" stroke="#fff" stroke-width="5"/>
                <path d="M0,14 30,14" stroke="#fff" stroke-width="5"/>
                <path d="M0,23 30,23" stroke="#fff" stroke-width="5"/>
            </svg>
        </a>
    </span>

    <div id="side-menu" class="side-nav">
        <a href="#" class="btn-close" onclick="closeSideMenu()">&times;</a>
        <a href="#" id="active">Eintragen</a>
        <a href="absenzen.php">Absenzen</a>
        <a href="optionen.php">Optionen</a>
    </div>

</header>

<main id="main">

<form class="absenzenformular" method="POST">
    <!-- Fächer-Input -->
    <div class="faecher-input">
        <!-- Dieses Table enthält alle "Fächer"-Eingabefelder -->
        <table id="fachTable">
            <tr id="zelle">
            <td>
            <!-- inputfield -->
            <div class="inputfield" id="fach">
                <label>Fächer</label>
                <input list="fächer" name="fach[]">
                <!-- Alle auswählbaren Fächer -->
                <datalist id="fächer">
                    <?php
                        $IDPerson = $_SESSION["IDPerson"];
                        $faecher_full = array();
                        $faecher_kurz = array();  

                        $sql = "SELECT * FROM anmeldungen WHERE IDPerson='$IDPerson';";
                                 
                        $result = mysqli_query($connection, $sql);
                        echo mysqli_error($connection);

                        while($row = mysqli_fetch_assoc($result)){

                            $anlassnummer = $row["Anlassnummer"];
                            $fach_kurz = explode("-", $anlassnummer);
                            //Der erste if-block sammelt die Abkürzungen der Fächer in einem Array
                            if(strpos($anlassnummer, "EF")){
                                $faecher_kurz[] = "EF_".$fach_kurz[2];
                            }
                            elseif(strpos($anlassnummer, "SF")){
                                $faecher_kurz[] = "SF_".$fach_kurz[2];
                            }
                            else{
                                $faecher_kurz[] = $fach_kurz[2];
                            }

    
                            $anlassbezeichnung = $row["Anlassbezeichnung"];
                            $fach_full = explode("-", $anlassbezeichnung);
                            //Der zweite if-block sammelt die vollen Namen der Fächer in einem Array
                            if(strpos($anlassbezeichnung, "EF")){
                                $faecher_full[] = "EF ".$fach_full[2];
                                $fach_full = "EF ".$fach_full[2];
                            }
                            elseif(strpos($anlassbezeichnung, "SF")){
                                $faecher_full[] = "SF ".$fach_full[2];
                                $fach_full = "SF ".$fach_full[2];
                            }
                            else{
                                $faecher_full[] = $fach_full[2];
                                $fach_full = $fach_full[2];
                            }
                            
                            echo "<option value='$fach_full'>";
                        }
                    ?>
                </datalist>
            </div>
            </td>
            </tr>
        </table> 
        <input type="button" value="+" id="plusbutton" onclick="addRow()">
    </div>

    <div id="nichtfaecher-input">
        
        <!-- Anzahl lektionen-->
        <div class="inputfield">
            <label>Anzahl Lektionen</label>
            <input type="text" id="lektionenanzahl" name="anzahlLektionen">
        </div>

        <!-- Begründung -->
        <div class="inputfield">            
            <div id="begruendungLabel">
                <label>Begründung</label>
                <label id="characterlimit">0/100</label>
            </div>     
            <textarea id="begruendung" name="begruendung" cols="30" rows="1" maxlength="100" onkeyup="UpdateCharacterLimit()"></textarea>
        </div>

        <!-- Datum -->
        <div class="inputfield">
            <label>Datum</label>
            <input type="date" id="date" name="datum">
        </div>

        <!-- Absenden -->
        <input type="submit" value="Absenden" id="absenden" name="absenden">

    </div>      
</form>

<div class="messageBox" id="messageBox">
    <label id="messageText">Test Message</label>
</div>

</main>

<?php

if(isset($_POST["absenden"])){
    $IDPerson = $_SESSION["IDPerson"];

    //Beim Fach muss keine Sicherheit für eine MySQL-Injection eingebaut werden, da die Werte in diesen Inputfeldern
    //sowieso nicht direkt in der DB eingetragen werden.
    $faecher_data = $_POST["fach"];
    $faecher = "";

    foreach($faecher_data as $fach){
        $faecher .= $faecher_kurz[array_search($fach, $faecher_full)].";";
    }

    $anzahlLektionen = mysqli_real_escape_string($connection, $_POST["anzahlLektionen"]);
    $begründung = mysqli_real_escape_string($connection, $_POST["begruendung"]);
    $date = mysqli_real_escape_string($connection, $_POST["datum"]);

    $sql = "SELECT * FROM absenzen WHERE IDPerson='$IDPerson' AND DatumAbsenz='$date';";
    $result = mysqli_query($connection, $sql);

    if(mysqli_num_rows($result)){
        //Result > 0, Absenz wurde bereits von Lehrer eingetragen.
        $sql = "UPDATE absenzen SET AnzahlLektionen='$anzahlLektionen', Begruendung='$begründung', Faecher='$faecher' WHERE IDPerson='$IDPerson' AND DatumAbsenz='$date';";
        mysqli_query($connection, $sql);
    }
    else{
        //Result = 0, Absenz wird zum ersten Mal eingetragen
        $sql = "INSERT INTO ma.absenzen(`gueltig`, `IDPerson`, `DatumAbsenz`, `DatumEintrag`, `AnzahlLektionen`, `Begruendung`, `Faecher`, `FaecherGesamt`) VALUES (DEFAULT, $IDPerson,'$date',DEFAULT,'$anzahlLektionen','$begründung','$faecher',null);";
        mysqli_query($connection, $sql);
    }
    messageBox("Absenz Eingetragen");
}

?>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>