<?php

    function getColumnNames($tableName) {

        return "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = " + "'" + $tableName +"'" + " ORDER BY ORDINAL_POSITION";

    }
?>
