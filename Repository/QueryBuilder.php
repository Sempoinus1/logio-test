<?php

require($_SERVER['DOCUMENT_ROOT'] . '/config/db.php');

class QueryBuilder
{
    private $conn;

    /**
     * QueryBuilder constructor.
     * @param $conn
     */
    public function __construct($conn)
    {
        $this->conn = $conn;
    }

    public function createConn()
    {
        $this->conn = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_DATABASE, DB_USER, DB_PASS);
    }

}