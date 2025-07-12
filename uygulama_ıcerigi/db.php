<?php
$host = 'localhost';
$user = 'root';
$pass = ''; 
$dbname = 'tilko_db';

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
?>
