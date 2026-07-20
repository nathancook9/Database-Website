<?php
// Include the database connection
include 'connectDB.php';

// Form submission logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addAttendee'])) {
    // Retrieve form data
    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $fee = $_POST['fee'];

    try {
        // Insert the new attendee into the attendee table
        $query = "INSERT INTO attendee (id, fname, lname, fee) VALUES (:id, :fname, :lname, :fee)";
        $stmt = $connection->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':fee', $fee);

        // Execute the query
        $stmt->execute();

        // Success message
        echo "<p class='success-message'>Attendee '$fname $lname' added successfully with a fee of '$fee'.</p>";

    } catch (PDOException $e) {
        echo "<p class='no-message'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add New Attendee</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Add New Attendee</h2>
</div>

<div class="main-add">
    <form method="POST" action="">
        <div class="form">
            <label for="id">Attendee ID:</label>
            <input type="text" name="id" id="id" required>
        </div class="form">

        <div class="form">
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" required>
        </div>

        <div class="form">
            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" required>
        </div>

        <div class="form">
            <label for="fee">Fee:</label>
            <select name="fee" id="fee" required>
                <option value="0">0</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>

        <button type="submit" name="addAttendee">Add</button>
    </form>
</div>

</body>
</html>
