<?php

    include "SQLComs.php";

    function getAvailableSeats() {

        // Get all seat names
        $newSQLCon = new SQLComs("192.168.2.56:3306","seatUser","HpqWeHafLfiwWBkw9UmWReP^Vu","seatBase","seatData");
        // Get all non-inserted rows thus far
        return $newSQLCon->getNullColumns();

    }
