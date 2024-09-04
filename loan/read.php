<?php 

include '../includes/db.php';

$loans = [];
$query = $conn->query("SELECT loans.idLoan, records.title, loans.name, loans.email, loans.date, loans.returnDate, loans.status FROM loans JOIN records ON loans.idRecord = records.idRecord ORDER BY loans.status ASC, loans.date DESC");
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $loans[] = $row;
    }
}


if (isset($_GET['return'])) {
    $id = $conn->real_escape_string($_GET['return']);
    $conn->query("UPDATE loans SET status = 'Returned', returnDate = NOW() WHERE idLoan = $id");
    header('Location: read.php');
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loans</title>
</head>
<body>
    
    <h2>Loans</h2>

    <a href="create.php">Add Loan</a>

    <table>
        <thead>
            <tr>
                <th>Record</th>
                <th>Loaned To</th>
                <th>Email</th>
                <th>Status</th>
                <th>Loaned On</th>
                <th>Returned On</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan) : ?>
                <tr>
                    <td><?php echo $loan['title']; ?></td>
                    <td><?php echo $loan['name']; ?></td>
                    <td><?php echo $loan['email']; ?></td>
                    <td><?php echo $loan['status']; ?></td>
                    <td><?php echo $loan['date']; ?></td>
                    <td><?php echo $loan['returnDate']; ?></td>
                    <td><a href="read.php?return=<?php echo $loan['idLoan']?>">Return</a></td>
                    <td><a href="edit.php?id=<?php echo $loan['idLoan']; ?>">Edit</a></td>
                    <td><a href="index.php?type=loan&delete=<?php echo $loan['idLoan']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>
</html>