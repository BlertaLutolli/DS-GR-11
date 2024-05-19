<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: home.php");
    exit();
}

$isGuest = isset($_SESSION['guest']) && $_SESSION['guest'];

$servername = "emrin_e_serverit_tuaj";
$username = "username";
$password = "password";
$dbname = "emri_i_databazes_tuaj";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT filename FROM uploaded_files";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            text-align: center;
            max-width: 600px;
            margin: auto;
        }
        h1 {
            margin-bottom: 20px;
        }
        .files {
            text-align: left;
        }
        .unauthorized-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Mirë se vini, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
        <?php if ($isGuest): ?>
            <p class="unauthorized-message">Nuk jeni te autorizuar te ngarkoni files. Regjistrohuni si perdorues per te fituar akses te plote!</p>
        <?php endif; ?>
        <div class="files">
            <h2>Encrypted Files</h2>
            <ul>
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<li><a href="encrypted.php?file=' . urlencode($row['filename']) . '">' . htmlspecialchars($row['filename']) . '</a></li>';
                    }
                } else {
                    echo "<li>No encrypted files found.</li>";
                }
                $conn->close();
                ?>
            </ul>
        </div>
        <?php if (!$isGuest): ?>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <h2>Upload File</h2>
                <input type="file" name="fileToUpload" id="fileToUpload">
                <button type="submit" name="submit">Upload File</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>
