<?php
$host = 'localhost';
$db   = 'rsoa_rsoa276_17';
$user = 'rsoa_rsoa276_17';
$pass = '123456';
$charset = 'utf8mb4';
 
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
 
try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     // For development/debugging purposes. In production, hide the message.
     // die("Connection failed: " . $e->getMessage());
 
     // Fallback for environment without actual DB setup - we can still work with sessions
}
?>
