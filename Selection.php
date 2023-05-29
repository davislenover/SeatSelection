<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Selection</title>
</head>
<body>

<form action="" method="post">

    <label for="ID">UserID</label>
    <input type="number" id="ID" name="ID">

    <br>

    <label for="seats">Choose a seat</label>
    <select id="seats" name="seats">

        <?php

        include "SeatChecker.php";

        // Get all available seats and display in dropdown
        foreach (getAvailableSeats() as $availableSeatName) {
            echo("<option value=\"$availableSeatName\">$availableSeatName</option>");
        }

        ?>

    </select>

    <br>

    <input type="submit" value="Reserve Seat" name="SubmitSeatSelection">

    <?php

    // Check if submit button was pressed (i.e., is the variable set now)
    if (isset($_POST["SubmitSeatSelection"])) {
        // Call function to reserve seat with given arguments
        reserveSeat($_POST["ID"],$_POST["seats"]);
    }

    ?>

</form>

</body>
</html>