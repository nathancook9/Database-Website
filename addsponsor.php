<?php
// Include the database connection
include 'connectDB.php';

// Form submission logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['addCompany'])) {
    // Retrieve form data
    $name = $_POST['name'];
    $emailsSent = $_POST['emailsSent'];
    $level = $_POST['level'];

    try {
        // Insert the new company into the company table
        $query = "INSERT INTO company (name, emailsSent, level) VALUES (:name, :emailsSent, :level)";
        $stmt = $connection->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':emailsSent', $emailsSent, PDO::PARAM_INT);
        $stmt->bindParam(':level', $level);

        $stmt->execute();

        echo "<p class='success-message'>Company '$name' added successfully with level '$level' and sponsor data added.</p>";


    } catch (PDOException $e) {
        echo "<p class='no-message'>Error: " . $e->getMessage() . "</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Company</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Add New Company</h2>
</div>

<div class="main-add">
    <form method="POST" action="">
        <div class="form">
            <label for="name">Company Name:</label>
            <input type="text" name="name" id="name" required>
        </div>

        <div class="form">
            <label for="emailsSent">Emails Sent:</label>
            <input type="number" name="emailsSent" id="emailsSent" required>
        </div>

        <div class="form">
            <label for="level">Sponsor Level:</label>
            <select name="level" id="level" required>
                <option value="Platinum">Platinum</option>
                <option value="Gold">Gold</option>
                <option value="Silver">Silver</option>
                <option value="Bronze">Bronze</option>
            </select>
        </div>

        <button type="submit" name="addCompany">Add Company</button>
    </form>
</div>

</body>
</html>
