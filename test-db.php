<?php
echo "PHP is working!<br>";

$host = '127.0.0.1';
$dbname = 'tendapb8_apitendapoa';
$username = 'tendapb8_apitendapoa';
$password = 'ESNyarobi@1234';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    echo "Database connection successful!<br>";
    echo "Connected to database: " . $dbname;
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>


