<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">
<script src="https://cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js"></script>

<?php
include './db.php';

#-----------------------------------------#
# Array of queries & their respective SQL #
#-----------------------------------------#
$queries = ['Which tools are primarily written in C?' => 'SELECT name AS "Tool name", program_lang AS "Programming language" FROM tool WHERE program_lang = "C"',
            'Which tools are used for differential gene expression analysis?' => 'SELECT t.name AS "Tool name", c.name AS "Category" FROM tool t LEFT JOIN tool_category tc ON t.id = tc.tool_id LEFT JOIN category c ON tc.cat_id = c.id WHERE c.name = "Differential gene expression analysis"',
            'What is the DOI of the publication with the highest number of citations?' => 'SELECT DOI, title AS "Title", citations AS "Number of citations" FROM publication WHERE citations = (SELECT MAX(citations) FROM publication)', 
            'Which tools have been updated since 2022?' => 'SELECT name AS "Tool name", last_updated AS "Last updated" FROM tool where YEAR(last_updated) >= 2022 ORDER BY last_updated',
            'Which tools have not been updated within the last 2 years?' => 'SELECT name AS "Tool name", last_updated AS "Last updated" FROM tool WHERE last_updated NOT BETWEEN CURDATE() - INTERVAL 2 YEAR AND CURDATE()',
            'Which tools were last updated in 2023, and what are their documentation URLs?' => 'SELECT name AS "Tool name", doc AS "Documentation URL", last_updated AS "Last updated" FROM tool WHERE YEAR(last_updated) = 2023',
            'Which developers developed the tool BWA?' => 'SELECT d.name AS "Developer name", t.name AS "Tool name" FROM developer d LEFT JOIN tool_developer td ON d.id = td.dev_id LEFT JOIN tool t ON td.tool_id = t.id WHERE t.name = "BWA"',
            'Which developers have written tools in Java?' => 'SELECT d.name AS "Developer name", t.name AS "Tool name", t.program_lang AS "Programming language" FROM developer d LEFT JOIN tool_developer td ON d.id = td.dev_id LEFT JOIN tool t ON td.tool_id = t.id WHERE t.program_lang = "Java"',
            'Which developers have developed 2 or more tools in the database?' => 'SELECT d.name AS "Developer name", COUNT(*) AS "Number of tools" FROM developer d LEFT JOIN tool_developer td ON d.id = td.dev_id GROUP BY d.id HAVING COUNT(*) > 1',
            'Which tools have at least 1 associated publication?' => 'SELECT t.name AS "Tool name", p.title AS "Publication title", p.DOI AS "Publication DOI" FROM tool t LEFT JOIN publication p ON t.id = p.tool_id WHERE p.DOI IS NOT NULL',
            'Which tools have a graphical user interface (GUI)?' => 'SELECT t.name AS "Tool name", i.type AS "Interface type" FROM tool t LEFT JOIN tool_interface ti ON t.id = ti.tool_id LEFT JOIN interface i ON ti.interface_id = i.id WHERE i.type = "GUI"',
            'Which tools have a web-based interface OR a command-line interface (CLI)?' => 'SELECT t.name AS "Tool name", i.type AS "Interface type" FROM tool t LEFT JOIN tool_interface ti ON t.id = ti.tool_id LEFT JOIN interface i ON ti.interface_id = i.id WHERE i.type = "Web-based" OR i.type = "CLI" ORDER BY i.type',
            'Which tools have both a web-based interface AND a command-line interface, and what are their repositories?' => 'SELECT t.name AS "Tool name", t.repo AS "Tool repository" FROM tool t LEFT JOIN tool_interface ti ON t.id = ti.tool_id LEFT JOIN interface i ON ti.interface_id = i.id WHERE i.type = "Web-based" OR i.type = "CLI" GROUP BY t.name, t.repo HAVING COUNT(DISTINCT i.type) = 2',
            'Which tools are maintained by the developer Heng Li, and when were they last updated?' => 'SELECT t.name AS "Tool name", t.last_updated AS "Last updated", d.name AS "Developer name" FROM tool t LEFT JOIN tool_developer td ON t.id = td.tool_id LEFT JOIN developer d ON td.dev_id = d.id WHERE d.name = "Heng Li" ORDER BY t.last_updated',
            'Which publications are associated with the tool Clustal Omega?' => 'SELECT p.title AS "Publication title", p.DOI, t.name AS "Tool name" FROM tool t LEFT JOIN publication p ON t.id = p.tool_id WHERE t.name = "Clustal Omega"',
            'Which publications have been cited more than 500 times and are associated with read alignment tools?' => 'SELECT p.title AS "Publication title", p.DOI, p.citations AS "Number of citations", t.name AS "Tool name", c.name AS "Category" FROM publication p LEFT JOIN tool t ON p.tool_id = t.id LEFT JOIN tool_category tc ON t.id = tc.tool_id LEFT JOIN category c ON tc.cat_id = c.id WHERE c.name = "Read alignment" AND p.citations > 50',
            'Which tools are written in R and have over 100 citations in their associated publications?' => 'SELECT t.name AS "Tool name", t.program_lang AS "Programming language", p.title AS "Publication title", p.DOI, p.citations AS "Number of citations" FROM tool t LEFT JOIN publication p ON t.id = p.tool_id WHERE t.program_lang = "R" AND p.citations > 100',
            'Which tools support multiple sequence alignment, and when were they last updated?' => 'SELECT t.name AS "Tool name", t.last_updated AS "Last updated", c.name AS "Category" FROM tool t LEFT JOIN tool_category tc ON t.id = tc.tool_id LEFT JOIN category c ON tc.cat_id = c.id WHERE c.name = "Multiple sequence alignment"',
            'Which tools are associated with publications in the "Protein Science" journal and have over 1000 citations?' => 'SELECT t.name AS "Tool name", p.title AS "Publication title", p.journal AS "Journal", p.DOI, p.citations AS "Number of citations" FROM tool t LEFT JOIN publication p ON t.id = p.tool_id WHERE p.journal = "Protein Science" AND p.citations > 1000',
            'Which tools are available as a Python module or R package, and have a repository?' => 'SELECT t.name AS "Tool name", i.type AS "Interface", t.repo AS "Repository" FROM tool t LEFT JOIN tool_interface ti ON t.id = ti.tool_id LEFT JOIN interface i ON ti.interface_id = i.id WHERE (i.type = "Python module" OR i.type = "R package") AND t.repo IS NOT NULL'];

$query_num = $_GET['query']; // Get query number from url
$query_words = array_keys($queries)[$query_num]; // Query in question format
$query_sql = array_values($queries)[$query_num]; // Query in SQL format

echo '<body class=bg>';
echo '<a href="./reports.php"; class="btn btn-list">Return to queries</a> '; // Button to return to queries
echo '<a href="/"; class="btn btn-list">Return to homepage</a><br><br>';     // Button to return to homepage
echo '<p class=maintext>' . $query_words . '</p>';                           // Print the query in question format at the top 
echo '<details><summary>Show SQL query</summary><br><code class="prettyprint lang-sql">' . $query_sql . '</code><br><br></details><br>'; // Optional dropdown to show SQL code

$result = $conn->query($query_sql);

#-----------------------------------------#
# Print results of the selected SQL query #
#-----------------------------------------#
if ($result->num_rows > 0) {
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
    echo "</tr></thead>";
    #-------------------#
    # Output table rows #
    #-------------------#
    while($row = $result->fetch_assoc()) {
        echo "<tbody><tr>";
        foreach ($row as $value) {
            echo "<td><b>" . htmlspecialchars($value ?? '') . "</b></td>"; // Listing data rows normally
        }        
    }
    echo "</tr></tbody></table>";
} else { echo "<p class=maintext>No records found.</p>"; }
