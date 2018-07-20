<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Taschenrechner</title>
</head>
<body>
<form method="POST">
<input type="text" name="num1" placeholder="Number 1">
<input type="text" name="num2" placeholder="Number 2"> 

<select name="Operator">
    <option>None</option>
    <option>Subtract</option>
    <option>Add</option>
    <option>Divide</option>
    <option>Multiply</option>
</select>
<br>
<input type="submit" value="Calculate" name="submit" value="submit">
</form>

<p>The answer is:  </p>

<?php
    if(isset($_POST["submit"])){
        $num1 = $_POST["num1"];
        $num2 = $_POST["num2"];
        $operator = $_POST["Operator"];

        switch ($operator) {
            case "Add":
                echo $num1 + $num2;
                break;

            case "Subtract":
                echo $num1 - $num2;
                break;

            case "Multiply":
                echo $num1 * $num2;
                break;

            case "Divide":
                echo $num1 / $num2;
                break;

            default:
                echo "no operator selected";
                break;
        }
    }

?>
</body>
</html>