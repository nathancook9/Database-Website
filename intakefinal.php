<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Intake</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Intake Summary</h2>
</div>

    <div class="main-form">

        <?php
        include 'connectDB.php';

        // Get all sponsors in the dropdown
        $query = "SELECT DISTINCT fee FROM attendee";
        $result = $connection->query($query);
        ?>

        <div class="dropdown-container">
            <form method="GET" action="">
                <label for="attendee">Select Fee:</label>
                <select name="attendee" id="attendee">
                    <option value="">All</option>
                    <?php
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($_GET['attendee'] ?? '') == $row['fee'] ? 'selected' : '';
                        echo "<option value=\"{$row['fee']}\" $selected>{$row['fee']}</option>";
                    }
                    ?>
                </select>

                <?php
                // Individual sponsor levels
                $query = "SELECT DISTINCT level FROM company ORDER BY FIELD(level, 'Platinum', 'Gold', 'Silver', 'Bronze')";
                $result = $connection->query($query);
                ?>

                <label for="company">Select Sponsor Level:</label>
                <select name="company" id="company">
                    <option value="">All</option>
                    <?php
                    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                        $selected = ($_GET['company'] ?? '') == $row['level'] ? 'selected' : '';
                        echo "<option value=\"{$row['level']}\" $selected>{$row['level']}</option>";
                    }
                    ?>
                </select>

                <input type="submit" value="Submit">
            </form>
        </div>

    </div>

    <div class="main-intake">
        <div class="main-fee">
            <?php
            // Determine selected sponsor
            $selectedFee = $_GET['attendee'] ?? '';
            $selectedLevel = $_GET['company'] ?? '';

            // Fee Summary Table
            echo "<h3>Fee Summary</h3>";

            $feeLevels = [0, 50, 100];
            echo "<table border='1'>";
            echo "<tr><th>Fee Amount</th><th>Number of Attendees</th><th>Total Collected</th></tr>";

            // Determine which fees to display
            $feeLevels = $selectedFee !== '' ? [$selectedFee] : [0, 50, 100];

            foreach ($feeLevels as $fee) {
                $query = "SELECT COUNT(*) AS count FROM attendee WHERE fee = :fee";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':fee', $fee);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $count = $result['count'];
                $total = $count * $fee;

                echo "<tr>";
                echo "<td>\${$fee}</td>";
                echo "<td>{$count}</td>";
                echo "<td>\${$total}</td>";
                echo "</tr>";
            }

            echo "</table>";

            ?>
        </div>

        <div class="main-sponsor">

            <?php
            // Define sponsor fee values
            $sponsorFees = [
                'Platinum' => 10000,
                'Gold'     => 5000,
                'Silver'   => 3000,
                'Bronze'   => 1000
            ];

            // Choose levels to show
            $sponsorLevels = $selectedLevel !== '' ? [$selectedLevel] : array_keys($sponsorFees);

            echo "<h3>Sponsor Summary</h3>";
            echo "<table border='1'>";
            echo "<tr><th>Sponsorship Level</th><th>Number of Sponsors</th><th>Total Value</th></tr>";

            foreach ($sponsorLevels as $level) {
                $query = "SELECT COUNT(*) AS count FROM company WHERE company.level = :level";
                $stmt = $connection->prepare($query);
                $stmt->bindParam(':level', $level);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $count = $result['count'];
                $total = $count * $sponsorFees[$level];

                echo "<tr>";
                echo "<td>{$level}</td>";
                echo "<td>{$count}</td>";
                echo "<td>\$" . number_format($total) . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            $connection = null;
            ?>
        </div>
    </div>

    

<div class="main-result">
    <?php
    include 'connectDB.php';

    $selectedFee = $_GET['attendee'] ?? '';
    $selectedLevel = $_GET['company'] ?? '';

    $attendeeFees = [0, 50, 100];
    $totalAttendeeSum = 0;

    $feeLevels = $selectedFee !== '' ? [$selectedFee] : $attendeeFees;

    foreach ($feeLevels as $fee) {
        $query = "SELECT COUNT(*) AS count FROM attendee WHERE fee = :fee";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':fee', $fee);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'];
        $totalAttendeeSum += $count * $fee;
    }

    // Assign each sponsor with amount
    $sponsorFees = [
        'Platinum' => 10000,
        'Gold'     => 5000,
        'Silver'   => 3000,
        'Bronze'   => 1000
    ];

    $totalSponsorSum = 0;
    $levels = $selectedLevel !== '' ? [$selectedLevel] : array_keys($sponsorFees);

    foreach ($levels as $level) {
        $query = "SELECT COUNT(*) AS count FROM company WHERE company.level = :level";
        $stmt = $connection->prepare($query);
        $stmt->bindParam(':level', $level);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $result['count'];
        $totalSponsorSum += $count * $sponsorFees[$level];
    }

    // Add totals from each table
    $grandTotal = $totalAttendeeSum + $totalSponsorSum;

    echo "<h3>Total Summary</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Category</th><th>Total Value</th></tr>";
    echo "<tr><td>Total Attendee Fees</td><td>\$" . number_format($totalAttendeeSum) . "</td></tr>";
    echo "<tr><td>Total Sponsor Contributions</td><td>\$" . number_format($totalSponsorSum) . "</td></tr>";
    echo "<tr><td><strong>Grand Total</strong></td><td><strong>\$" . number_format($grandTotal) . "</strong></td></tr>";
    echo "</table>";

    $connection = null;
    ?>
</div>

</body>
</html>
