<?php
include '../includes/db.php';

if (isset($_GET['id'])) {
    $artistId = $_GET['id'];
    
    $query = $conn->prepare("SELECT * FROM artists WHERE idArtist = ?");
    print_r($conn->error);
    $query->bind_param("i", $artistId);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $artist = $result->fetch_assoc();
    } else {
        die("Artist not found.");
    }
} else {
    die("ID not provided.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $query = $conn->prepare("UPDATE artists SET name = ? WHERE idArtist = ?");
    $query->bind_param("si", $name, $artistId);

    if ($query->execute()) {
        header("Location: ../index.php");
    } else {
        echo "Error: " . $query->error;
    }

    $query->close();
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
</body>
</html>