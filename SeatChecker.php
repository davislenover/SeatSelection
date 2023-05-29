<?php

    include "SQLComs.php";
    include "DatabaseInfo.php";

    function getAvailableSeats() {

        global $serverName, $password, $tableName, $database, $defaultUser;

        // Get all seat names
        $newSQLCon = new SQLComs($serverName,$defaultUser,$password,$database,$tableName);
        // Get all non-inserted rows thus far
        $returnNames = $newSQLCon->getNullColumns();
        // Close connection
        $newSQLCon->closeConnection();

        return $returnNames;

    }

    function reserveSeat($userID, $seatToReserve) {

        global $serverName, $password, $tableName, $database, $allowMultipleResponses;

        try {

            // Attempt to get connection
            $newSQLCon = new SQLComs($serverName,$userID,$password,$database,$tableName);

            // Prepare transaction (a transaction operates on a temporary database)
            $newSQLCon->beginTransaction();

            // Before performing main queries in transaction, check if multiple seat selection from the same user is not allowed
            if (!$allowMultipleResponses) {
                // If so, check if the userID already reserved a seat
                if ($newSQLCon->doesIDExistInRow($userID)) {
                    // If so commit transaction and close connection, throw corresponding error
                    $newSQLCon->commitTransaction();
                    $newSQLCon->closeConnection();
                    throw new Exception($userID,1001);
                }
            }

            $statement = $newSQLCon->invokeTransaction(getNotNullRowOfColumn($tableName,$seatToReserve));

            // Check if any rows returned (this means that a row has already been reserved)
            if ($statement->num_rows > 0) {
                // Commit transaction (as it was only reading, not writing) and close connection
                $newSQLCon->commitTransaction();
                $newSQLCon->closeConnection();
                // Throw error to indicate failure
                throw new Exception($seatToReserve,1000);

            } else {

                // If no rows returned, then reserve seat (i.e., add userID to specified seat column in a new row)
                $statement = $newSQLCon->invokeTransaction(insertRow($tableName,$seatToReserve,$userID));
                // Commit changes and close connection
                $newSQLCon->commitTransaction();
                $newSQLCon->closeConnection();

            }

            // Display successful reservation
            echo("<p style=\"color: green;\">Seat: " . $seatToReserve  . ", has been reserved! Time: " . date('H:i:s') . "</p>");

        // Catch exception if connection fails
        } catch (Exception $exception) {
            // Auth error (username is incorrect)
            if ($exception->getCode() == 1045) {
                // Echo red error message telling user the UserID is incorrect
                if ($userID == "") {
                    echo("<p style=\"color: red;\">Please enter a UserID</p>");
                } else {
                    echo("<p style=\"color: red;\">Invalid UserID (" . $userID . "), please check numerical value and try again</p>");
                }

            } elseif ($exception->getCode() == 1000) {

                // Echo red error message telling user that the seat could not be reserved
                echo("<p style=\"color: red;\">Seat: " . $exception->getMessage() . ", is no longer available. Please select a different seat</p>");

            } elseif ($exception->getCode() == 1001) {

                // Echo red error message telling user that the seat could not be reserved because they already reserved one
                echo("<p style=\"color: red;\">The given UserID (" . $exception->getMessage() . "), has already reserved a seat</p>");

            } else {

                // If an unknown error occurs
                echo("<p style=\"color: red;\">(" . $exception->getCode() . ") an unidentified error occured. Please try again. If the issue persists, please contact an administrator</p>");

            }

        }

    }
