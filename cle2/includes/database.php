<?php
$host   = "localhost";
$username = "root";
$password = "";
$database = "cle2_reserverings";
$port = "3306";

$db = mysqli_connect($host, $username, $password, $database, $port)
or die("Error: " . mysqli_connect_error());
