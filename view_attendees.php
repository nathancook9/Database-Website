<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Attendees</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>List of All Attendees</h2>
</div>

<div class="main-attendees">

    <?php
    include 'connectDB.php';
    
    // Query all attendees
    $query = "SELECT * FROM attendee";
    $result = $connection->query($query);

    // Separate attendees by fee categories
    $feeCategories = [
        'Sponsor' => [],
        'Student' => [],
        'Professional' => []
    ];

    // Categorize attendees based on fee
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        if ($row['fee'] == 00) {
            $feeCategories['Sponsor'][] = $row;
        } elseif ($row['fee'] == 50) {
            $feeCategories['Student'][] = $row;
        } elseif ($row['fee'] == 100) {
            $feeCategories['Professional'][] = $row;
        }
    }

    // Function to display a table
    function displayTable($category, $attendees) {

        if (count($attendees) > 0) {
            echo "<table>";
            echo "<tr><th colspan='4' class='title'>" . htmlspecialchars($category) . "</th></tr>";
            echo "<tr><th>ID</th><th>First Name</th><th>Last Name</th></tr>";
            foreach ($attendees as $row) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['fName']) . "</td>";
                echo "<td>" . htmlspecialchars($row['lName']) . "</td>";
                // echo "<td>" . htmlspecialchars($row['fee']) . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>No attendees found.</p>";
        }
    }

    // Display tables for each fee category
    foreach ($feeCategories as $category => $attendees) {
        displayTable($category, $attendees);
    }

    // Close the database connection
    $connection = NULL;
    ?>
</div>

</body>
</html>