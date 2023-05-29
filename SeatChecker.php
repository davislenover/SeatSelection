<?php

    include "SQLComs.php";

    function getAvailableSeats() {

        $newSQLCon = new SQLComs("192.168.2.56:3306","seatUser","HpqWeHafLfiwWBkw9UmWReP^Vu");
        $returnSeats = array();
        array_push($returnSeats,"Test1","Test2");
        return $returnSeats;

    }
