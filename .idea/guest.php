<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo 'Emri i perdoruesit ekziston. Zgjidhni nje emer tjeter perdoruesi!';
    } else {
        $stmt = $pdo->prepare('INSERT INTO users (username, authorized) VALUES (?, 0)');
        if ($stmt->execute([$username])) {
            echo 'Guest user u regjistrua me sukses!';
        } else {
            echo 'Deshtoi regjistrimi i guest user!';
        }
    }
}
?>
