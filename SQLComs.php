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

    public function getNullColumns() {

        // Get all inserted rows in table
        $result = mysqli_query($this->conn,selectAllRows($this->tableName));

        // Setup array that tracks if a given column in a row is not null
        $checkNullArray = array();
        foreach ($this->columnNames as $name) {
            // This is a key-value array (uses the name of the column as the index)
            $checkNullArray[$name] = 0;
        }

        // Get all rows
        while ($row = $result->fetch_assoc()) {
            // Look through the column names
            foreach ($this->columnNames as $name) {
                // Check if in the specific row, the given column is not null
                if (!is_null($row[$name])) {
                    // If it's not, increment specific index in tracker array by 1
                    $checkNullArray[$name] += 1;
                }
            }
        }

        // Now just check which columns have a value of 0 in the tracker array to return
        // If they have a value of 0, it means that the given column was null for every row and thus, is available
        $returnArray = array();
        foreach ($this->columnNames as $name) {
            if ($checkNullArray[$name] == 0) {
                $returnArray[] = $name;
            }
        }

        return $returnArray;

    }


    public function getColumnNames() {
        return $this->columnNames;
    }

}