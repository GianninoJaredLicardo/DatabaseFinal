<?php require_once 'database/db.php'; ?>
<link rel="stylesheet" href="style.css">
<button><a href="index.php">Home</a></button> <br>

<h2>Add Team</h2>

<form method="POST">
    <input type="text" name="team_name" placeholder="Team Name">
    <button type="submit" name="add_team">Add Team</button>
</form>

<?php
if (isset($_POST['add_team'])) {
    $team_name = $_POST['team_name'];

    if ($team_name == "") {
        echo "Please enter a team name!<br>";
    } else {
        $check = $db->query("SELECT * FROM teams WHERE team_name='$team_name'");

        if ($check->num_rows > 0) {
            echo "Team already exists!<br>";
        } else {
            $db->query("INSERT INTO teams (team_name) VALUES ('$team_name')");
            echo "Team Added!<br>";
        }
    }
}
?>

<hr>

<h2>Remove Team</h2>

<form method="POST">
    <select name="team_id">
        <option value="">-- Select Team --</option>
        <?php
        $teams = $db->query("SELECT * FROM teams");
        while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['team_name']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="remove_team">Remove Team</button>
</form>

<?php
if (isset($_POST['remove_team'])) {
    $team_id = $_POST['team_id'];

    if ($team_id == "") {
        echo "Please select a team!<br>";
    } else {
        $check = $db->query("SELECT * FROM teams WHERE id=$team_id");

        if ($check->num_rows == 0) {
            echo "Team does not exist!<br>";
        } else {
            // delete players first
            $db->query("DELETE FROM players WHERE team_id=$team_id");
            $db->query("DELETE FROM teams WHERE id=$team_id");

            echo "Team Removed!<br>";
        }
    }
}
?>

<hr>

<h2>Add Player</h2>

<form method="POST">
    <input type="text" name="player_name" placeholder="Player Name">

    <select name="team_id">
        <option value="">-- Select Team --</option>
        <?php
        $teams = $db->query("SELECT * FROM teams");
        while ($t = $teams->fetch_assoc()) {
            echo "<option value='{$t['id']}'>{$t['team_name']}</option>";
        }
        ?>
    </select>

    <button type="submit" name="add_player">Add Player</button>
</form>

<?php
if (isset($_POST['add_player'])) {
    $player_name = $_POST['player_name'];
    $team_id = $_POST['team_id'];

    if ($player_name == "" || $team_id == "") {
        echo "Please fill all fields!<br>";
    } else {
        $db->query("INSERT INTO players (name, team_id) VALUES ('$player_name', '$team_id')");
        echo "Player Added!";
    }
}
?>

<hr>

<h2>Remove Player</h2>

<form method="POST">
    <select name="player_id">
        <option value="">-- Select Player --</option>
        <?php
        $players = $db->query("SELECT * FROM players");
        while ($p = $players->fetch_assoc()) {
            echo "<option value='{$p['id']}'>{$p['name']}</option>";
        }
        ?>
    </select>
    <button type="submit" name="remove_player">Remove Player</button>
</form>

<?php
if (isset($_POST['remove_player'])) {
    $player_id = $_POST['player_id'];

    if ($player_id == "") {
        echo "Please select a player!<br>";
    } else {
        $check = $db->query("SELECT * FROM players WHERE id=$player_id");

        if ($check->num_rows == 0) {
            echo "Player does not exist!<br>";
        } else {
            $db->query("DELETE FROM players WHERE id=$player_id");
            echo "Player Removed!";
        }
    }
}
?>

<hr>

<h2>Teams and Players List</h2>

<?php
$sql = "SELECT teams.id, teams.team_name, players.name AS player_name
        FROM teams
        LEFT JOIN players ON teams.id = players.team_id
        ORDER BY teams.id";

$result = $db->query($sql);

$current_team = null;
$has_player = false;

while ($row = $result->fetch_assoc()) {

    if ($current_team != $row['team_name']) {

        if ($current_team !== null && !$has_player) {
            echo "- No players yet<br>";
        }

        echo "<br><b>Team: " . $row['team_name'] . "</b><br>";

        $current_team = $row['team_name'];
        $has_player = false;
    }

    if ($row['player_name']) {
        echo "- " . $row['player_name'] . "<br>";
        $has_player = true;
    }
}

if ($current_team !== null && !$has_player) {
    echo "- No players yet<br>";
}
?>