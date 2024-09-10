<?php
include '../includes/db.php';

$statusMessage = '';

// create artist
if (isset($_POST['name'])) {
    $name = $_POST['name'];

    // Check if artist already exists
    $checkQuery = $conn->prepare("SELECT * FROM artists WHERE name = ?");
    $checkQuery->bind_param("s", $name);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        $statusMessage = "Artist already exists";
    } else {
        $insertQuery = $conn->prepare("INSERT INTO artists (name) VALUES (?)");
        $insertQuery->bind_param("s", $name);

        if ($insertQuery->execute()) {
            $statusMessage = "New artist created successfully";
            header("Location: ../index.php");
        } else {
            $statusMessage = "Error: " . $insertQuery->error;
        }

        $insertQuery->close();
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
        <input type="text" id="name" name="name" required><br><br>

        <input type="submit" value="Submit">
    </form>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <a href="../index.php">Back</a>
</body>
</html>