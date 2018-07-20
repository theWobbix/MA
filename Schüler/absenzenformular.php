<?php
include_once "../php/alert.php";
session_start();

//Leitet den User zur Login-Seite, wenn er nicht angemeldet ist.
if(!isset($_SESSION["IDPerson"])){
   header("Location: ../index.php");
}

?>

<!DOCTYPE html>

<html>

<head>
    <title>MA</title>
    <link rel="stylesheet" type="text/css" media="screen" href="../css/main.css" />
    <link rel="stylesheet" type="text/css" media="screen" href="css/absenzenformular.css" />
    <script src="JS/absenzenformular.js"></script>   
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
        <a href="#">Absenzen</a>
        <a href="optionen.php">Optionen</a>
        <a href="#">Feedback</a>
    </div>

</header>

<main id="main">

<div class="absenzenformular">
    <!-- Fächer-Input -->
    <div class="faecher-input">
        <!-- Dieses Table enthält alle "Fächer"-Eingabefelder -->
        <table id="fachTable">
            <tr id="zelle">
            <td>
            <!-- inputfield -->
            <div class="inputfield" id="fach">
                <label>Fächer</label>
                <input list="fächer" name="fach">
                <!-- Alle auswählbaren Fächer -->
                <datalist id="fächer">
                    <option value="Deutsch">
                    <option value="Physik">
                    <option value="Sport">
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
            <input type="text" id="lektionenanzahl">
        </div>

        <!-- Begründung -->
        <div class="inputfield">            
            <div id="begruendungLabel">
                <label>Begründung</label>
                <label id="characterlimit">0/100</label>
            </div>     
            <textarea id="begruendung" cols="30" rows="1" maxlength="100" onkeyup="UpdateCharacterLimit()"></textarea>
        </div>

        <!-- Datum -->
        <div class="inputfield">
            <label>Datum</label>
            <input type="date" id="date">
        </div>

        <!-- Absenden -->
        <input type="button" value="Absenden" id="absenden">

    </div>      
</div>

</main>

<script>
    function openSideMenu(){
        document.getElementById("side-menu").style.width = "250px";
        //document.getElementById("main").style.marginLeft = "250px";
    }

    function closeSideMenu(){
        document.getElementById("side-menu").style.width = "0px";
        //document.getElementById("main").style.marginLeft = "0px";
    }

    /*
    Der herauskommentierte Code schiebt den rest der Website beim öffnen des
    Sidenavs nach rechts. Dadurch sieht es dann so aus als ob das Sidenav den
    Rest der Website nach rechts schieben würde.
    */
</script>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>