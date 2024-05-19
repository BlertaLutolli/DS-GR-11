<?php
require 'db.php';
session_start();

$userId = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$userId) {
    header('Location: home.php');
    exit;
}

$stmt = $pdo->prepare('SELECT authorized, private_key FROM users WHERE id = ?');
$stmt->execute([$userId]);
$user = $stmt->fetch();

$isGuest = $user['authorized'] == 0;
$privateKey = $user['private_key'];

// Funksioni për enkriptimin e skedarit
function encryptFile($filePath, $key) {
    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = openssl_random_pseudo_bytes($ivLength);
    $data = file_get_contents($filePath);
    return base64_encode($iv . openssl_encrypt($data, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$isGuest) {
    $uploadDir = 'uploads/';
    $allowedFileTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

    if (isset($_FILES['file'])) {
        $fileError = $_FILES['file']['error'];
        $fileType = $_FILES['file']['type'];
        $fileSize = $_FILES['file']['size'];
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = basename($_FILES['file']['name']);

        if ($fileError === UPLOAD_ERR_OK) {
            if (!in_array($fileType, $allowedFileTypes)) {
                echo 'Lloji i file-it eshte i pavlefshem! Llojet e lejuara jane: ' . implode(', ', $allowedFileTypes);
                exit;
            }

            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            $destination = $uploadDir . $fileName;

            if (move_uploaded_file($fileTmpPath, $destination)) {
                $encryptedContent = encryptFile($destination, $privateKey);

                $decryptionResult = decryptFile($encryptedContent, $privateKey);

                if ($decryptionResult['success']) {
                    echo 'File-i u ngarkua me sukses !';
                } else {
                    echo 'Decryption failed.';
                }
            } else {
                echo 'Ka ndodhur një gabim gjatë lëvizjes së skedarit të ngarkuar.';
            }
        } else {
            echo 'Nuk është ngarkuar asnjë skedar ose ka ndodhur një gabim gjatë ngarkimit. Kod gabimi: ' . $fileError;
        }
    } else {
        echo 'Nuk është ngarkuar asnjë skedar ose ka ndodhur një gabim gjatë ngarkimit.';
    }
}
// Funksioni për dekriptimin e skedarit
function decryptFile($encryptedContent, $key) {
    $data = base64_decode($encryptedContent);
    $ivLength = openssl_cipher_iv_length('aes-256-cbc');
    $iv = substr($data, 0, $ivLength);
    $encryptedData = substr($data, $ivLength);

    $decryptedData = openssl_decrypt($encryptedData, 'aes-256-cbc', $key, OPENSSL_RAW_DATA, $iv);

    if ($decryptedData === false) {
        $error = openssl_error_string();
        return ['success' => false, 'error' => $error];
    }
    return ['success' => true];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 350px;
        }
        .container h1 {
            color: #333333;
            margin-bottom: 20px;
        }
        .upload-btn-wrapper {
            position: relative;
            overflow: hidden;
            display: inline-block;
        }
        .upload-icon-btn {
            border: 2px dashed #007bff;
            color: #007bff;
            background-color: transparent;
            padding: 30px;
            font-size: 50px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            width: 220px;
            height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background-color 0.3s, color 0.3s;
        }
        .upload-icon-btn:hover {
            background-color: #007bff;
            color: #ffffff;
        }
        .upload-btn-wrapper input[type=file] {
            font-size: 100px;
            position: absolute;
            left: 0;
            top: 0;
            opacity: 0;
            cursor: pointer;
        }
        .upload-btn {
            border: none;
            color: white;
            background-color: #28a745;
            padding: 10px 20px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            border-radius: 5px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .upload-btn:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload File</h1>
        <form action="upload.php" method="post" enctype="multipart/form-data">
            <div class="upload-btn-wrapper">
                <button class="upload-icon-btn">+</button>
                <input type="file" name="file" required>
            </div>
            <br>
            <button class="upload-btn" type="submit">Upload</button>
        </form>
    </div>
</body>
</html>
