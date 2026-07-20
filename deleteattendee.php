<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Attendee</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Remove Conference Attendee</h2>
</div>

<div class="main-content">
    <!-- <h3>This will also cause attending sponsors to be deleted.</h3> -->

    <?php
    include 'connectDB.php';

    // Handle form submission for attendee deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteAttendeeID'])) {
        $attendeeId = $_POST['deleteAttendeeID'];

        try {
            $connection->beginTransaction();

            // Step 1: Delete the attendee
            $deleteAttendeeQuery = "DELETE FROM attendee WHERE id = :id";
            $stmtDel = $connection->prepare($deleteAttendeeQuery);
            $stmtDel->bindParam(':id', $attendeeId);
            $stmtDel->execute();

            $connection->commit();
            echo "<p class='success-message-del'>Attendee " . htmlspecialchars($attendeeId) . " successfully deleted.</p>";

        } catch (PDOException $e) {
            $connection->rollBack();
            echo "<p class='no-message-del'>Error occurred while deleting attendee: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    // Fetch all attendees
    $query = "SELECT id, fname, lname FROM attendee ORDER BY id";
    $result = $connection->query($query);

    if ($result->rowCount() > 0) {
        echo "<table>";
        echo "<tr><th>First Name</th><th>Last Name</th><th>Attendee ID</th><th>Action</th></tr>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['fname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lname']) . "</td>";
            echo "<td>" . htmlspecialchars($row['id']) . "</td>";
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='deleteAttendeeID' value='" . htmlspecialchars($row['id']) . "' />
                        <button type='submit' style='background-color:red;color:white;'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No attendees found.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
