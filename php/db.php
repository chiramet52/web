<?php
$host = 'localhost';
$user = 'root';     
$pass = 'd6ixJD(QZ_Dor41P';       
$dbname = 'bottlebank';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}
?>
