<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Sponsors</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="top-text">
    <h2>Remove Conference Sponsor</h2>
</div>

<div class="main-content">
    <h3>This will also cause attending sponsors to be deleted.</h3>

    <?php
    include 'connectDB.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deleteSponsorID'])) {
        $companyName = $_POST['deleteSponsorID'];

        try {
            $connection->beginTransaction();

            //Check if the company has a sponsor
            $getSponsorQuery = "SELECT id FROM sponsor WHERE companyName = :companyName";
            $stmt = $connection->prepare($getSponsorQuery);
            $stmt->bindParam(':companyName', $companyName);
            $stmt->execute();
            $sponsorData = $stmt->fetch(PDO::FETCH_ASSOC);

            //Check if a sponsor exists and delete
            if ($sponsorData) {
                $sponsorId = $sponsorData['id'];

                // Delete attendee data associated with the sponsor
                $deleteAttendeeQuery = "DELETE FROM attendee WHERE id = :id";
                $stmtDel = $connection->prepare($deleteAttendeeQuery);
                $stmtDel->bindParam(':id', $sponsorId);
                $stmtDel->execute();

                // Delete sponsor data
                $deleteSponsorQuery = "DELETE FROM sponsor WHERE id = :id";
                $stmtSponsor = $connection->prepare($deleteSponsorQuery);
                $stmtSponsor->bindParam(':id', $sponsorId);
                $stmtSponsor->execute();
            }

            $deleteCompanyQuery = "DELETE FROM company WHERE name = :name";
            $stmtCompany = $connection->prepare($deleteCompanyQuery);
            $stmtCompany->bindParam(':name', $companyName);
            $stmtCompany->execute();

            $connection->commit();
            echo "<p class='success-message-del'>Sponsor and associated data successfully deleted for: <strong>" . htmlspecialchars($companyName) . "</strong></p>";

        } catch (PDOException $e) {
            $connection->rollBack();
            echo "<p class='no-message-del'>Error occurred while deleting sponsor: " . htmlspecialchars($e->getMessage()) . "</p>";
        }
    }

    // Get all avalible companys
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
        echo "<tr><th>Company</th><th>Level</th><th>Action</th></tr>";
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['name']) . "</td>";
            echo "<td>" . htmlspecialchars($row['level']) . "</td>";
            echo "<td>
                    <form method='POST' action=''>
                        <input type='hidden' name='deleteSponsorID' value='" . htmlspecialchars($row['name']) . "' />
                        <button type='submit' style='background-color:red;color:white;'>Delete</button>
                    </form>
                  </td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "<p>No companies found.</p>";
    }

    $connection = null;
    ?>
</div>

</body>
</html>
