<?php

include '../includes/db.php';

$statusMessage = '';

// get loan
if (isset($_GET['id'])) {
    $id = $conn->real_escape_string($_GET['id']);
    $query = $conn->query("SELECT loans.idLoan, loans.idRecord, records.title, loans.name, loans.email, loans.date, loans.returnDate, loans.status FROM loans JOIN records ON loans.idRecord = records.idRecord WHERE loans.idLoan = $id");
    if ($query && $query->num_rows > 0) {
        $loan = $query->fetch_assoc();
    }
}

// get records
$records = [];
if ($loan['status'] == 'Open'){
    $query = $conn->prepare("SELECT idRecord, title FROM records WHERE (idRecord NOT IN (SELECT idRecord FROM loans WHERE status = 'Open')) OR (idRecord IN (SELECT idRecord FROM loans WHERE idLoan = ?))");
    $query->bind_param("i", $id);
} else {
    $query = $conn->prepare("SELECT idRecord, title FROM records");
}
$query->execute();
$result = $query->get_result();
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $records[] = $row;
    }
}

// reopen loan
if (isset($_POST['type'])) {
    if ($_POST['type'] == 'reopen') {
        $id = $conn->real_escape_string($_POST['id']);

        $query = $conn->query("SELECT idRecord FROM loans WHERE idLoan = $id");
        $record = $query->fetch_assoc();

        $query = $conn->query("SELECT idLoan FROM loans WHERE idRecord = " . $record['idRecord'] . " AND status = 'Open'");
        if ($query && $query->num_rows > 0) {
            $statusMessage = "Cannot reopen: record already loaned";
        } else {
            $query = $conn->prepare("UPDATE loans SET status = 'Open', returnDate = NULL WHERE idLoan = ?");
            $query->bind_param("i", $id);
            $query->execute();
            header('Location: read.php');
        }
    }
}

// update loan
if (isset($_POST['record'])) {
    $recordId = $_POST['record'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $date = $_POST['date'];
    $returnDate = $_POST['returnDate'];

    if ($date > $returnDate) {
        $statusMessage = "Return date must be after loan date";
        exit;
    }

    $query = $conn->prepare("UPDATE loans SET idRecord = ?, name = ?, email = ?, date = ?, returnDate = ? WHERE idLoan = ?");
    $query->bind_param("issssi", $recordId, $name, $email, $date, $returnDate, $id);

    if ($query->execute()) {
        header('Location: read.php');
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
    <title>Edit a Loan</title>
</head>
<body>
    
    <h2>Edit Loan</h2>
    
    <form action="" method="post">
        <label for="record">Record:</label>
        <select class="selection" id="record" name="record" required>
            <option value="">Select a record</option>
            <?php foreach ($records as $record): ?>
            <option value="<?php echo $record['idRecord']; ?>" <?php if ($record['idRecord'] == $loan['idRecord']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($record['title']); ?>
            </option>
            <?php endforeach; ?>
        </select><br><br>

        <input type="hidden" name="id" value="<?php echo $loan['idLoan']; ?>">
        
        <label for="name">Name</label>
        <input type="text" class="textinput" name="name" id="name" value="<?php echo $loan['name']; ?>"><br><br>

        <label for="email">Email</label>
        <input type="email" name="email" class="textinput" id="email" value="<?php echo $loan['email']; ?>"><br><br>

        <label for="date">Loaned on:</label>
        <input type="date" id="date" class="selection name="date" value="<?php echo $loan['date']; ?>" required><br><br>
        
        <?php if ($loan['status'] == 'Returned') : ?>
            <label for="returnDate">Returned on:</label>
            <input type="date" id="returnDate" class="selection name="returnDate" value="<?php echo $loan['returnDate']; ?>" required><br><br>
        <?php else: ?>
            <input type="hidden" name="returnDate" value="<?php echo $loan['returnDate']; ?>">
        <?php endif; ?>
        

        <button type="submit" class="submit">Update</button>
    </form>
    
    <!-- reopen loan button -->
    <?php if ($loan['status'] == 'Returned') : ?>
        <form action="" method="post">
            <input type="hidden" name="type" value="reopen">
            <input type="hidden" name="id" value="<?php echo $loan['idLoan']; ?>">
            <button type="submit" class="submit">Reopen Loan</button>
        </form>
    <?php endif; ?>

    <p><?php echo htmlspecialchars($statusMessage); ?></p>

    <a href="read.php" class="back">Back</a>

</body>
</html>