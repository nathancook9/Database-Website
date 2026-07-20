<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Student Rooms</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Student Rooming Arrangments</h2>
</div>

<div class="main-content">


    <h3>Sort by Room or select "All" to see all rooming arrangements.</h3>

    <?php
    include 'connectDB.php';

    // Get all sponsors  in the dropdown
    $query = "SELECT DISTINCT roomNum FROM student";
    $result = $connection->query($query);
    ?>

    <div class="dropdown-container">
        <form method="GET" action="">
            <label for="student">Select Room:</label>
            <select name="student" id="student" onchange="this.form.submit()">
                <option value="">All</option>
                <?php
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($_GET['student'] ?? '') == $row['roomNum'] ? 'selected' : '';
                    echo "<option value=\"{$row['roomNum']}\" $selected>{$row['roomNum']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <?php
    // Determine selected sponsor
    $selectedRoom = $_GET['student'] ?? '';

    if ($selectedRoom !== '') {
        $query = "SELECT student.roomNum, student.id, attendee.fname, attendee.lname 
                  FROM student 
                  JOIN attendee ON student.id = attendee.id 
                  WHERE student.roomNum = :student";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':student', $selectedRoom);
        $stmt->execute();
    } else {
        $query = "SELECT student.roomNum, student.id, attendee.fname, attendee.lname FROM student JOIN attendee ON student.id = attendee.id";
        $stmt = $connection->query($query);
    }

    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Room</th><th>ID</th><th>First Name</th><th>Last Name</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['roomNum']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No jobs found.</p>";
    }

    $connection = null;
    ?>

    <img src="hotelfloor.jpg">

    </img>
</div>

</body>
</html>
