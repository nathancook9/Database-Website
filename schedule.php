<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Conference Schedule</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Conference Schedule</h2>
</div>

<div class="main-content">

    <?php
    include 'connectDB.php';

    // Get all sponsors  in the dropdown
    $query = "SELECT DISTINCT sessionDate FROM session";
    $result = $connection->query($query);
    ?>

    <div class="dropdown-container">
        <form method="GET" action="">
            <label for="session">Select Date:</label>
            <select name="session" id="session" onchange="this.form.submit()">
                <option value="">All</option>
                <?php
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($_GET['session'] ?? '') == $row['sessionDate'] ? 'selected' : '';
                    echo "<option value=\"{$row['sessionDate']}\" $selected>{$row['sessionDate']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <?php
    // Determine selected sponsor
    $selectedDay = $_GET['session'] ?? '';

    if ($selectedDay !== '') {
        $query = "SELECT location, sessionDate, startTime, endTime, attendee.fname, attendee.lname FROM session Join attendee ON speakerID = attendee.id
                WHERE sessionDate = :sessionDate";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':sessionDate', $selectedDay);
        $stmt->execute();
    } else {
        $query = "SELECT location, sessionDate, startTime, endTime, attendee.fname, attendee.lname FROM session Join attendee ON speakerID = attendee.id";
        $stmt = $connection->query($query);
    }

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Room Number</th><th>Date</th><th>Start Time</th><th>End Time</th><th>First Name</th><th>Last Name</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
            echo "<td>" . htmlspecialchars($row['sessionDate']) . "</td>";
            echo "<td>" . htmlspecialchars($row['startTime']) . "</td>";
            echo "<td>" . htmlspecialchars($row['endTime']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No events found.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
