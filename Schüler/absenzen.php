<?php
include_once "../php/db_connection.php";
include_once "../php/alert.php";
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
    <link rel="stylesheet" type="text/css" media="screen" href="css/absenzen.css" />
    <script src="../JS/SideMenu.js"></script>
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
        <a href="absenzenformular.php">Eintragen</a>
        <a href="#" id="active">Absenzen</a>
        <a href="optionen.php">Optionen</a>
    </div>

</header>

<main id="main">

<?php
    $IDPerson = $_SESSION["IDPerson"];
    $lektionenGesamt = 0;

    $sql = "SELECT * FROM absenzen WHERE IDPerson='$IDPerson';";
    $result = mysqli_query($connection, $sql);

    while($row = mysqli_fetch_assoc($result)){
        $lektionenGesamt += $row["AnzahlLektionen"];
    }
    echo "
        <div class='lektionenGesamt'>
            <label id='anzahlAbsenzenTitel'>Anzahl verpasster Lektionen</label>
            <label>$lektionenGesamt</label>
        </div>
    ";
?>


<div class="absenzenTableContainer">
    <table class="absenzenTable">
    <th>Datum</th>
    <th>Anzahl Lektionen</th>
    <th>Begründung</th>
    <?php
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
    ?>
    </table>
</div>

<div class="messageBox" id="messageBox">
    <label id="messageText">Test Message</label>
</div>

</main>

<footer>
    <div>Hannes Eybl<br>2018</div>
</footer>

</body>

</html>