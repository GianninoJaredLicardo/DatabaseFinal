<?php require_once "database/db.php"; ?>
<link rel="stylesheet" href="style.css">
<button><a href="index.php">Home</a></button> <br>

<h2>Teams</h2>

<?php
$result = $db->query("SELECT * FROM teams");

while ($row = $result->fetch_assoc()) {
    echo $row["team_name"] . "<br>";
}