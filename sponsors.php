<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sponsors</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Conference Sponsors</h2>
</div>

<div class="main-content">

    <?php
    include 'connectDB.php';
    
    $query = "SELECT * FROM company ORDER BY CASE company.level
                WHEN 'Platinum' THEN 1
                WHEN 'Gold' THEN 2
                WHEN 'Silver' THEN 3
                WHEN 'Bronze' THEN 4
                ELSE 5
            END";
    $result = $connection->query($query);

    if ($result->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>Level</th><th>Company Name</th><th>Emails Sent</th></tr>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            // echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['level']) . "</td>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['emailsSent']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No records found.</p>";
    }
    

    $connection = NULL;
    ?>
</div>

</body>
</html>