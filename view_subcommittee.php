<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Subcommittee Members</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="main-content">

    <h2>Sub-committee Information</h2>

    <?php
    include 'connectDB.php';

    // Get all subcommittees to populate the dropdown
    $query = "SELECT * FROM subcommittee";
    $result = $connection->query($query);
    ?>

    <!-- Dropdown Menu for the Different Subcommittees -->
    <div class="dropdown-container">
        <form method="GET" action="">
            <label for="subcommittee">Select Sub-committee:</label>
            <select name="subcommittee" id="subcommittee" onchange="this.form.submit()">
                <option value="">All</option>
                <?php
                while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    $selected = ($_GET['subcommittee'] ?? '') == $row['name'] ? 'selected' : '';
                    echo "<option value=\"{$row['name']}\" $selected>{$row['name']}</option>";
                }
                ?>
            </select>
        </form>
    </div>

    <?php
    // Determine selected subcommittee (if any)
    $selectedCommittee = $_GET['subcommittee'] ?? '';

    if ($selectedCommittee !== '') {
        // Output if subcommittee is selected
        $query = "SELECT 
            member.id AS member_id, memberOf.subcommittee AS subcommittee, member.fName, member.lName,subcommittee.chairID 
            FROM member JOIN memberOf ON member.id = memberOf.id JOIN subcommittee ON memberOf.subcommittee = subcommittee.name 
            WHERE memberOf.subcommittee = :subcommittee";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':subcommittee', $selectedCommittee);
        $stmt->execute();
    } else {
        // Query all members from all subcommittees
        $query = "SELECT member.id AS member_id, memberOf.subcommittee AS subcommittee, member.fName, member.lName, subcommittee.chairID 
            FROM member JOIN memberOf ON member.id = memberOf.id JOIN subcommittee ON memberOf.subcommittee = subcommittee.name";
        $stmt = $connection->query($query);
    }

    // Display members in a table
    if ($stmt->rowCount() > 0) {
        echo "<table border='1'>";
        echo "<tr><th>Worker ID</th><th>Sub Committee</th><th>First Name</th><th>Last Name</th><th>Position</th></tr>";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            //Check position of member
            $position = ($row['member_id'] == $row['chairID']) ? "Chair" : "Member";
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['member_id']) . "</td>";
            echo "<td>" . htmlspecialchars($row['subcommittee']) . "</td>";
            echo "<td>" . htmlspecialchars($row['fName']) . "</td>";
            echo "<td>" . htmlspecialchars($row['lName']) . "</td>";
            echo "<td>" . htmlspecialchars($position) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No members found.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
