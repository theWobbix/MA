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
    <link rel="stylesheet" type="text/css" media="screen" href="css/absenzEintragen.css" />
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
        <a href="#" id="active">Melden</a>
        <a href="absenzen.php">Absenzen</a>
        <a href="optionen.php">Optionen</a>
    </div>

</header>

<main id="main">

<form class="melden" method="POST">

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
                    <input type='checkbox' value='$IDPerson;$fach' class='absenzToggleBtn' id='absenzToggle$personenCount' name='absenzToggle[]'>
                    <label for='absenzToggle$personenCount' class='absenzToggleBtnVisible'></label>
                    <label for='absenzToggle$personenCount'>$Vorname $Nachname</label>
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

    <input type="submit" value="Absenzen Melden" class="btn_big" name="btn_submit">

</form>

<div class="messageBox" id="messageBox">
    <label class="messageText" id="messageText">Test Message</label>
</div>

</main>

<?php

if(isset($_POST["btn_submit"])){
    $absenzToggle = $_POST["absenzToggle"];

    $date = date("d-m-Y");

    //Loop durch jeden Schüler 
    foreach($absenzToggle as $toggle){
        $toggle = explode(";", $toggle);

        $IDSchüler = $toggle[0];
        $fach = $toggle[1];

        $sql = "SELECT * FROM absenzen WHERE IDPerson='$IDSchüler' AND DatumAbsenz='$date';";
        $result = mysqli_query($connection, $sql);

        if(mysqli_num_rows($result)){
            //Result ist grösser als 0, also existiert bereits eine Absenz für diesen Schüler an diesem Tag

            $result = mysqli_fetch_assoc($result);
        
            //In der Liste der Fächer wird das Fach das der User Unterrichtet eingetragen
            $sql = "UPDATE absenzen SET FaecherGesamt=CONCAT(FaecherGesamt, '$fach;') WHERE IDPerson=$IDSchüler and DatumAbsenz='$date';";
            mysqli_query($connection, $sql);
            
        }
        else{
            //Result ist 0, neue Absenz wird kreiert
            $sql = "INSERT INTO ma.absenzen(`gueltig`, `IDPerson`, `DatumAbsenz`, `DatumEintrag`, `AnzahlLektionen`, `Begruendung`, `Faecher`, `FaecherGesamt`) VALUES (DEFAULT, $IDSchüler,'$date',DEFAULT,null,null,null,'$fach;');";

            mysqli_query($connection, $sql);   
        }        
    }
    messageBox("Absenzen Eingetragen");
}

?>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>