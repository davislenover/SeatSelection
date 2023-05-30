<?php

    include "SQLComs.php";
    include "DatabaseInfo.php";
    include "MessageBase.php";

    // Function to get open seats from SQL database
    function getAvailableSeats() {

        global $serverName, $password, $tableName, $database, $defaultUser;

        try {

            // Get all seat names
            $newSQLCon = new SQLComs($serverName,$defaultUser,$password,$database,$tableName);
            // Get all non-inserted rows thus far
            $returnNames = $newSQLCon->getNullColumns();
            // Close connection
            $newSQLCon->closeConnection();

            return $returnNames;

        } catch (Exception $exception) {

            $returnNames = array();
            $returnNames[] = "Unable to find seats!";
            return $returnNames;

        }

    }

    // Function to attempt to reserve a seat in SQL database
    function reserveSeat($userID, $seatToReserve) {

        global $serverName, $password, $tableName, $database, $allowMultipleResponses, $userPrefix;

        try {
            // Check Input (will throw error if fails and give connection if passes)
            // Attempt to get connection
            $newSQLCon = checkInput($serverName,$userPrefix,$userID,$password,$database,$tableName);

            // Prepare transaction (a transaction operates on a temporary database)
            $newSQLCon->beginTransaction();

            // Before performing main queries in transaction, check if multiple seat selection from the same user is not allowed
            if (!$allowMultipleResponses) {
                // If so, check if the userID already reserved a seat
                if ($newSQLCon->doesIDExistInRow($userID)) {
                    // If so commit transaction and close connection, throw corresponding error
                    $newSQLCon->commitTransaction();
                    $newSQLCon->closeConnection();
                    throw new DuplicateIDReservationException($userID);
                }
            }

            // Check if any rows returned with data for given seat (this means that the given seat has already been reserved)
            if ($newSQLCon->doRowsExistForColumn($seatToReserve)) {
                // Commit transaction (as it was only reading, not writing) and close connection
                $newSQLCon->commitTransaction();
                $newSQLCon->closeConnection();
                // Throw error to indicate failure
                throw new FailedReservationException($seatToReserve);

            } else {

                // If no rows returned, then reserve seat (i.e., add userID to specified seat column in a new row)
                $newSQLCon->invokeTransaction(insertRow($tableName,$seatToReserve,$userID));
                // Commit changes and close connection
                $newSQLCon->commitTransaction();
                $newSQLCon->closeConnection();

            }

            // Display successful reservation
            echo("<p style=\"color: green;\">" . $seatToReserve  . " has been reserved! Time: " . date("H:i:s",time()) . "-" . time() . "</p>");

        // Catch exception if connection fails
        } catch (GeneralReservationException $exception) {
            echo($exception->getMessage());

        } catch (UnknownException $unknownException) {
            echo($unknownException->getMessage());

            // General Exceptions
        } catch (Exception $exception) {
        if ($exception->getCode() == 1054) {
            // 1054 indicates unknown column name (SQL)
            echo((new InvalidSeatException())->getMessage());
        }
            // If an unknown error occurs
            echo((new UnknownException($exception->getCode()))->getMessage());
        }

    }


    // Function to check input
    // If input passes, a new sql connection object will be returned, if not, an error is thrown
    function checkInput($serverName, $userPrefix, $userID, $password, $database, $tableName) {

        if ($userID == "") {
            throw new NoIDException();
        }

        try {
            return new SQLComs($serverName,$userPrefix.$userID,$password,$database,$tableName);
        } catch (Exception $exception) {
            // Auth error (username is incorrect)
            if ($exception->getCode() == 1045) {
                // Echo red error message telling user the UserID is incorrect
                throw new InvalidIDException($userID);
            } else {
                // If an unknown error occurs
                throw new UnknownException($exception->getCode());
            }
        }
    }
