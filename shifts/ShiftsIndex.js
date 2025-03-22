document.addEventListener("DOMContentLoaded", function () {
  const toggleButton = document.getElementById("toggle-btn");
  const sidebar = document.getElementById("sidebar");

  if (toggleButton && sidebar) {
      toggleButton.addEventListener("click", toggleSidebar);
  }

  function toggleSidebar() {
      sidebar.classList.toggle("close");
      toggleButton.classList.toggle("rotate");
      closeAllSubMenus();
  }

  function toggleSubMenu(button) {
      if (!button.nextElementSibling.classList.contains("show")) {
          closeAllSubMenus();
      }

      button.nextElementSibling.classList.toggle("show");
      button.classList.toggle("rotate");

      if (sidebar.classList.contains("close")) {
          sidebar.classList.remove("close");
          toggleButton.classList.toggle("rotate");
      }
  }

  function closeAllSubMenus() {
      document.querySelectorAll("#sidebar .show").forEach((menu) => {
          menu.classList.remove("show");
          menu.previousElementSibling.classList.remove("rotate");
      });
  }

  document.querySelectorAll(".dropdown-btn").forEach((button) => {
      button.addEventListener("click", function () {
          toggleSubMenu(this);
      });
  });
});

// Function to edit the Time Out column
function editAttendance(attendanceId) {
    const newTimeOut = prompt("Enter the new Time Out (YYYY-MM-DD HH:MM:SS):");
    if (newTimeOut) {
        fetch(`AttendanceEdit.php?attendance_id=${attendanceId}&time_out=${newTimeOut}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Time Out updated successfully!");
                    location.reload(); // Refresh the page to reflect changes
                } else {
                    alert("Failed to update Time Out.");
                }
            })
            .catch(error => console.error('Error:', error));
    }
}

// Function to delete a row
function deleteAttendance(attendanceId) {
    if (confirm("Are you sure you want to delete this record?")) {
        fetch(`AttendanceDelete.php?attendance_id=${attendanceId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert("Record deleted successfully!");
                    location.reload(); // Refresh the page to reflect changes
                } else {
                    alert("Failed to delete record.");
                }
            })
            .catch(error => console.error('Error:', error));
    }
}
