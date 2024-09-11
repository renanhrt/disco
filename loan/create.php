<?php
include '../includes/db.php';

// get records
$records = [];
$query = $conn->query("SELECT idRecord, title FROM records WHERE idRecord NOT IN (SELECT idRecord FROM loans WHERE status = 'Open');");
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $records[] = $row;
    }
}

// create loan
if (isset($_POST['record'])) {
    $recordId = $_POST['record'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];

    $query = $conn->prepare("INSERT INTO loans (idRecord, name, email, date) VALUES (?, ?, ?, ?)");
    $query->bind_param("isss", $recordId, $name, $email, $date);

    if ($query->execute()) {
        header('Location: read.php');
    } else {
        die("Error: " . $query->error);
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
    <title>Create a loan</title>
</head>
<body>
    <h1>Create a Loan</h1>
    <form action="" method="post">
        
        <label for="record">Record:</label><br>
        <select class="selection" id="record" name="record" required>
            <option value="">Select a record</option>
            <?php foreach ($records as $record): ?>
                <option value="<?php echo $record['idRecord']; ?>">
                    <?php echo htmlspecialchars($record['title']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="Name">Name:</label><br>
        <input type="text" class="textinput" id="name" name="name" required><br><br>
 
        <label for="email">Email:</label><br>
        <input type="email" class="textinput" id="email" name="email" required><br><br>
        
        <label for="date">Loaned on:</label><br>
        <input type="date" class="selection" id="date" name="date" required><br><br>

        <input type="submit" class="submit" value="Submit">
    </form>

    <a href="read.php" class="back">Back</a>
</body>
</html>