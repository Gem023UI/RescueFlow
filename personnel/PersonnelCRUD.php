<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BFP NCR Taguig City</title>
  <link rel="stylesheet" href="PersonnelCRUD.css">
  <script type="text/javascript" src="PersonnelCRUD.js"></script>
</head>
<body>
  <!-- PDF Button -->
  <a href="javascript:void(0)" class="floating-btn-pdf" onclick="openModal('pdfModal')">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
      <path d="M360-460h40v-80h40q17 0 28.5-11.5T480-580v-40q0-17-11.5-28.5T440-660h-80v200Zm40-120v-40h40v40h-40Zm120 120h80q17 0 28.5-11.5T640-500v-120q0-17-11.5-28.5T600-660h-80v200Zm40-40v-120h40v120h-40Zm120 40h40v-80h40v-40h-40v-40h40v-40h-80v200ZM320-240q-33 0-56.5-23.5T240-320v-480q0-33 23.5-56.5T320-880h480q33 0 56.5 23.5T880-800v480q0 33-23.5 56.5T800-240H320Zm0-80h480v-480H320v480ZM160-80q-33 0-56.5-23.5T80-160v-560h80v560h560v80H160Zm160-720v480-480Z"/>
    </svg>
  </a>

  <!-- Add Button -->
  <a href="javascript:void(0)" class="floating-btn-add" onclick="openModal('addPersonnelModal')">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
      <path d="M440-440H200v-80h240v-240h80v240h240v80H520v240h-80v-240Z"/>
    </svg>
  </a>

  <!-- Edit Button -->
  <a href="javascript:void(0)" class="floating-btn-edit" onclick="openModal('editModal')">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
      <path d="M160-400v-80h280v80H160Zm0-160v-80h440v80H160Zm0-160v-80h440v80H160Zm360 560v-123l221-220q9-9 20-13t22-4q12 0 23 4.5t20 13.5l37 37q8 9 12.5 20t4.5 22q0 11-4 22.5T863-380L643-160H520Zm300-263-37-37 37 37ZM580-220h38l121-122-18-19-19-18-122 121v38Zm141-141-19-18 37 37-18-19Z"/>
    </svg>
  </a>

  <!-- Delete Button -->
  <a href="javascript:void(0)" class="floating-btn-delete" onclick="openModal('deleteModal')">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#FFFFFF">
      <path d="M280-120q-33 0-56.5-23.5T200-200v-520h-40v-80h200v-40h240v40h200v80h-40v520q0 33-23.5 56.5T680-120H280Zm400-600H280v520h400v-520ZM360-280h80v-360h-80v360Zm160 0h80v-360h-80v360ZM280-720v520-520Z"/>
    </svg>
  </a>

  <!-- PDF Modal -->
  <div id="pdfModal" class="modal" onclick="closeOutside(event, 'pdfModal')">
    <div class="modal-content">
      <span class="close" onclick="closeModal('pdfModal')">&times;</span>
      <h2>Generate PDF</h2>
      <p>Form for generating PDF goes here.</p>
    </div>
  </div>

  <!-- Add Personnel Modal -->
  <div id="addPersonnelModal" class="modal" onclick="closeOutside(event, 'addPersonnelModal')">
    <div class="modal-content">
      <span class="close" onclick="closeModal('addPersonnelModal')">&times;</span>
      <h2>Add Personnel</h2>
      <form id="addPersonnelForm" enctype="multipart/form-data">
        
        <!-- Office Dropdown -->
        <div class="mb-3">
          <label for="officeId" class="form-label">Office</label>
          <select class="form-select" id="officeId" name="OfficeID" required>
            <option value="">Select an Office</option>
          </select>
        </div>

        <!-- Position Dropdown (Changes Based on Office Selection) -->
        <div class="mb-3">
          <label for="positionId" class="form-label">Position</label>
          <select class="form-select" id="positionId" name="PositionID" required>
            <option value="">Select a Position</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="firstName" class="form-label">First Name</label>
          <input type="text" class="form-control" id="firstName" name="FirstName" required>
        </div>

        <div class="mb-3">
          <label for="lastName" class="form-label">Last Name</label>
          <input type="text" class="form-control" id="lastName" name="LastName" required>
        </div>

        <div class="mb-3">
          <label for="designated" class="form-label">Designated Date</label>
          <input type="datetime-local" class="form-control" id="designated" name="Designated" required>
        </div>

        <div class="mb-3">
          <label for="picture" class="form-label">Picture</label>
          <input type="file" class="form-control" id="picture" name="Picture" accept="image/*" required>
        </div>

        <button type="submit" class="btn btn-primary">Add Personnel</button>
      </form>
    </div>
  </div>


  <!-- Edit Modal -->
  <div id="editModal" class="modal" onclick="closeOutside(event, 'editModal')">
    <div class="modal-content">
      <span class="close" onclick="closeModal('editModal')">&times;</span>
      <h2>Edit Activity</h2>
      <p>Form for editing activity goes here.</p>
    </div>
  </div>

  <!-- Delete Modal -->
  <div id="deleteModal" class="modal" onclick="closeOutside(event, 'deleteModal')">
    <div class="modal-content">
      <span class="close" onclick="closeModal('deleteModal')">&times;</span>
      <h2>Delete Activity</h2>
      <p>Confirmation for deleting activity goes here.</p>
    </div>
  </div>
</body>
</html>
