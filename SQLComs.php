<?php

include "SQLDefaultQueries.php";

class SQLComs
{
    private $serverName;
    private $userName;
    private $passWord;
    private $dataBase;
    private $tableName;
    private $conn;

    private $columnNames;

    public function __construct($servername, $username, $password, $database, $tablename)
    {
        $this->serverName = $servername;
        $this->userName = $username;
        $this->passWord = $password;
        $this->dataBase = $database;
        $this->tableName = $tablename;

        // Create SQL connection
        $this->conn = new mysqli($this->serverName, $this->userName, $this->passWord, $this->dataBase);

        // No $ on subsequent names, think of this as "this.conn.connect_error"
        if ($this->conn->connect_error) {
            throw new mysqli_sql_exception("Connection to database failed");
        }

        // Get Table Column Names (setup)
        $this->getColumns();

        echo "Connection Successful";
    }

    // Function to get column names within the table
    private function getColumns() {

        // Initialize names array
        $this->columnNames = array();

        // Send Query to table
        // Note getColumnNames contains the query which will order the table names by ordinal position
        $result = $this->sendQuery(getColumnNames($this->tableName));

        // Loop through all rows and add each string to columnNames array
        $index = 0;
        // This is similar to an iterator
        while ($row = $result->fetch_assoc()) {
            $this->columnNames[$index++] = $row["COLUMN_NAME"];
        }

        // Close the query afterward
        $result->close();

    }


    private function sendQuery($query) {
        // Get the connection and execute the query
        return mysqli_query($this->conn,$query);
    }

}