<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
</head>
<body>

<?php
include './db.php';

#-----------------------------------------#
# Query to get all tables in the database #
#-----------------------------------------#
$sql = "SHOW TABLES";
$result = $conn->query($sql);

echo "<body class=bg>";
if ($result->num_rows > 0) {
    echo "<h1 class=header>Welcome to the Bioinformatics Tool Database!</h1>";
    echo "<p class=maintext>Select a table from the database to view:</p>";
    echo "<ul class='maintext list'>";        
    #--------------------------------------------#
    # List all tables in the database as buttons #
    #--------------------------------------------#
    while($row = $result->fetch_array()) {
        $table_name = $row[0];
        echo "<li style='padding:2px'><a class='btn btn-list' href='show-table.php?table=$table_name'>$table_name</a></li>";
    }
    echo "</ul>";
    #----------------------------------------------#
    # Link to reports page with 20 example queries #
    #----------------------------------------------#
    echo "<p class=maintext>Or view some example queries <a class=link href='reports.php'>here</a>.</p>";
} else { echo "<p class=maintext>No tables found in the database.</p>"; }

$conn->close();
?>
