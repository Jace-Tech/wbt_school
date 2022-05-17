<?php


class Student {
    private $connection;

    public function __construct($connection) {
        $this->connection = $connection;
    }

}