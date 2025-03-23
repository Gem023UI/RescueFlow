<?php
session_start();
include('../includes/config.php');

// Restrict if not Admin Function
$user_id = $_SESSION['user_id'] ?? null;
$role_id = $_SESSION['role'] ?? null; // Fetch RoleID from session

$categoryID = 7; // Change this value to filter by a different category

// SQL query to fetch assets with related details, filtering by assetcategory_id
$sql = "SELECT a.asset_id, a.asset_name, a.description, a.status, a.last_maintenance_date, ac.Category 
        FROM assets a
        JOIN asset_category ac ON a.assetcategory_id = ac.AssetCategory_ID
        WHERE a.assetcategory_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $categoryID);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BFP NCR Taguig City</title>
  <link rel="stylesheet" href="AssetIndex.css">
  <script type="text/javascript" src="AssetIndex.js" defer></script>
</head>
<body>
    <nav id="sidebar">
        <ul>
        <li>
            <span class="logo"><a href="../dashboard/RescueFlowIndex.php">BFP NCR Taguig S1</a></span>
            <button onclick=toggleSidebar() id="toggle-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="m313-480 155 156q11 11 11.5 27.5T468-268q-11 11-28 11t-28-11L228-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T468-692q11 11 11 28t-11 28L313-480Zm264 0 155 156q11 11 11.5 27.5T732-268q-11 11-28 11t-28-11L492-452q-6-6-8.5-13t-2.5-15q0-8 2.5-15t8.5-13l184-184q11-11 27.5-11.5T732-692q11 11 11 28t-11 28L577-480Z"/></svg>
            </button>
        </li>
        <li>
            <a href="../dashboard/RescueFlowIndex.php" >
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M240-200h120v-200q0-17 11.5-28.5T400-440h160q17 0 28.5 11.5T600-400v200h120v-360L480-740 240-560v360Zm-80 0v-360q0-19 8.5-36t23.5-28l240-180q21-16 48-16t48 16l240 180q15 11 23.5 28t8.5 36v360q0 33-23.5 56.5T720-120H560q-17 0-28.5-11.5T520-160v-200h-80v200q0 17-11.5 28.5T400-120H240q-33 0-56.5-23.5T160-200Zm320-270Z"/></svg>
            <span>Dashboard</span>
            </a>
        </li>
        <li>
            <a href="../reports/ReportsIndex.php" >
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-160v-80h64l79-263q8-26 29.5-41.5T420-560h120q26 0 47.5 15.5T617-503l79 263h64v80H200Zm148-80h264l-72-240H420l-72 240Zm92-400v-200h80v200h-80Zm238 99-57-57 142-141 56 56-141 142Zm42 181v-80h200v80H720ZM282-541 141-683l56-56 142 141-57 57ZM40-360v-80h200v80H40Zm440 120Z"/></svg>
            <span>Reports</span>
            </a>
        </li>
        <li>
            <a href="../incident/IncidentIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M240-400q0 52 21 98.5t60 81.5q-1-5-1-9v-9q0-32 12-60t35-51l113-111 113 111q23 23 35 51t12 60v9q0 4-1 9 39-35 60-81.5t21-98.5q0-50-18.5-94.5T648-574q-20 13-42 19.5t-45 6.5q-62 0-107.5-41T401-690q-39 33-69 68.5t-50.5 72Q261-513 250.5-475T240-400Zm240 52-57 56q-11 11-17 25t-6 29q0 32 23.5 55t56.5 23q33 0 56.5-23t23.5-55q0-16-6-29.5T537-292l-57-56Zm0-492v132q0 34 23.5 57t57.5 23q18 0 33.5-7.5T622-658l18-22q74 42 117 117t43 163q0 134-93 227T480-80q-134 0-227-93t-93-227q0-129 86.5-245T480-840Z"/></svg>
            <span>Incidents</span>
            </a>
        </li>
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M280-120q-50 0-85-35t-35-85h-40q-33 0-56.5-23.5T40-320v-200h440v-160q0-33 23.5-56.5T560-760h80v-40q0-17 11.5-28.5T680-840h40q17 0 28.5 11.5T760-800v40h22q26 0 47 15t29 40l58 172q2 6 3 12.5t1 13.5v267H800q0 50-35 85t-85 35q-50 0-85-35t-35-85H400q0 50-35 85t-85 35Zm0-80q17 0 28.5-11.5T320-240q0-17-11.5-28.5T280-280q-17 0-28.5 11.5T240-240q0 17 11.5 28.5T280-200Zm400 0q17 0 28.5-11.5T720-240q0-17-11.5-28.5T680-280q-17 0-28.5 11.5T640-240q0 17 11.5 28.5T680-200ZM120-440v120h71q17-19 40-29.5t49-10.5q26 0 49 10.5t40 29.5h111v-120H120Zm440 120h31q17-19 40-29.5t49-10.5q26 0 49 10.5t40 29.5h71v-120H560v120Zm0-200h276l-54-160H560v160ZM40-560v-60h40v-80H40v-60h400v60h-40v80h40v60H40Zm100-60h70v-80h-70v80Zm130 0h70v-80h-70v80Zm210 180H120h360Zm80 0h280-280Z"/></svg>
            <span>Assets</span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
            </button>
            <ul class="sub-menu">
          <div>
            <li><a href="../assets/FireTruck1.php">Fire Truck 1</a></li>
            <li><a href="../assets/FireTruck2.php">Fire Truck 2</a></li>
            <li><a href="../assets/FireTruck3.php">Fire Truck 3</a></li>
            <li><a href="../assets/FireTruck4.php">Fire Truck 4</a></li>
            <li><a href="../assets/EmergencyVehicle.php">Emergency Vehicle</a></li>
            <li><a href="../assets/Stationary.php">Stationary</a></li>
          </div>
        </ul>
        <li>
            <button onclick=toggleSubMenu(this) class="dropdown-btn">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M640-160v-280h160v280H640Zm-240 0v-640h160v640H400Zm-240 0v-440h160v440H160Z"/></svg>
            <span>Analysis</span>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#e8eaed"><path d="M480-361q-8 0-15-2.5t-13-8.5L268-556q-11-11-11-28t11-28q11-11 28-11t28 11l156 156 156-156q11-11 28-11t28 11q11 11 11 28t-11 28L508-372q-6 6-13 8.5t-15 2.5Z"/></svg>
            </button>
            <ul class="sub-menu">
            <div>
                <li><a href="../analysis/AnalysisCauses.php">Cause of Fire</a></li>
                <li><a href="../analysis/AnalysisHotspot.php">Fire Hotspot</a></li>
            </div>
            </ul>
        </li>
        </li>
        <li>
            <a href="../personnels/PersonnelIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="M440-280h320v-22q0-45-44-71.5T600-400q-72 0-116 26.5T440-302v22Zm160-160q33 0 56.5-23.5T680-520q0-33-23.5-56.5T600-600q-33 0-56.5 23.5T520-520q0 33 23.5 56.5T600-440ZM160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h240l80 80h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Zm0-80h640v-400H447l-80-80H160v480Zm0 0v-480 480Z"/></svg>
            <span>Personnels</span>
            </a>
        </li>
        <li>
            <a href="../training/TrainingIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#F19E39"><path d="m216-160-56-56 384-384H440v80h-80v-160h233q16 0 31 6t26 17l120 119q27 27 66 42t84 16v80q-62 0-112.5-19T718-476l-40-42-88 88 90 90-262 151-40-69 172-99-68-68-266 265Zm-96-280v-80h200v80H120ZM40-560v-80h200v80H40Zm739-80q-33 0-57-23.5T698-720q0-33 24-56.5t57-23.5q33 0 57 23.5t24 56.5q0 33-24 56.5T779-640Zm-659-40v-80h200v80H120Z"/></svg>
            <span>Training</span>
            </a>
        </li>
        <li>
            <a href="../shifts/ShiftsIndex.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M438-226 296-368l58-58 84 84 168-168 58 58-226 226ZM200-80q-33 0-56.5-23.5T120-160v-560q0-33 23.5-56.5T200-800h40v-80h80v80h320v-80h80v80h40q33 0 56.5 23.5T840-720v560q0 33-23.5 56.5T760-80H200Zm0-80h560v-400H200v400Zm0-480h560v-80H200v80Zm0 0v-80 80Z"/></svg>
            <span>Shifts</span>
            </a>
        </li>
        <li>
            <a href="../user/Logout.php">
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF"><path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z"/></svg>
            <span>Logout</span>
            </a>
        </li>
        </ul>
    </nav>
    <main>
    <h1 class="asset-header">ASSET MANAGEMENT</h1>
    <h2>Emergency Vehicle</h2>
    <?php if ($role_id == 4): // Only show for admin ?>
        <div class="admin-button">
            <a href="AssetCreate.php" class="add-button">ADD ASSET</a>
        </div>
    <?php endif; ?>
    <div class="asset-container">
    <?php while ($row = $result->fetch_assoc()): ?>
            <div class="asset-structure">
                <div class="asset-picture">
                    <?php
                        $asset_id = $row['asset_id'];
                        $img_query = "SELECT img_path FROM assets_image WHERE asset_id = $asset_id";
                        $img_result = $conn->query($img_query);
                        
                        while ($img_row = $img_result->fetch_assoc()) {
                            echo '<img src="../' . $img_row['img_path'] . '">';
                        }
                    ?>
                </div>
                <div class="asset-info">
                    <p class="asset-details">
                        <strong>Name:</strong> <?php echo htmlspecialchars($row['asset_name']); ?><br>
                        <strong>Description:</strong> <?php echo htmlspecialchars($row['description']); ?><br>
                        <strong>Status:</strong> <?php echo htmlspecialchars($row['status']); ?><br>
                        <strong>Previous Maintenance:</strong> <?php echo htmlspecialchars($row['last_maintenance_date']); ?><br>
                    </p>
                    <?php if ($role_id == 4): // Only show for admin ?>
                        <div class="asset-button">
                            <a class="edit-button" href="AssetEdit.php?id=<?php echo $row['asset_id']; ?>">Edit</a>
                            <a class="delete-button"href="AssetDelete.php?id=<?php echo $row['asset_id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
  </main>
</body>
</html>