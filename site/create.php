<?php
include './db.php';

$table_name = $_GET['table']; // Get table name

#------------------------------------------------------#
# Build each field & value set for the full SQL string #
#------------------------------------------------------#
$fields = '';
$values = '';
foreach ($_POST as $field => $value) {
  // For columns with a date type, convert string inputs to date format just in case
  if ($field == "year" || $field == "last_updated") { date("Y/m/d", strtotime($value)); }
  $fields = $fields . $field . ",";
  $values = $values . "'" . $value . "',";
}
$fields = rtrim($fields, ','); // Trim trailing comma
$values = rtrim($values, ','); // Trim trailing comma

$sql = "INSERT INTO $table_name ($fields) VALUES ($values)";
$conn->query($sql);
$conn->close();

header("location: show-table.php?table=" . $table_name);
?>