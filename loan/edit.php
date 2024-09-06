<?php

include '../includes/db.php';

if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = $conn->query("SELECT loans.idLoan, loans.idRecord, records.title, loans.name, loans.email, loans.date, loans.returnDate, loans.status FROM loans JOIN records ON loans.idRecord = records.idRecord WHERE loans.idLoan = $id");
    if ($query && $query->num_rows > 0) {
        $loan = $query->fetch_assoc();
    }
}


$records = [];
$query = $conn->query("SELECT idRecord, title FROM records WHERE idRecord NOT IN (SELECT idRecord FROM loans WHERE status = 'Open' AND idRecord != {$loan['idRecord']})");
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $records[] = $row;
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit a Loan</title>
</head>
<body>
    
    <h2>Edit Loan</h2>

    <form action="" method="post">
        <label for="record">Record:</label>
        <select id="record" name="record" required>
            <option value="">Select a record</option>
            <?php foreach ($records as $record): ?>
                <option value="<?php echo $record['idRecord']; ?>">
                    <?php echo htmlspecialchars($record['title']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="hidden" name="id" value="<?php echo $loan['idLoan']; ?>">
        
        <label for="name">Name</label>
        <input type="text" name="name" id="name" value="<?php echo $loan['name']; ?>"><br><br>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" value="<?php echo $loan['email']; ?>"><br><br>

        <label for="date">Loaned on:</label>
        <input type="date" id="date" name="date" value="<?php echo $loan['date']; ?>" required><br><br>

        <label for="returnDate">Returned on:</label>
        <input type="date" id="returnDate" name="returnDate" value="<?php echo $loan['returnDate']; ?>"><br><br>

        <button type="submit">Update</button>
    </form>

    <a href="read.php">Back</a>

</body>
</html>