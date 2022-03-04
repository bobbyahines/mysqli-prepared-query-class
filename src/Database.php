<?php declare(strict_types=1);


use Error;
use mysqli;

class Database
{

    /**
     * @param string $dbName
     * @return \mysqli
     */
    protected function connection(string $dbName): mysqli
    {

        $host = $_ENV['CLIENT_HOST'];
        $port = $_ENV['CLIENT_PORT'];
        $user = $_ENV['CLIENT_USER'];
        $pass = $_ENV['CLIENT_PASS'];
        $name = $_ENV['CLIENT_NAME'];

        try {
            $connect = \mysqli_connect($host, $user, $pass, $name, $port);
            if ($connect->connect_error) {
                throw new Error($connect->connect_errno . ': ' . $connect->connect_error);
            }
        } catch (Error $error) {
            echo $error->getMessage();
            die();
        }

        return $connect;
    }

    /**
     * @param string $sql
     * @param string $types
     * @param array $params
     * @param string $db
     * @return array|null
     */
    public function preparedQuery(string $sql, string $types, array $params, string $db = 'client'): ?array
    {
        $connection = $this->connection($db);

        try {
            $preparedQuery = $connection->prepare($sql);
            if (!$preparedQuery) {
                throw new Error($connection->error);
            }
        } catch (Error $e) {
            echo $e->getMessage();die();
        }

        $preparedQuery->bind_param($types, ...$params);
        $preparedQuery->execute();

        $result = $preparedQuery->get_result();

        $preparedQuery->close();
        $connection->close();

        $numRows =  mysqli_num_rows($result);
        if ($numRows > 1) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return $result->fetch_assoc();
    }
}
