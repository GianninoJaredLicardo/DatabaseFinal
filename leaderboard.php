<?php require_once 'database/db.php'; ?>
<link rel="stylesheet" href="style.css">
<button><a href="index.php">Home</a></button> <br>

<h2>Leaderboard</h2>

<?php
$sql = "SELECT team_name, win, loss
        FROM teams
        ORDER BY win DESC";

$result = $db->query($sql);

while ($row = $result->fetch_assoc()) {
    echo $row['team_name'] . 
         " | Wins: " . $row['win'] . 
         " | Losses: " . $row['loss'] . "<br>";
}
?>