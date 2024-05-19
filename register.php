<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Funksioni për të bërë hash password-in
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Gjenerohet një celës privat random
    $privateKey = bin2hex(random_bytes(16)); 

   
    $stmt = $pdo->prepare('INSERT INTO users (username, password, private_key, authorized) VALUES (?, ?, ?, 1)');
    $stmt->execute([$username, $hashedPassword, $privateKey]);

    echo 'Perdoruesi u regjistrua me sukses!';
}
?>