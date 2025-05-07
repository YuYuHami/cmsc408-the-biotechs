<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link rel="stylesheet" href="styles.css">

<?php
include './db.php';

#----------------------------#
# List of selectable queries #
#----------------------------#
$queries = ['Which tools are primarily written in C?', 
            'Which tools are used for differential gene expression analysis?',
            'What is the DOI of the publication with the highest number of citations?', 
            'Which tools have been updated since 2022?',
            'Which tools have not been updated within the last 2 years?',
            'Which tools were last updated in 2023, and what are their documentation URLs?',
            'Which developers developed the tool BWA?',
            'Which developers have written tools in Java?',
            'Which developers have developed 2 or more tools in the database?',
            'Which tools have at least 1 associated publication?',
            'Which tools have a graphical user interface (GUI)?',
            'Which tools have a web-based interface OR a command-line interface (CLI)?',
            'Which tools have both a web-based interface AND a command-line interface, and what are their repositories?',
            'Which tools are maintained by the developer Heng Li, and when were they last updated?',
            'Which publications are associated with the tool Clustal Omega?',
            'Which publications have been cited over 500 times and are associated with read alignment tools?',
            'Which tools are written in R and have over 100 citations in their associated publications?',
            'Which tools support multiple sequence alignment, and when were they last updated?',
            'Which tools are associated with publications in the "Protein Science" journal and have over 1000 citations?',
            'Which tools are available as a Python module or R package, and have a repository?'];

echo '<body class=bg>';
echo '<a href="/"; class="btn btn-list">Return to homepage</a><br><br>'; // Button to return to homepage
echo '<h1 class=header>Select a query from the following list.</h1>';
echo '<ul class=ulsplit style="text-align:left">';
#---------------------------------------------------------------#
# Loop through queries to print them to the screen in list form #
#---------------------------------------------------------------#
$i = 0;
foreach($queries as $query) {
    echo '<li class="smallerlist">' . $i + 1 . '. ' . $query . '<br><br style="font-size:5px"><a class="btn btn-sm btn-list" href="show-query.php?query=' . $i . '" role="button">Run query</a>';
    $i = $i + 1;
}
echo '</ul></body>';