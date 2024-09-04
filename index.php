<?php
include 'includes/db.php';

$records = [];
$query = $conn->query("SELECT idRecord, title, year, cover, idArtist FROM records");

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
    <title>Disco</title>
</head>
<body>
    <div id="rectable">
        <h2>Records</h2>
        <a href="record/create.php">Add Record</a>
        <a href="artist/create.php">Add Artist</a>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Year</th>
                    <th>Artist</th>
                    <th>Cover</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($records as $record) : ?>
                    <tr>
                        <td><?php echo $record['title']; ?></td>
                        <td><?php echo $record['year']; ?></td>
                        <td><?php echo $conn->query("SELECT name FROM artists WHERE idArtist = {$record['idArtist']}")->fetch_assoc()['name']; ?></td>
                        <td><img src="<?php echo $record['cover']; ?>" alt="<?php echo $record['title']; ?>" width="100"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>