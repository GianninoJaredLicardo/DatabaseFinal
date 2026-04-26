<?php require_once "database/db.php"; ?>
<link rel="stylesheet" href="style.css">

<button><a href="index.php">Home</a></button> <br>

<h2>Create Match</h2>

<form method="POST">

    <select name="team_a">
        <option value="">Select Team A</option>
        <?php
        $teams = $db->query("SELECT * FROM teams");
        while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['team_name']}</option>";
        }
        ?>
    </select>

    VS

    <select name="team_b">
        <option value="">Select Team B</option>
        <?php
        $teams = $db->query("SELECT * FROM teams");
        while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['team_name']}</option>";
        }
        ?>
    </select>

    <button type="submit" name="create_match">Create Match</button>
</form>

<?php
if (isset($_POST['create_match'])) {

    $team_a = $_POST['team_a'];
    $team_b = $_POST['team_b'];

    if ($team_a == "" || $team_b == "") {
        echo "Please select both teams!<br>";
    } elseif ($team_a == $team_b) {
        echo "Teams must be different!<br>";
    } else {

        $db->query("INSERT INTO matches (team_a, team_b, status)
                    VALUES ($team_a, $team_b, 'pending')");

        echo "Match Created!<br>";
    }
}
?>

<hr>

<h2>Matches</h2>

<?php
$sql = "SELECT m.*,
        t1.team_name AS teamA,
        t2.team_name AS teamB
        FROM matches m
        JOIN teams t1 ON m.team_a = t1.id
        JOIN teams t2 ON m.team_b = t2.id";

$result = $db->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo $row['teamA'] . " vs " . $row['teamB'];
        echo " - " . $row['status'];
        echo "<br>";
    }
} else {
    echo "No matches yet.";
}
?>