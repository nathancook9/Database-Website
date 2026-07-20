<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>List of Jobs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

    <h2>Employment Opportunities</h2>

    <h3>Sort by Sponsor or select "All" to see all available jobs.</h3>

    <?php
    include 'connectDB.php';

    // Get all sponsors  in the dropdown
    $query = "SELECT * FROM sponsor";
    $result = $connection->query($query);
    ?>

    <div class="dropdown-container">
        <form method="GET" action="">
            <label for="sponsor">Select Sponsor:</label>
            <select name="sponsor" id="sponsor" onchange="this.form.submit()">
                <option value="">All</option>
                <?php
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($_GET['sponsor'] ?? '') == $row['companyName'] ? 'selected' : '';
                    echo "<option value=\"{$row['companyName']}\" $selected>{$row['companyName']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <?php
    // Determine selected sponsor
    $selectedSponsor = $_GET['sponsor'] ?? '';

    if ($selectedSponsor !== '') {
        $query = "SELECT * FROM sponsor JOIN jobAd ON sponsor.companyName = jobAd.companyName WHERE jobAd.companyName = :sponsor";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':sponsor', $selectedSponsor);
        $stmt->execute();
    } else {
        $query = "SELECT companyName, jobTitle, salary, location FROM jobAd";
        $stmt = $connection->query($query);
    }

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Company</th><th>Job Title</th><th>Salary (Annual)</th><th>Location</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['companyName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['jobTitle']) . "</td>";
            echo "<td>\$" . htmlspecialchars($row['salary']) . "</td>";
            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No jobs found.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
