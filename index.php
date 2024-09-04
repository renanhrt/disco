<?php
include 'includes/db.php';

$records = [];
$query = $conn->query("SELECT idRecord, title, year, cover, idArtist FROM records ORDER BY year DESC");

if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $records[] = $row;
    }
} 


$query = $conn->query("
    SELECT artists.idArtist, artists.name, COUNT(records.idRecord) record_count
    FROM artists
    LEFT JOIN records ON artists.idArtist = records.idArtist
    GROUP BY artists.idArtist
    ORDER BY record_count DESC
");

$artists = [];
if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $artists[] = $row;
    }
}


// delete record
if (isset($_GET['delete'])) {
    if ($_GET['type'] == 'artist') {
        $id = $conn->real_escape_string($_GET['delete']);
        $conn->query("DELETE FROM artists WHERE idArtist = $id");
        $conn->query("DELETE FROM records WHERE idArtist = $id");
        header('Location: index.php');
    } else {
        $id = $conn->real_escape_string($_GET['delete']);
        $conn->query("DELETE FROM records WHERE idRecord = $id");
        header('Location: index.php');
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
    <h2>Records</h2>

    <h2><a href="loan/loans.php">Loans</a></h2>

    <a href="record/create.php">Add Record</a>
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
                    <td><a href="record/edit.php?id=<?php echo $record['idRecord']; ?>">Edit</a></td>
                    <td><a href="index.php?type=record&delete=<?php echo $record['idRecord']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>


    <h2>Artists</h2>
    <a href="artist/create.php">Add Artist</a>
    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Qty. of Records</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($artists as $artist) : ?>
                <tr>
                    <td><?php echo $artist['name']; ?></td>
                    <td><?php echo $artist['record_count']; ?></td>
                    <td><a href="artist/edit.php?id=<?php echo $artist['idArtist']; ?>">Edit</a></td>
                    <td><a href="index.php?type=artist&delete=<?php echo $artist['idArtist']; ?>">Delete</a></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
</body>
</html>