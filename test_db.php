<?php
$passwords = ['', 'root', '123456', 'password', 'mysql', 'admin'];
$created = false;

foreach ($passwords as $pw) {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306', 'root', $pw, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        $pdo->exec('CREATE DATABASE IF NOT EXISTS marketplace CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        echo "SUCCESS! Connected with password: '" . ($pw ?: '(empty)') . "'\n";
        echo "Database 'marketplace' created!\n";
        $created = true;
        break;
    } catch (Exception $e) {
        echo "Failed with password '$pw': " . $e->getMessage() . "\n";
    }
}

if (!$created) {
    echo "\nCould not connect. Please provide your MySQL root password.\n";
}
