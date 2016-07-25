<?php

//postgresqlに接続
function postgresql_connect() {

	$conn = pg_connect("host=localhost user=postgres");
	return $conn;

}

//postgresqlに接続
function postgresql_db_connect($db_name) {
try{
	$conn = pg_connect("host=localhost port=5432 dbname={$db_name} user=postgres password=postgresql");
	return $conn;

}catch(Exception $e){
	echo $e;
}
}