<?php

class SQLComs
{
    private $serverName;
    private $userName;
    private $passWord;
    private $conn;

    public function __construct($servername, $username, $password)
    {
        $this->serverName = $servername;
        $this->userName = $username;
        $this->passWord = $password;

        // Create SQL connection
        $this->conn = new mysqli($this->serverName, $this->userName, $this->passWord);

        // No $ on subsequent names, think of this as "this.conn.connect_error"
        if ($this->conn->connect_error) {
            throw new mysqli_sql_exception("Connection to database failed");
        }

        echo "Connection Successful";
    }


    public function sendQuery($query) {



    }

}