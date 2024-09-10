<?php
include '../includes/db.php';

$statusMessage = '';

// get selected artist
if (isset($_GET['id'])) {
    $artistId = $_GET['id'];
    
    $query = $conn->prepare("SELECT * FROM artists WHERE idArtist = ?");
    print_r($conn->error);
    $query->bind_param("i", $artistId);
    $query->execute();
    $result = $query->get_result();

    $artist = $result->fetch_assoc();
}

// update artist
if (isset($_POST['name'])) {
    $name = $_POST['name'];

    $checkQuery = $conn->prepare("SELECT idArtist FROM artists WHERE name = ?");
    $checkQuery->bind_param("s", $name);
    $checkQuery->execute();
    $checkResult = $checkQuery->get_result();

    if ($checkResult->num_rows > 0) {
        $statusMessage = "Artist already exists";
    } else {
        $query = $conn->prepare("UPDATE artists SET name = ? WHERE idArtist = ?");
        $query->bind_param("si", $name, $artistId);

        if ($query->execute()) {
            $statusMessage = "Artist updated successfully";
            header("Location: ../index.php");
        } else {
            $statusMessage = "Error: " . $query->error;
        }

        $query->close();
    }

    $checkQuery->close();
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
    <form method="POST" action="">
        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo $artist['name'] ?>" required><br><br>

        <input type="submit" value="Submit">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <a href="../index.php">Back</a>
</body>
</html>