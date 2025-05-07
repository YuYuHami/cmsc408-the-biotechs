<?php
include './db.php';

$table_name = $_GET['table']; // Get table name

// For query: publication table has "DOI" primary key name instead of "id" 
if ($table_name == "publication") { $pk = "DOI"; }
else { $pk = "id"; }

$id = $_POST[$pk];

#-----------------------------------------------#
# Build SQL string using each field & value set #
#-----------------------------------------------#
$sql = "UPDATE $table_name SET ";
foreach ($_POST as $field => $value) {
    // For columns with a date type, convert string inputs to date format just in case
    if ($field == "year" || $field == "last_updated") { date("Y-m-d", strtotime($value)); }
    $sql = $sql . $field . "='" . $value . "', ";
}
$sql = rtrim($sql, ', ');          // Trim trailing comma
$sql = $sql . " WHERE $pk='$id'";  // Finish SQL string
$result = $conn->query($sql);
$conn->close();

header("location: show-table.php?table=" . $table_name);
?>
