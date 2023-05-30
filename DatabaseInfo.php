<?php
// Contains information for database which other php files reference
// Note that these are TEMPORARY FIELDS AND ARE NOT IN USE IN PRODUCTION
$serverName = "192.168.2.56:3306";
$defaultUser = "seatUser";
// Used in case a specific service requires all users to have a given prefix
$userPrefix = "";
$password = "HpqWeHafLfiwWBkw9UmWReP^Vu";
$database = "seatBase";
$tableName = "seatData";
// If false, upon user submission, userID will be checked if it already exists within database
$allowMultipleResponses = false;
// Time is Hours:Minutes:Seconds (24h time)
$disableResponseUntil = "22:55:00";
// Disable reservations regardless of time setting (false for disable)
$acceptingReservations = true;
?>
