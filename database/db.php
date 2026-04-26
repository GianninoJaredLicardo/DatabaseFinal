<?php

$db = new mysqli("localhost", "root", "", "tournament");

if ($db -> connect_error) {
    die("Connection Error: " . $db->connect_error);
}