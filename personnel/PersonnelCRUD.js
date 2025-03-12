function openModal(modalId) {
  // Ensure modal is visible before adding 'show'
  const modal = document.getElementById(modalId);
  modal.style.display = 'block';
  requestAnimationFrame(() => {
    modal.classList.add('show');
  });
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  modal.classList.remove('show');
  // Wait for CSS transition to finish, then hide
  setTimeout(() => {
    modal.style.display = 'none';
  }, 300);
}

function closeOutside(event, modalId) {
  // If user clicks the semi-transparent background, close the modal
  if (event.target.classList.contains('modal')) {
    closeModal(modalId);
  }
}
//Add Modal
$(document).ready(function() {

  // Fetch Offices and Populate Dropdown
  $.ajax({
    url: 'FetchOffice.php',
    method: 'GET',
    dataType: 'json',
    success: function(data) {
      var officeSelect = $('#officeId');
      data.forEach(function(office) {
        officeSelect.append(new Option(office.name, office.id));
      });
    },
    error: function(xhr, status, error) {
      console.error('Error fetching offices:', error);
    }
  });

  // Fetch Positions Based on Selected Office
  $('#officeId').on('change', function() {
    var officeId = $(this).val();
    var positionSelect = $('#positionId');
    positionSelect.empty().append(new Option('Select a Position', ''));

    if (officeId) {
      $.ajax({
        url: 'FetchPosition.php',
        method: 'GET',
        data: { OfficeID: officeId },
        dataType: 'json',
        success: function(data) {
          data.forEach(function(position) {
            positionSelect.append(new Option(position.name, position.id));
          });
        },
        error: function(xhr, status, error) {
          console.error('Error fetching positions:', error);
        }
      });
    }
  });

  // Handle Form Submission
  $('#addPersonnelForm').on('submit', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({
      url: 'PersonnelCreate.php',
      method: 'POST',
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        alert(response);
        closeModal('addPersonnelModal');  // Close modal after successful insertion
      },
      error: function(xhr, status, error) {
        console.error('Error adding personnel:', error);
      }
    });
  });

});
