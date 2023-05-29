<?php

    include "SQLComs.php";
    include "DatabaseInfo.php";

    // Function to get open seats from SQL database
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

    // Function to attempt to reserve a seat in SQL database
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

            // Check if any rows returned with data for given seat (this means that the given seat has already been reserved)
            if ($newSQLCon->doRowsExistForColumn($seatToReserve)) {
                // Commit transaction (as it was only reading, not writing) and close connection
                $newSQLCon->commitTransaction();
                $newSQLCon->closeConnection();
                // Throw error to indicate failure
                throw new Exception($seatToReserve,1000);

            } else {

                // If no rows returned, then reserve seat (i.e., add userID to specified seat column in a new row)
                $newSQLCon->invokeTransaction(insertRow($tableName,$seatToReserve,$userID));
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
                echo("<p style=\"color: red;\">" . $exception->getMessage() . " is no longer available. Please select a different seat</p>");

            } elseif ($exception->getCode() == 1001) {

                // Echo red error message telling user that the seat could not be reserved because they already reserved one
                echo("<p style=\"color: red;\">The given UserID (" . $exception->getMessage() . "), has already reserved a seat</p>");

            } elseif ($exception->getCode() == 1054) {
                // 1054 indicates unknown column name (SQL)
                echo("<p style=\"color: red;\">Invalid Seat. Please select a valid seat and try again</p>");

            } elseif ($exception->getCode() == 1064) {

                // 1064 is an SQL syntax error, however in this case, it means no more seats are available
                echo("<p style=\"color: red;\">Seats are all gone! Sorry!</p>");

            } else {

                // If an unknown error occurs
                echo("<p style=\"color: red;\">(" . $exception->getCode() . ") an unidentified error occured. Please try again. If the issue persists, please contact an administrator</p>");

            }

        }

    }
