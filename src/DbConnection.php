<?php

class DbConnection {
    /**
     * @var PDO $conn
     */
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public static function createConnection($dbConfig, $pdoOptions = []) {
        $host = $dbConfig['host'];
        $port = $dbConfig['port'];
        $database = $dbConfig['database'];

        $dsn = "mysql:dbname=$database;host=$host;port=$port";

        $conn = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], array_merge([
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ], $pdoOptions));
        return new self($conn);
    }

    public function exec($query, $params = [], $fetchOptions = PDO::FETCH_BOTH) {
        $sth = $this->conn->prepare($query);
        $sth->execute($params);
        return $sth->fetchAll($fetchOptions);
    }
}