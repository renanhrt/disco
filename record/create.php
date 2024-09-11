<?php
include '../includes/db.php';

$statusMessage = '';
$verifier = false;


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

    if (empty($statusMessage)) {
        if ($cover['error'] === UPLOAD_ERR_OK) {
            $images = 'images/';
            $uploadFile = $images . basename($cover['name']);
    
            $extension = strtolower(pathinfo($cover['name'], PATHINFO_EXTENSION));
            if ($extension != "jpg" && $extension != "png" && $extension != "jpeg") {
                $statusMessage = "Only JPG, JPEG, PNG files are allowed.";
            } else {
                if (move_uploaded_file($cover['tmp_name'], "../" . $uploadFile)) {
                    $verifier = true;
                } else {
                    $statusMessage = "Possible file upload attack!";
                }
            }
        } else {
            $statusMessage = "File upload error: " . $cover['error'];
        }
        
        $query = $conn->prepare("INSERT INTO records (title, year, idArtist, cover) VALUES (?, ?, ?, ?)");
        $query->bind_param("siss", $title, $year, $artistId, $uploadFile);

        if ($query->execute()) {
            $statusMessage = "New record created successfully";
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
    <link rel="stylesheet" href="../style.css">
    <title>Create Records</title>
</head>
<body>
    <h2>Create a Record</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" class="textinput" name="title" required><br><br>

        <label for="year">Year:</label><br>
        <input type="number" id="year" class="textinput" name="year" required><br><br>

        <label for="artist">Artist:</label><br>
        <select id="artist" class="selection" name="artist" required>
            <option value="">Select an artist</option>
            <?php foreach ($artists as $artist): ?>
                <option value="<?php echo $artist['idArtist']; ?>">
                    <?php echo htmlspecialchars($artist['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="cover">Cover:</label><br>
        <input type="file" class="submit" name="cover" required><br><br>

        <input type="submit" value="Submit" class="submit">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <a href="../index.php" class="back">Back</a>
</body>
</html>
