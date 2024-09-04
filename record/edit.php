<?php
include '../includes/db.php';

$statusMessage = '';
$verifier = false;


if (isset($_GET['id'])) {
    $recordId = $_GET['id'];
    
    $query = $conn->prepare("SELECT * FROM records WHERE idRecord = ?");
    print_r($conn->error);
    $query->bind_param("i", $recordId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        die("Record not found.");
    }
} else {
    die("ID not provided.");
}


$artists = [];
$query = $conn->query("SELECT idArtist, name FROM artists");

if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $artists[] = $row;
    }
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $artistId = $_POST['artist'];
    $cover = $_FILES['cover'];

    if ($cover['error'] === UPLOAD_ERR_OK) {
        $images = 'images/';
        $uploadFile = $images . basename($cover['name']);

        $extension = strtolower(pathinfo($cover['name'], PATHINFO_EXTENSION));
        if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
            $statusMessage = "Only JPG, JPEG, PNG files are allowed.";
        } else {
            if (move_uploaded_file($cover['tmp_name'], $uploadFile)) {
                $verifier = true;
            } else {
                $statusMessage = "Possible file upload attack!";
            }
        }
    } else {
        // If no new cover is uploaded, keep the old cover
        $uploadFile = $record['cover'];
    }

    if (empty($statusMessage)) {
        $query = $conn->prepare("UPDATE records SET title = ?, year = ?, idArtist = ?, cover = ? WHERE idRecord = ?");
        $query->bind_param("sissi", $title, $year, $artistId, $uploadFile, $recordId);

        if ($query->execute()) {
            $statusMessage = "Record updated successfully";
            // back to index.php

            
            header("Location: ../index.php");
        } else {
            $statusMessage = "Error: " . $query->error;
        }

        $query->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Record</title>
</head>
<body>
    <h2>Edit Record</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($record['title']); ?>" required><br><br>

        <label for="year">Year:</label><br>
        <input type="number" id="year" name="year" value="<?php echo htmlspecialchars($record['year']); ?>" required><br><br>

        <label for="artist">Artist:</label><br>
        <select id="artist" name="artist" required>
            <option value="">Select an artist</option>
            <?php foreach ($artists as $artist): ?>
                <option value="<?php echo $artist['idArtist']; ?>" <?php echo $artist['idArtist'] == $record['idArtist'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($artist['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="cover">Cover:</label><br>
        <input type="file" id="cover" name="cover"><br><br>
        <img src="<?php echo "../".$record['cover']; ?>" alt="Current Cover" style="max-width: 150px;"><br><br>

        <input type="submit" value="Update">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>
</body>
</html>