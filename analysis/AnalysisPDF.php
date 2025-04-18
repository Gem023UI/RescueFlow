<?php
require '../includes/config.php';
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('RescueNet');
$pdf->SetTitle('Incident Reports');

// Set margins and page properties
$pdf->SetMargins(20, 20, 20);
$pdf->SetAutoPageBreak(TRUE, 20);
$pdf->AddPage();

// Add BFP Logo
$logoPath = $_SERVER['DOCUMENT_ROOT'] . '/RESCUEFLOW(1)/incident/uploads/bfpncrlogo.png';
$pdf->Image($logoPath, 85, 10, 40, 40, 'PNG', '', '', true);

// Add Header Text
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->Ln(45); // Move below logo
$pdf->Cell(0, 10, 'Republic of the Philippines', 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 12);
$pdf->Cell(0, 10, 'Department of the Interior and Local Government', 0, 1, 'C');
$pdf->Cell(0, 10, 'BFP Taguig City - Fire Station 1', 0, 1, 'C');
$pdf->MultiCell(0, 10, 'Radian Road, Arca South (Formerly FTI Complex), Western Bicutan, Taguig City, Philippines', 0, 'C', false, 1);
$pdf->SetFont('Helvetica', 'B', 14);
$pdf->Ln(5);

// Line Break
$pdf->Ln(10);

// First Section: Incident Count by Date and Barangay
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Incident Count by Date and Barangay', 0, 1, 'C');

// Fetch incident counts by date and barangay
$sql1 = "SELECT DATE(reported_time) AS incident_date, b.barangay_name AS barangay, COUNT(*) as count 
        FROM incidents i
        JOIN barangays b ON i.barangay_id = b.barangay_id
        GROUP BY incident_date, b.barangay_name
        ORDER BY incident_date ASC";
$result1 = $conn->query($sql1);

$html1 = '<style>
            table { border-collapse: collapse; width: 100%; font-size: 10px; }
            th, td { border: 1px solid black; padding: 6px; text-align: center; }
            th { background-color: #5bc0de; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f2f2f2; }
         </style>
         <table>
            <tr>
                <th style="width: 30%;">Date</th>
                <th style="width: 50%;">Barangay</th>
                <th style="width: 20%;">Count</th>
            </tr>';

while ($row1 = $result1->fetch_assoc()) {
    $html1 .= '<tr>
                <td>' . htmlspecialchars($row1['incident_date']) . '</td>
                <td>' . htmlspecialchars($row1['barangay']) . '</td>
                <td>' . htmlspecialchars($row1['count']) . '</td>
              </tr>';
}

$html1 .= '</table>';
$pdf->writeHTML($html1, true, false, true, false, '');
$pdf->Ln(10);

// Second Section: Incident Count by Cause
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Incident Count by Cause', 0, 1, 'C');

// Fetch incident counts by cause
$sql2 = "SELECT cause, COUNT(*) AS count FROM incidents WHERE cause IS NOT NULL GROUP BY cause ORDER BY count DESC";
$result2 = $conn->query($sql2);

$html2 = '<style>
            table { border-collapse: collapse; width: 100%; font-size: 10px; }
            th, td { border: 1px solid black; padding: 6px; text-align: center; }
            th { background-color: #5cb85c; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f2f2f2; }
         </style>
         <table>
            <tr>
                <th style="width: 70%;">Cause</th>
                <th style="width: 30%;">Count</th>
            </tr>';

while ($row2 = $result2->fetch_assoc()) {
    $html2 .= '<tr>
                <td>' . htmlspecialchars($row2['cause']) . '</td>
                <td>' . htmlspecialchars($row2['count']) . '</td>
              </tr>';
}

$html2 .= '</table>';
$pdf->writeHTML($html2, true, false, true, false, '');
$pdf->Ln(15);

// Third Section: Detailed Incident Reports
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Detailed Incident Reports', 0, 1, 'C');

// Fetch incidents from database
$sql = "SELECT i.incident_id, i.incident_type, 
               b.barangay_name, i.address, i.reported_by, 
               i.reported_time, s.level AS severity, 
               i.cause
        FROM incidents i
        LEFT JOIN barangays b ON i.barangay_id = b.barangay_id
        LEFT JOIN severity s ON i.severity_id = s.id
        ORDER BY i.reported_time DESC";

$result = $conn->query($sql);

// Table Header with Improved Styling
$html = '<style>
            table { border-collapse: collapse; width: 100%; font-size: 8px; }
            th, td { border: 1px solid black; padding: 5px; text-align: center; }
            th { background-color: #d9534f; color: white; font-weight: bold; }
            tr:nth-child(even) { background-color: #f2f2f2; }
         </style>
         <table>
            <tr>
                <th style="width: 8%;">Incident ID</th>
                <th style="width: 12%;">Incident Type</th>
                <th style="width: 10%;">Severity</th>
                <th style="width: 12%;">Barangay</th>
                <th style="width: 18%; text-align: left;">Address</th>
                <th style="width: 14%;">Reported By</th>
                <th style="width: 10%; white-space: nowrap;">Date</th>
                <th style="width: 8%; white-space: nowrap;">Time</th>
                <th style="width: 12%; text-align: left;">Cause</th>
            </tr>';

// Add Table Data
while ($row = $result->fetch_assoc()) {
    $reported_time = $row['reported_time'];
    $formatted_date = date('Y-m-d', strtotime($reported_time));
    $formatted_time = date('h:i A', strtotime($reported_time));

    $html .= '<tr>
                <td>' . htmlspecialchars($row['incident_id']) . '</td>
                <td>' . htmlspecialchars($row['incident_type']) . '</td>
                <td>' . htmlspecialchars($row['severity'] ?? 'Not Specified') . '</td>
                <td>' . htmlspecialchars($row['barangay_name']) . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row['address'] ?? 'N/A') . '</td>
                <td>' . htmlspecialchars($row['reported_by'] ?? 'Unknown') . '</td>
                <td>' . htmlspecialchars($formatted_date) . '</td>
                <td>' . htmlspecialchars($formatted_time) . '</td>
                <td style="text-align: left;">' . htmlspecialchars($row['cause'] ?? 'Not specified') . '</td>
              </tr>';
}

$html .= '</table>';

// Write content to PDF
$pdf->writeHTML($html, true, false, true, false, '');

// Add Fire Severity Levels Below the Table
$pdf->Ln(10);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, 'Fire Severity Levels', 0, 1, 'C');
$pdf->SetFont('Helvetica', '', 11);
$pdf->MultiCell(0, 8, "1. First Alarm (Minor Fire)\nSmall-scale fire that can be controlled using basic firefighting equipment.", 0, 'L', false, 1);
$pdf->MultiCell(0, 8, "2. Second Alarm (Moderate Fire)\nFire spreads beyond the point of origin but remains controllable.", 0, 'L', false, 1);
$pdf->MultiCell(0, 8, "3. Third Alarm (Major Fire)\nFire spreads extensively and threatens multiple structures.", 0, 'L', false, 1);
$pdf->MultiCell(0, 8, "4. Fourth Alarm (Severe Fire)\nLarge-scale fire that engulfs buildings or communities.", 0, 'L', false, 1);
$pdf->MultiCell(0, 8, "5. Fifth Alarm & General Alarm (Conflagration)\nFire is out of control and has reached disaster-level severity.\nMass evacuation is required.", 0, 'L', false, 1);

// Signature Section
$pdf->Ln(15);
$pdf->SetFont('Helvetica', 'B', 12);
$pdf->Cell(0, 10, 'FSUPT GERARD A VENEZUELA', 0, 1, 'R');
$pdf->Cell(0, 10, 'Bureau of Fire Protection', 0, 1, 'R');

// Close connection
$conn->close();

// Output PDF to browser (force download)
$pdf->Output('Analysis Records and Data.pdf', 'D');
?>