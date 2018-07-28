<?php
include_once "../php/db_connection.php";
include_once "../php/alert.php";
session_start();

//Leitet den User zur Login-Seite, wenn er nicht angemeldet ist.
if(!isset($_SESSION["IDPerson"])){
   header("Location: ../index.php");
}
elseif($_SESSION["StatusPerson"] != "Lehrer"){
    header("Location: ../Schüler/absenzenformular.php");
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MA</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/absenzen.css" />
    <script src="../JS/SideMenu.js"></script>
    <script src="JS/absenzenEintragen.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
</head>

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
        <a href="absenzEintragen.php">Melden</a>
        <a href="#" id="active">Absenzen</a>
        <a href="optionen.php">Optionen</a>
    </div>

</header>

<main id="main">

<form class="absenzen" method="POST">

    <?php
    $personenCount = 0;
    $personen = array();
    $klassenCount = 0;
    $EFCount = 0;
    $SFCount = 0;

    $html = "";

    $userID = $_SESSION["IDPerson"];

    $sql = "SELECT DISTINCT Anlassnummer FROM anmeldungen WHERE IDPersonHauptleitung='$userID';";

    $result = mysqli_query($connection, $sql);

    while($row = mysqli_fetch_assoc($result)){
        $anlassID = $row['Anlassnummer'];

        $htmlTMP = "";
        $klassenCount += 1;

        $temp = explode("-", $anlassID);
        $fach = $temp[2];

        if(strpos($anlassID, "EF")){
            $EFCount += 1;
            $IDKlasse = "EF ".$EFCount;
            $fach = "EF_".$fach;
        }
        elseif (strpos($anlassID, "SF")){
            $SFCount += 1;
            $IDKlasse = "SF ".$SFCount;

            $fach = "SF_".$fach;
        }
        else{
            $IDKlasse = $temp[sizeof($temp)-1];
        }

        $htmlTMP .= "
            <div class='klasse'>
                <label for='btn_dropdown$klassenCount' class='dropdownArrowLabel'>
                    <i class='arrowRight' id='arrow$klassenCount'></i>
                </label>

                <input type='button' value='$IDKlasse' class='btn_dropdown' id='btn_dropdown$klassenCount' onClick='toggleArrow($klassenCount)'>

                <div class='klasse_daten' id='klasse_daten$klassenCount'>
        ";
        
        //Der innere Loop fügt alle Klassenmitglieder zum Dropdown hinzu        
        $sql = "SELECT Vorname, Nachname, IDPerson FROM anmeldungen WHERE Anlassnummer='$anlassID';";
        $result_klasse = mysqli_query($connection, $sql);
        while($row_klasse = mysqli_fetch_assoc($result_klasse)){
            $Vorname = $row_klasse["Vorname"];
            $Nachname = $row_klasse["Nachname"];
            $IDPerson = $row_klasse["IDPerson"];

            $personenCount += 1;
            $personen[] = $IDPerson;

            $htmlTMP .= "
                <div class='data'>
                    <input type='submit' value='$Vorname $Nachname' class='personBtn' name='person_submit[$IDPerson]'>
                </div>
            ";
        }

        $htmlTMP .= "
                </div>
            </div>
        ";

        $html .= $htmlTMP;
    }
    echo $html;
    ?>

</form>

<div class="absenzenTableContainer">
    <table class="absenzenTable">
    <th>Datum</th>
    <th>Anzahl Lektionen</th>
    <th>Begründung</th>
    <?php
        if(isset($_POST["person_submit"])){
            $IDSchueler = key($_POST["person_submit"]);
            $sql = "SELECT * FROM absenzen WHERE IDPerson='$IDSchueler';";
            $result = mysqli_query($connection, $sql);
            while($row = mysqli_fetch_assoc($result)){
                $datumAbsenz = $row["DatumAbsenz"];
                $datumAbsenz = explode("-", $datumAbsenz);
                $datumAbsenz = $datumAbsenz[2].".".$datumAbsenz[1].".".$datumAbsenz[0];

                $anzahlLektionen = $row["AnzahlLektionen"];
                $begruendung = $row["Begruendung"];

                $tr = "<tr>";
                $tr .= "<td>$datumAbsenz</td>";
                $tr .= "<td>$anzahlLektionen</td>";
                $tr .= "<td>$begruendung</td>";
                $tr .= "</tr>";
                echo $tr;
            } 
        }
    ?>
    </table>
</div>




<div class="messageBox" id="messageBox">
    <label class="messageText" id="messageText">Test Message</label>
</div>

</main>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>