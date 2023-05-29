<?php

    // File consists of strings as pre-made SQL queries

    function getColumnNames($tableName) {
        return "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = " . "'" . $tableName ."'" . " ORDER BY ORDINAL_POSITION";
    }

    function insertRow($tableName, $seatName, $UserToInsert) {
        return "INSERT INTO " . $tableName . " (" . $seatName . ") VALUES ('" . $UserToInsert . "')";
    }

    function selectAllRows($tableName) {
        return "SELECT * FROM " . $tableName;
    }

    function getNotNullRowOfColumn($tableName, $seatName) {
        return selectAllRows($tableName) . " WHERE " . $seatName . " IS NOT NULL";
    }

?>
