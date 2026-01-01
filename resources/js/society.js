document.addEventListener('DOMContentLoaded', function () {

  const table = $('#usersTable').DataTable({
    responsive: true,
    pageLength: 10,
    drawCallback: function () {
      forceSquarePagination();
    }
  });

  forceSquarePagination();
});

/**
 * Force square pagination (override Bootstrap 5)
 */
function forceSquarePagination() {

  document.querySelectorAll('.dataTables_wrapper .pagination .page-item')
    .forEach(function (item) {

      // remove bootstrap rounded styles
      item.classList.remove('rounded', 'rounded-pill');

      const link = item.querySelector('.page-link');
      if (!link) return;

      link.classList.remove('rounded', 'rounded-pill');

      // FORCE square shape
      link.style.setProperty('border-radius', '0px', 'important');
      link.style.setProperty('min-width', '36px', 'important');
      link.style.setProperty('height', '36px', 'important');
      link.style.setProperty('line-height', '34px', 'important');
      link.style.setProperty('padding', '0', 'important');
      link.style.setProperty('text-align', 'center', 'important');
      link.style.setProperty('font-weight', '500', 'important');
    });

  // Active state (Sneat color)
  document.querySelectorAll('.dataTables_wrapper .pagination .page-item.active .page-link')
    .forEach(function (el) {
      el.style.setProperty('background-color', '#696cff', 'important');
      el.style.setProperty('border-color', '#696cff', 'important');
      el.style.setProperty('color', '#fff', 'important');
    });
}

window.openAddSociety = function () {
  const form = document.getElementById('societyForm');
  if (!form) return;

  form.reset();
  document.getElementById('society_id').value = '';
  document.getElementById('societyOffcanvasTitle').innerText = 'Add Society';
  form.action = '/societies';

  new bootstrap.Offcanvas('#societyOffcanvas').show();
};

window.openEditSociety = function (society) {
  const form = document.getElementById('societyForm');
  if (!form) return;

  $('#societyOffcanvasTitle').text('Edit Society');
  $('#societyForm').attr('action', "{{ url('societies') }}/" + society.id);
  $('#formMethod').val('PUT');

  document.getElementById('societyOffcanvasTitle').innerText = 'Edit Society';
  document.getElementById('society_id').value = society.id;
  document.getElementById('society_name').value = society.society_name;
  document.getElementById('registration_no').value = society.registration_no;
  document.getElementById('address_line_1').value = society.address_line_1 ?? '';
  document.getElementById('total_wings').value = society.total_wings ?? '';
  document.getElementById('total_flats').value = society.total_flats ?? '';
  document.getElementById('state_id').value = society.state_id ?? '';
  document.getElementById('pincode').value = society.pincode ?? '';
  document.getElementById('contact_email').value = society.contact_email ?? '';
  document.getElementById('contact_mobile').value = society.contact_mobile ?? '';

  loadCities(society.state_id, society.city_id);

  loadExistingLogos(society.logos);

  form.action = `/societies/${society.id}`;

  new bootstrap.Offcanvas('#societyOffcanvas').show();
};

function loadCities(stateId, selectedCityId = null) {
  let cityDropdown = $('#city_id');
  cityDropdown.prop('disabled', true);
  cityDropdown.html('<option value="">Loading...</option>');

  if (!stateId) {
    cityDropdown.html('<option value="">Select City</option>');
    return;
  }

  $.ajax({
    url: `/api/cities/${stateId}`,
    type: "GET",
    success: function (data) {
      cityDropdown.empty().append('<option value="">Select City</option>');
      $.each(data.data, function (index, city) {
        let selected = selectedCityId == index ? 'selected' : '';
        cityDropdown.append(
          `<option value="${city.id}" ${selected}>${city}</option>`
        );
      });
      cityDropdown.prop('disabled', false);
    }
  });
}

let selectedFiles = [];
let removedLogos = [];

const dropArea = document.getElementById('dropArea');
const logoInput = document.getElementById('logoInput');
const previewContainer = document.getElementById('previewContainer');
const browseFiles = document.getElementById('browseFiles');
const removedLogosInput = document.getElementById('removed_logos');

/* Browse click */
browseFiles.addEventListener('click', () => logoInput.click());

/* File select */
logoInput.addEventListener('change', handleFiles);

/* Drag & Drop */
dropArea.addEventListener('dragover', e => {
  e.preventDefault();
  dropArea.classList.add('dragover');
});

dropArea.addEventListener('dragleave', () => {
  dropArea.classList.remove('dragover');
});

dropArea.addEventListener('drop', e => {
  e.preventDefault();
  dropArea.classList.remove('dragover');
  handleFiles({ target: { files: e.dataTransfer.files } });
});

/* Handle files */
function handleFiles(e) {
  [...e.target.files].forEach(file => {
    if (!file.type.startsWith('image/')) return;
    selectedFiles.push(file);
  });
  renderPreviews();
  updateInputFiles();
}

/* Render previews */
function renderPreviews() {
  previewContainer.innerHTML = '';

  selectedFiles.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = () => {
      previewContainer.innerHTML += `
        <div class="preview-box" draggable="true" data-index="${index}">
          <img src="${reader.result}">
          <button type="button" class="remove-btn" onclick="removeImage(${index})">&times;</button>
        </div>
      `;
    };
    reader.readAsDataURL(file);
  });
}

/* Remove image */
function removeImage(index) {
  selectedFiles.splice(index, 1);
  renderPreviews();
  updateInputFiles();
}

/* Update input files */
function updateInputFiles() {
  const dataTransfer = new DataTransfer();
  selectedFiles.forEach(file => dataTransfer.items.add(file));
  logoInput.files = dataTransfer.files;
}

function loadExistingLogos(logos) {
  logos.forEach(logo => {
    previewContainer.innerHTML += `
      <div class="preview-box" data-existing="${logo.id}">
        <img src="${logo.url}">
        <button type="button"
                class="remove-btn"
                onclick="removeExistingLogo(${logo.id}, this)">
          &times;
        </button>
      </div>
    `;
  });
}

function removeExistingLogo(id, el) {
  removedLogos.push(id);
  removedLogosInput.value = removedLogos.join(',');
  el.closest('.preview-box').remove();
}
