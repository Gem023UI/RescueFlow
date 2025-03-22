<?php
// Start the session only if it hasn't been started yet
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['PersonnelID'])) {
    echo json_encode(['error' => 'User not logged in']);
    exit;
}

// Fetch the PersonnelID from the session
$personnelID = $_SESSION['PersonnelID'];

// Return the PersonnelID as JSON
echo json_encode(['PersonnelID' => $personnelID]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BFP NCR Taguig City</title>
  <link rel="stylesheet" href="">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery for AJAX -->
  <style>
    .floating-btn {
        width: 90px;
        height: 90px;
        background-color: black;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        color: white;
        position: fixed;
        right: 30px;
        bottom: 140px;
        border: none;
        cursor: pointer;
        z-index: 9999; /* Ensures it's on top of everything */
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); /* Optional: Adds shadow for better visibility */
    }

    /* Modal Styles */
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 10000; /* Ensure it's on top of everything */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
    }

    .modal-content {
        background-color: white;
        margin: 15% auto; /* Center the modal */
        padding: 20px;
        border-radius: 10px;
        width: 300px;
        text-align: center;
    }

    .modal-buttons {
        margin-top: 20px;
    }

    .modal-buttons button {
        padding: 10px 20px;
        margin: 0 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .modal-buttons button.yes {
        background-color: #4CAF50; /* Green */
        color: white;
    }

    .modal-buttons button.cancel {
        background-color: #f44336; /* Red */
        color: white;
    }
  </style>
</head>
<body>
    <!-- Floating Button -->
    <a href="#" class="floating-btn" onclick="showModal()">
        <svg xmlns="http://www.w3.org/2000/svg" height="50px" viewBox="0 -960 960 960" width="50px" fill="#FFFFFF">
            <path d="M440-120q-75 0-140.5-28T185-225q-49-49-77-114.5T80-480q0-75 28-140.5T185-735q49-49 114.5-77T440-840q21 0 40.5 2.5T520-830v82q-20-6-39.5-9t-40.5-3q-118 0-199 81t-81 199q0 118 81 199t199 81q118 0 199-81t81-199q0-11-1-20t-3-20h82q2 11 2 20v20q0 75-28 140.5T695-225q-49 49-114.5 77T440-120Zm112-192L400-464v-216h80v184l128 128-56 56Zm168-288v-120H600v-80h120v-120h80v120h120v80H800v120h-80Z"/>
        </svg>
    </a>

    <!-- Modal Dialog -->
    <div id="timeInModal" class="modal">
        <div class="modal-content">
            <p>Time In?</p>
            <div class="modal-buttons">
                <button class="yes" onclick="handleAttendance()">Yes</button>
                <button class="cancel" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Function to show the modal
        function showModal() {
            document.getElementById('timeInModal').style.display = 'block';
        }

        // Function to close the modal
        function closeModal() {
            document.getElementById('timeInModal').style.display = 'none';
        }

        // Function to handle attendance
        function handleAttendance() {
            // Close the modal
            closeModal();

            // Send an AJAX request to fetch the PersonnelID
            $.ajax({
                url: 'AttendanceFetch.php', // Update this path if needed
                method: 'GET',
                success: function(response) {
                    console.log('Response from AttendanceFetch.php:', response); // Debugging
                    // Parse the JSON response
                    const personnelID = JSON.parse(response).PersonnelID;

                    // Send another AJAX request to insert attendance
                    $.ajax({
                        url: 'AttendanceTimeIn.php', // Update this path if needed
                        method: 'POST',
                        data: { PersonnelID: personnelID },
                        success: function(response) {
                            console.log('Response from AttendanceTimeIn.php:', response); // Debugging
                            alert('Attendance recorded successfully!');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error recording attendance:', error); // Debugging
                            alert('Error recording attendance: ' + error);
                        }
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching PersonnelID:', error); // Debugging
                    alert('Error fetching PersonnelID: ' + error);
                }
            });
        }
    </script>

</body>
</html>