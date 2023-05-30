<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Selection</title>
    <style>

        /* Place UserID and Seat DropDown beside each-other */
        .form-group {
            color: #333;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        /* Add padding between labels (text for fields) and fields */
        .label {
            margin-right: 5px;
        }

        input[type="number"],
        textarea {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: border-color 0.2s ease-in-out;
            width: 200px;
            margin-right: 10px;
        }

        input[type="submit"] {
            padding: 15px 20px;
            font-size: 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 10px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Center all form content */
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        select {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.1);
            width: 200px;
            background-repeat: no-repeat;
            background-position: right center;
        }

        option {
            background-color: #b8b8b8;
            color: #000;
        }

        p {
            font-family: "Arial", sans-serif;
            font-size: 18px;
            color: #333;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        img {
            display: block;
            margin-left: auto;
            margin-right: auto;
            width: 50%;
            border: 4px solid #333;
            transition: transform 0.3s;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 700px;
            height: 500px;
            margin-bottom: 20px;
        }
        img:hover {
            transform: scale(1.02);
        }

    </style>
</head>
<body>

<img src="images/seatReference.jpg" alt="Seat reference guide" />

<form action="" onsubmit='disableSubmitButton()' method="post">

    <div class="form-group">

        <label for="ID" class="label">UserID</label>
        <input type="number" id="ID" name="ID">

        <label for="seats" class="label">Choose a seat</label>
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

    </div>

    <?php

        // If no available seats exist, remove reserve button and replace with text
        if (count($availableSeats) != 0) {
            echo("<input type=\"submit\" value=\"Reserve Seat\" name=\"SubmitSeatSelection\">");
        } else {
            echo("<p style=\"color: red;\">Seats are now all gone!</p>");
        }
    
    ?>

    <script>
        // Visually update button, functions below are cosmetic
        const button = document.getElementsByName("SubmitSeatSelection")[0];
        function disableSubmitButton() {
            setTimeout(function () {
                try {
                    button.value = "Processing...";
                    button.disabled = true;
                    button.style.backgroundColor = "gray";
                } catch (error) {
                    // Do nothing on error
                }
            }, 10); // delay before invoking
        }
    </script>


    <?php

        // Check if submit button was pressed (i.e., is the variable set now)
        if (isset($_POST["SubmitSeatSelection"])) {
            // Check if accepting reservations
            global $acceptingReservations;
            if ($acceptingReservations) {
                // Check time
                global $disableResponseUntil;
                if (time() >= strtotime($disableResponseUntil)) {
                    // Check that at least one seat was determined to be available (they may not actually be available on submission)
                    if (count($availableSeats) != 0) {
                        // Call function to reserve seat with given arguments
                        reserveSeat($_POST["ID"],$_POST["seats"]);
                    }
                } else {
                    echo("<p style=\"color: blue;\">Reservations are not currently accepted. They will be at " . $disableResponseUntil . " (current time: " . date("H:i:s",time()) . ")</p>");
                }
            } else {
                echo("<p style=\"color: blue;\">Reservations are not currently being accepted at this time</p>");
            }

            exit();
    }

    ?>

</form>

</body>

</html>