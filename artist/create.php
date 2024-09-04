<?php
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];

    $query = $conn->prepare("INSERT INTO artists (name) VALUES (?)");
    $query->bind_param("s", $name);

    if ($query->execute()) {
        echo "New artist created successfully";
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
        <input type="text" id="name" name="name" required><br><br>

        <input type="submit" value="Submit">
    </form>
</body>
</html>