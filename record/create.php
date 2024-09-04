<?php
include '../includes/db.php';

$statusMessage = '';
$verifier = false;

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
            if (move_uploaded_file($cover['tmp_name'], "../" . $uploadFile)) {
                $verifier = true;
            } else {
                $statusMessage = "Possible file upload attack!";
            }
        }
    } else {
        $statusMessage = "File upload error: " . $cover['error'];
    }

    if (empty($statusMessage)) {
        
        $query = $conn->prepare("INSERT INTO records (title, year, idArtist, cover) VALUES (?, ?, ?, ?)");
        $query->bind_param("siss", $title, $year, $artistId, $uploadFile);

        if ($query->execute()) {
            $statusMessage = "New album created successfully";
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
    <title>Create Artists</title>
</head>
<body>
    <h2>Create Artist</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="title">Title:</label><br>
        <input type="text" id="title" name="title" required><br><br>

        <label for="year">Year:</label><br>
        <input type="number" id="year" name="year" required><br><br>

        <label for="artist">Artist:</label><br>
        <select id="artist" name="artist" required>
            <option value="">Select an artist</option>
            <?php foreach ($artists as $artist): ?>
                <option value="<?php echo $artist['idArtist']; ?>">
                    <?php echo htmlspecialchars($artist['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="cover">Cover:</label><br>
        <input type="file" id="cover" name="cover" required><br><br>

        <input type="submit" value="Submit">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>
</body>
</html>
