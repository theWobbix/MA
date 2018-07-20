<?php
include_once "../php/db_connection.php";
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
    <link rel="stylesheet" type="text/css" media="screen" href="css/optionen.css" />
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
        <a href="absenzenformular.php" >Eintragen</a>
        <a href="#">Absenzen</a>
        <a href="#" id="active">Optionen</a>
        <a href="#">Feedback</a>
    </div>

</header>

<main id="main">

<div class="optionen">

    <!-- Passwort  -->
    <div class="option" id="opt_passwort">
        <label class="opt_titel">Passwort</label>

        <input type="button" value="Passwort ändern" class="btn_open" onclick="openPwMenu()">

        <form class="passwort_ändern" method="POST" id="form_passwort_ändern">

            <div class="inputfield">
                <label>Altes Passwort</label>
                <input type="password" name="pw_old">
            </div>
            
            <div class="inputfield">
                <label>Neues Passwort</label>
                <input type="password" name="pw_new">
            </div>

            <input type="submit" name="pw_submit" value="Ändern" class="btn_big">
        </form>
    </div>

    <!-- Logout -->
    <div class="option" id="logout">

        <label class="opt_titel">Ausloggen</label>

    <form method="POST">
        <input type="submit" name="btn_logout" class="btn_big" value="Logout">
    </form>
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

    //Wechselt zwischen offenem und geschlossenem Menü
    function openPwMenu(){
        var element = document.getElementById("form_passwort_ändern");

        if(element.style.height == "0px"){
            element.style.height = "339px";

            //Stellt den overflow auf "visible", da der Schatten des Knopfes über den Rand des Formulars herausragt
            //und vollständig dargestellt werden soll.
            setTimeout(() => {
                element.style.overflow = "visible"
            }, 350);
                          
        }
        else{
            element.style.overflow = "hidden";
            element.style.height = "0px";

            /*
            Wenn der Button in weniger als 450ms 2 Mal gedrückt wurde, wird der Overflow erst dann auf "visible"
            gestellt, wenn sich das Formular bereits geschlossen hat. Um diesen Bug zu verhindern wird das Formular
            beim Schliessen nach 451ms ein zweites mal auf "hidden" gestellt.
            */
            setTimeout(() => {
                element.style.overflow = "hidden"
            }, 351);

        }
    }
</script>

<?php

if(isset($_POST["pw_submit"])){
    
    $pw_new = $_POST["pw_new"];
    $pw_old = $_POST["pw_old"];

    //$_SESSION-Values sind schwer in strings zu bekommen, also:
    $IDPerson = $_SESSION["IDPerson"];

    //Def SQL
    $sql = "SELECT pw FROM sus WHERE IDPerson='$IDPerson';";

    $result = mysqli_query($connection, $sql);
    $data = mysqli_fetch_assoc($result);

    if($data["pw"] == md5($pw_old)){

        //Vor dem einspeichern PW verschlüsseln
        $pw_new = md5($pw_new);

        $sql = "UPDATE sus SET pw='$pw_new' WHERE IDPerson='$IDPerson';";

        mysqli_query($connection, $sql);
    }
    else{
        alert("Falsches Passwort");
    }
}

if(isset($_POST["btn_logout"])){
    session_destroy();
    header("Location: ../index.php");
}

?>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>