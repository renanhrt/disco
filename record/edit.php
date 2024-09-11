<?php
include '../includes/db.php';

$statusMessage = '';
$verifier = false;

// get selected record
if (isset($_GET['id'])) {
    $recordId = $_GET['id'];
    
    $query = $conn->prepare("SELECT * FROM records WHERE idRecord = ?");
    $query->bind_param("i", $recordId);
    $query->execute();
    $result = $query->get_result();

    $record = $result->fetch_assoc();
}

// get artists
$artists = [];
$query = $conn->query("SELECT idArtist, name FROM artists");
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $artists[] = $row;
    }
}

// update record
if (isset($_POST['title'])) {
    $title = $_POST['title'];
    $year = $_POST['year'];
    $artistId = $_POST['artist'];
    $cover = $_FILES['cover'];

    // upload cover image
    if (isset($cover) && $cover['size'] > 0) {
        if ($cover['error'] === UPLOAD_ERR_OK) {
            $images = 'images/';
            $uploadFile = $images . basename($cover['name']);

            $extension = strtolower(pathinfo($cover['name'], PATHINFO_EXTENSION));
            if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
                $statusMessage = "Only JPG, JPEG, PNG files are allowed.";
            } else {
                if (move_uploaded_file($cover['tmp_name'], "../" . $uploadFile)) {
                    $verifier = true;
                    unlink("../".$record['cover']);
                } else {
                    $statusMessage = "Possible file upload attack!";
                }
            }
        } else {
            $statusMessage = "File upload error: " . $cover['error'];
        }
    } else {
        $uploadFile = $record['cover'];
        $verifier = true;
    }

    $query = $conn->prepare("UPDATE records SET title = ?, year = ?, idArtist = ?, cover = ? WHERE idRecord = ?");
    $query->bind_param("sissi", $title, $year, $artistId, $uploadFile, $recordId);

        if ($query->execute()) {
            $statusMessage = "Record updated successfully";
            header("Location: ../index.php");
        } else {
            $statusMessage = "Error: " . $query->error;
        }

    $query->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>Edit Record</title>
</head>
<body>
    <h2>Edit Record</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" class="textinput" value="<?php echo htmlspecialchars($record['title']); ?>" required><br><br>

        <label for="year">Year:</label><br>
        <input type="number" id="year" name="year" class="textinput" value="<?php echo htmlspecialchars($record['year']); ?>" required><br><br>

        <label for="artist">Artist:</label><br>
        <select id="artist" class="selection" name="artist" required>
            <option value="">Select an artist</option>
            <?php foreach ($artists as $artist): ?>
                <option value="<?php echo $artist['idArtist']; ?>" <?php echo $artist['idArtist'] == $record['idArtist'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($artist['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="cover">Cover:</label><br>
        <input class="submit" type="file" id="cover" name="cover"><br><br>
        <img src="<?php echo "../".$record['cover']; ?>" alt="Current Cover" style="max-width: 150px;"><br><br>

        <input class="submit" type="submit" value="Update">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <a href="../index.php" class="back">Back</a>
</body>
</html>