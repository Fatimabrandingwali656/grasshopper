<?php
$host = 'localhost';
$dbname = 'dbbm3grr61j8nb';
$user = 'uygl9yptwxjbv';
$password = 'ts7g7slncxmt';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
