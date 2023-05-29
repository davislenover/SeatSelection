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

        // Get all available seats
        $availableSeats = getAvailableSeats();

        if (count($availableSeats) != 0) {
            // Get all available seats and display in dropdown
            foreach ($availableSeats as $availableSeatName) {
                echo("<option value=\"$availableSeatName\">$availableSeatName</option>");
            }
        } else {
            // If no available seats exist, display empty option
            echo("<option value=\"\"></option>");
        }

        ?>

    </select>

    <br>

    <?php

        // If no available seats exist, remove reserve button and replace with text
        if (count($availableSeats) != 0) {
            echo("<input type=\"submit\" value=\"Reserve Seat\" name=\"SubmitSeatSelection\">");
        } else {
            echo("<p style=\"color: red;\">Seats are now all gone!</p>");
        }
    
    ?>


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