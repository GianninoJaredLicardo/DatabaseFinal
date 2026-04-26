<?php require_once 'database/db.php'; ?>
<link rel="stylesheet" href="style.css">
<button><a href="index.php">Home</a></button> <br><br>

<h2>Submit Match Score</h2>

<form method="POST">

    Select Match:
    <select name="match_id" required>
        <option value="">-- Select Match --</option>

        <?php
        $matches = $db->query("
            SELECT m.id,
                   t1.team_name AS teamA,
                   t2.team_name AS teamB
            FROM matches m
            JOIN teams t1 ON m.team_a = t1.id
            JOIN teams t2 ON m.team_b = t2.id
        ");

        while ($m = $matches->fetch_assoc()) {
            echo "<option value='{$m['id']}'>
                    {$m['teamA']} vs {$m['teamB']} (Match #{$m['id']})
                  </option>";
        }
        ?>
    </select>
    <br><br>

    Team A Score: <input type="number" name="score_a" required><br><br>
    Team B Score: <input type="number" name="score_b" required><br><br>

    <button type="submit" name="submit_score">Submit Score</button>
</form>

<?php
if (isset($_POST['submit_score'])) {

    $match_id = $_POST['match_id'];
    $score_a = $_POST['score_a'];
    $score_b = $_POST['score_b'];

    // Get match teams
    $match = $db->query("
        SELECT * FROM matches WHERE id=$match_id
    ")->fetch_assoc();

    if (!$match) {
        echo "Match not found!";
    } else {

        $team_a = $match['team_a'];
        $team_b = $match['team_b'];

        // Determine winner
        if ($score_a > $score_b) {
            $winner = $team_a;
        } elseif ($score_b > $score_a) {
            $winner = $team_b;
        } else {
            $winner = null; // draw
        }

        // Transaction-like update (simple version)
        if ($winner) {
            $db->query("UPDATE matches 
                        SET winner_id=$winner, status='finished'
                        WHERE id=$match_id");
        } else {
            $db->query("UPDATE matches 
                        SET winner_id=NULL, status='draw'
                        WHERE id=$match_id");
        }

        echo "Score submitted successfully!";
    }
}
?>