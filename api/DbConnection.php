<?php


class DbConnection
{
    private $DSN = 'mysql:dbname=meal;host=sql-meal.cndjbcddhtpq.us-east-2.rds.amazonaws.com';
    private $DUSER = 'admin';
    private $DUSER_PASS = 'yBbh8ZvBh6FGCAsgkbRR';
    private $PDO;

    /**
     * DbConnection constructor.
     * @param $PDO
     */
    public function __construct()
    {
        try {
            $this->PDO = new PDO($this->DSN, $this->DUSER, $this->DUSER_PASS);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function prepare($string, $arr){
        return $this->PDO->prepare($string, $arr);
    }


}