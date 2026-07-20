<?php
include 'connectDB.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $location = $_POST['location'];
    $sessionDate = $_POST['sessionDate'];
    $startTime = $_POST['startTime'];
    $endTime = $_POST['endTime'];

    $originalLocation = $_POST['originalLocation'];
    $originalDate = $_POST['originalDate'];
    $originalStart = $_POST['originalStart'];

    //Prevent double booking
    $checkQuery = "SELECT COUNT(*) FROM session WHERE location = :location 
                   AND sessionDate = :sessionDate
                   AND startTime = :startTime
                   AND NOT (location = :originalLocation AND sessionDate = :originalDate AND startTime = :originalStart)"; 

    
    $checkStmt = $connection->prepare($checkQuery);
    $checkStmt->bindParam(':location', $location);
    $checkStmt->bindParam(':sessionDate', $sessionDate);
    $checkStmt->bindParam(':startTime', $startTime);
    $checkStmt->bindParam(':originalLocation', $originalLocation);
    $checkStmt->bindParam(':originalDate', $originalDate);
    $checkStmt->bindParam(':originalStart', $originalStart);
    $checkStmt->execute();
    $count = $checkStmt->fetchColumn();

    //Give error if already booked
    if ($count > 0) {
        echo "<p class='no-message'>The selected session slot is already booked.</p>";
    } else {
        // If avalible change information
        $updateQuery = "UPDATE session SET location = :location, sessionDate = :sessionDate, startTime = :startTime, endTime = :endTime
                        WHERE location = :originalLocation AND sessionDate = :originalDate AND startTime = :originalStart";

        $stmt = $connection->prepare($updateQuery);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':sessionDate', $sessionDate);
        $stmt->bindParam(':startTime', $startTime);
        $stmt->bindParam(':endTime', $endTime);
        $stmt->bindParam(':originalLocation', $originalLocation);
        $stmt->bindParam(':originalDate', $originalDate);
        $stmt->bindParam(':originalStart', $originalStart);
        $stmt->execute();

        echo "<p class='success-message'>Session updated successfully!</p>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Schedule</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Edit Conference Schedule</h2>
</div>

<div class="main-content">

    <?php
    // Use distinct dates for the dropdown menu
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
    
    $selectedDay = $_GET['session'] ?? '';

    // Select day and data
    if ($selectedDay !== '') {
        $query = "SELECT location, sessionDate, startTime, endTime, attendee.fname, attendee.lname 
                  FROM session JOIN attendee ON speakerID = attendee.id 
                  WHERE sessionDate = :sessionDate";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':sessionDate', $selectedDay);
        $stmt->execute();
    } else {
        $query = "SELECT location, sessionDate, startTime, endTime, attendee.fname, attendee.lname 
                  FROM session JOIN attendee ON speakerID = attendee.id";
        $stmt = $connection->query($query);
    }

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Room</th><th>Date</th><th>Start Time</th><th>End Time</th><th>First Name</th><th>Last Name</th><th>Action</th></tr>";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<form method='POST' action=''>";

            // Input to change
            echo "<td><input type='text' name='location' value='" . htmlspecialchars($row['location']) . "' required></td>";
            echo "<td><input type='date' name='sessionDate' value='" . htmlspecialchars($row['sessionDate']) . "' required></td>";
            echo "<td><input type='time' name='startTime' value='" . htmlspecialchars($row['startTime']) . "' required></td>";
            echo "<td><input type='time' name='endTime' value='" . htmlspecialchars($row['endTime']) . "' required></td>";

            // Non-edit speaker names
            echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lname']) . "</td>";

            // Locate data for change
            echo "<input type='hidden' name='originalLocation' value='" . htmlspecialchars($row['location']) . "'>";
            echo "<input type='hidden' name='originalDate' value='" . htmlspecialchars($row['sessionDate']) . "'>";
            echo "<input type='hidden' name='originalStart' value='" . htmlspecialchars($row['startTime']) . "'>";
            echo "<td><button type='submit' name='update'>Update</button></td>";
            echo "</form>";
            echo "</tr>";
        }

        echo "</table>";
    } else {
        echo "<p>No events for change.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
