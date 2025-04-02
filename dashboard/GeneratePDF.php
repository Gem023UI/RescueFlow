<?php
require '../vendor/autoload.php';
use Dompdf\Dompdf;

session_start();
include('../includes/config.php');

$dompdf = new Dompdf();
ob_start();

if (!$conn) {
    die("Database connection error.");
}
?>

<html>
<head>
    <title>Rescue Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h2, h3 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid black; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .section { margin-bottom: 30px; }
        .date { text-align: right; font-size: 14px; font-weight: bold; }
    </style>
</head>
<body>

    <h2>Rescue Dashboard Report</h2>
    <p class="date">Date: <?php echo date("F j, Y"); ?></p>

    <!-- Active Dispatches Section -->
    <div class="section">
        <h3>Active Dispatches</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Status</th>
                <th>Dispatched At</th>
            </tr>
            <?php
            $sql_dispatches = "SELECT * FROM dispatches WHERE status_id != 3 ORDER BY dispatched_at DESC";
            $result_dispatches = $conn->query($sql_dispatches);
if (!$result_dispatches) {
    die("Error in dispatch query: " . $conn->error);
}
//
            $result_dispatches = $conn->query($sql_dispatches);
            while ($row = $result_dispatches->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['dispatch_id']}</td>
                        <td>{$row['status_id']}</td>
                        <td>{$row['dispatched_at']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Attendance Section -->
    <div class="section">
        <h3>Today's Attendance</h3>
        <table>
            <tr>
                <th>Personnel</th>
                <th>Rank</th>
                <th>Time In</th>
                <th>Time Out</th>
            </tr>
            <?php
            $query_today = "
                SELECT p.FirstName, p.LastName, r.rank_name AS Rank, a.timestamp AS TimeIn, a.time_out AS TimeOut
                FROM attendance a
                JOIN personnel p ON a.personnel_id = p.PersonnelID
                JOIN ranks r ON p.RankID = r.rank_id
                WHERE DATE(a.timestamp) = CURDATE()";
            $result_today = $conn->query($query_today);
            while ($row = $result_today->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['FirstName']} {$row['LastName']}</td>
                        <td>{$row['Rank']}</td>
                        <td>{$row['TimeIn']}</td>
                        <td>{$row['TimeOut']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

    <!-- Training Section -->
    <div class="section">
        <h3>Scheduled Training Sessions</h3>
        <table>
            <tr>
                <th>Training Name</th>
                <th>Description</th>
                <th>Scheduled Date</th>
            </tr>
            <?php
            $todayDate = date("Y-m-d");
            $sql_training = "SELECT training_name, description, scheduled_date FROM trainings WHERE DATE(scheduled_date) = '$todayDate'";
            $result_training = $conn->query($sql_training);
            while ($row = $result_training->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['training_name']}</td>
                        <td>{$row['description']}</td>
                        <td>{$row['scheduled_date']}</td>
                      </tr>";
            }
            ?>
        </table>
    </div>

</body>
</html>

<?php
$html = ob_get_clean();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("RescueFlow Dashboard.pdf", ["Attachment" => 0]); // Opens in browser
?>