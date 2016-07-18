<?php

function db_connect() {

    $dsn = 'mysql:dbname=todolist;host=localhost;charset=utf8';
    $user = 'root';
    $password = '';

    $dbh = new PDO($dsn, $user, $password);

    $dbh->query('SET NAMES utf8');
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

    return $dbh;

}

function postgresql_db_connect() {


// $conn_string = "host=localhost port=5432 dbname=todolist user=postgres password=postgresql";
$dbh = pg_connect("host=localhost port=5432 dbname=todolist user=postgres password=postgresql");

//$dbh = "pgsql:dbname=todolist host=localhost user=postgres password=postgresql options='--client_encoding=UTF8'";


//$dbconn5 = pg_connect("host=localhost options='--client_encoding=UTF8'");
// "localhost" のデータベースに接続する際に、エンコーディングを UTF-8 に指定

return $dbh;

}