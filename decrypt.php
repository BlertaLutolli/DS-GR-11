<?php
require 'db.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['user_id'])) {
    echo 'Duhet te jeni te identifikuar per te shkarkuar files!';
    exit;
}

function decryptFile($filePath, $key) {
    $data = file_get_contents($filePath);
    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $ivLength);
    $encryptedData = substr($data, $ivLength);
    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, 0, $iv);
    return $decryptedData;
}

$uploadDir = 'uploads/';

if (isset($_GET['file'])) {
    $fileId = $_GET['file'];
    
    $stmt = $pdo->prepare('SELECT * FROM files WHERE id = ? AND user_id = ?');
    $stmt->execute([$fileId, $_SESSION['user_id']]);
    

    $file = $stmt->fetch();

    $filePath = $uploadDir . $file['filename'];
    if (!file_exists($filePath)) {
        echo 'Skedari nuk ekziston.';
        exit;
    }

    $stmt = $pdo->prepare('SELECT private_key FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    $privateKey = $user['private_key'];


    $decryptedData = decryptFile($filePath, $privateKey);
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file['filename']) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . strlen($decryptedData));
    echo $decryptedData;
    exit;
} else {
    echo ' Asnje file nuk eshte specifikuar!';
}
?>
