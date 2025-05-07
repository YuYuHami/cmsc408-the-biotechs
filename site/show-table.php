<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
<?php
include './db.php';

#----------------------------------------------------------#
# Check if the table name is provided as a query parameter #
#----------------------------------------------------------#
if (!isset($_GET['table'])) {
    die("No table specified.");
}
$table_name = $_GET['table'];

#-----------------------------------------------------#
# Publication table has DOI primary key instead of id #
#-----------------------------------------------------#
if ($table_name == "publication") { $id = "DOI"; }
else { $id = "id"; }

#-------------------------------------------------------#
# Query to retrieve the contents of the specified table #
#-------------------------------------------------------#
if ($table_name == "publication") {
    $sql = "SELECT * FROM `$table_name` ORDER BY tool_id"; // For publication table, order by the foreign key tool ID instead, to
}                                                          // keep publications associated with the same tools grouped together. 
else {
    $sql = "SELECT * FROM `$table_name` ORDER BY $id";     // Order by ID for all other tables
}
$result = $conn->query($sql);

echo "<body class=bg><b>";

echo "<a href='/'; class='btn btn-list'>Return to homepage</a><br/>"; // Button to return to homepage
#---------------------------------#
# Show contents of selected table #
#---------------------------------#
if ($result->num_rows > 0) {
    echo "<br>";
    echo "<h1 class=header>Contents of table: $table_name</h1>";
    echo "<div class='d-flex justify-content-center'>";
    echo "<table class='table table-hover table-condensed'><thead>";

    #----------------------#
    # Output table headers #
    #----------------------#
    $fields = $result->fetch_fields();
    echo "<tr>";
    foreach ($fields as $field) {
        echo "<th class=tableheader>" . htmlspecialchars($field->name ?? '') . "</th>";
    }
    echo "<th class=tableheader><th class=tableheader></tr></thead>";

    #-------------------#
    # Output table rows #
    #-------------------#
    while($row = $result->fetch_assoc()) {
        echo "<tbody><tr>";
        #-------------#
        # UPDATE form #
        #-------------#
        if (isset($_GET['id']) && $row[$id] == $_GET['id']) { // If id set in url, turn the row specified into a set of forms for updating
            echo "<form class='form-inline m-2' action='update.php?table=" . $table_name . "&id=" . $row[$id] . "' method='POST'>";
            
            // Input form for each column
            $i = 0;
            foreach ($row as $value) {
                $colname = mysqli_fetch_field_direct($result, $i)->name;  // Get the current field name
                echo '<td><b><input type="text" class="form-control" name="' . $colname . '"' . htmlspecialchars($value ?? '') . '</b></td>';
                $i = $i + 1;
            }

            echo '<td><button type="submit" class="btn btn-update">Save</button></td><td>'; // Submit button
            echo '</form>';
        } 
        else {
            foreach ($row as $value) {
                echo "<td><b>" . htmlspecialchars($value ?? '') . "</b></td>"; // Listing data rows normally
            }        
        #-------------------------#
        # UPDATE & DELETE buttons #
        #-------------------------#
        echo '<td style="width:0.1%"><a class="btn btn-sm btn-update" href="show-table.php?table='. $table_name . '&id=' . $row[$id] . '" role="button">Update</a></td>';
        echo '<td style="width:0.1%"><a class="btn btn-sm btn-delete" href="delete.php?table='. $table_name . '&id=' . $row[$id] . '" role="button">Delete</a></td>';
        }
    }
    echo "</tr></tbody></table>";

    #----------------------------------------#
    # CREATE operations (inserting new data) #
    #----------------------------------------#
    echo '<br><p class=maintext>Add a new row of data to the ' . $table_name . ' table:</p>';    
    echo '<form action="create.php?table='. $table_name . '" method="POST";>';
    
    foreach ($fields as $field) {
        echo '<div class="form-floating mb-2" style="font-size:18px">';
        echo '<input type="text" class="form-control mb-2 formbg" id="' . $field->name . '" name="' . $field->name . '">';
        echo '<label class=formlabel for="' . $field->name . '">' . htmlspecialchars($field->name) . ": </label></div>";
    }
    echo '<br style="font-size:10px">';
    echo '<button type="submit" class="btn btn-list">Add</button>';
    echo '</form></b></body>';
} else { echo "No records found in table: $table_name"; }

$conn->close();
?>
