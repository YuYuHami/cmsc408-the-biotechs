<?php
include './db.php';

$table_name = $_GET['table'];
$id = $_GET['id'];

// For query: publication table has "DOI" primary key name instead of "id" 
if ($table_name == "publication") { $pk = "DOI"; }
else { $pk = "id"; }

$sql = "DELETE FROM $table_name WHERE $pk='$id'";
$conn->query($sql);
$conn->close();
header("location: show-table.php?table=" . $table_name);
?>