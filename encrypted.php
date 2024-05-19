<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

$servername = "emrin_e_serverit_tuaj";
$username = "username";
$password = "password";
$dbname = "emri_i_databazes_tuaj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Lidhja deshtoi: " . $conn->connect_error);
}

if (isset($_GET['file'])) {
    $filename = $_GET['file'];

    $stmt = $conn->prepare("SELECT encrypted_file FROM uploaded_files WHERE filename = ?");
    $stmt->bind_param("s", $filename);
    $stmt->execute();
    $stmt->bind_result($encryptedContent);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
} else {
    echo "Asnje file nuk eshte specifikuar!";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Encrypted File</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
            word-wrap: break-word;
        }
        h1 {
            margin-bottom: 20px;
        }
        pre {
            white-space: pre-wrap;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Permbajtja e Enkriptuar e <?php echo htmlspecialchars($filename); ?></h1>
        <pre><?php echo htmlspecialchars($encryptedContent); ?></pre>
    </div>
</body>
</html>
