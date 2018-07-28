<?php

include_once "../php/db_connection.php";

$sql = "INSERT INTO ma.absenzen (`gueltig`, `IDPerson`, `DatumAbsenz`, `DatumEintrag`, `AnzahlLektionen`, `Begruendung`, `Faecher`, `FaecherGesamt`) VALUES (DEFAULT, 1234, '12-12-1200', DEFAULT, NULL, NULL, NULL, NULL);";
#$sql = "INSERT INTO test.users (`user_first`, `user_last`) VALUES (NULL, DEFAULT);";

$x = mysqli_query($connection, $sql);
echo $x;
echo "success <br>";
echo mysqli_error($connection);

