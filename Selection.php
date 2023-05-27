<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Selection</title>
</head>
<body>

<form action="ProcessInput.php" method="post">

    <label for="ID">UserID</label>
    <input type="number" id="ID" name="ID">

    <br>

    <label for="seats">Choose a seat</label>
    <select id="seats" name="seats">

        <?php

        include "SeatChecker.php";

        foreach (getAvailableSeats() as $availableSeatName) {

            echo("<option value=\"$availableSeatName\">$availableSeatName</option>");

        }

        ?>

    </select>

    <br>

    <input type="submit" value="Reserve Seat">

</form>

</body>
</html>