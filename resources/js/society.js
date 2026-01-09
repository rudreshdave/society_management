document.addEventListener('DOMContentLoaded', function () {

  /* ==========================
     DATATABLE
  ========================== */
  $('#usersTable').DataTable({
    responsive: true,
    pageLength: 10
  });

});

/* ==========================
   GLOBAL STATE
========================== */
let selectedFiles = [];        // new uploads
let existingLogos = [];        // [{id, sort_order}]
let removedLogos = [];
let sortableInstance = null;

/* ==========================
   ELEMENTS
========================== */
const previewContainer = document.getElementById('previewContainer');
const logoInput = document.getElementById('logoInput');
const browseFiles = document.getElementById('browseFiles');
const dropArea = document.getElementById('dropArea');
const attachmentsSortInput = document.getElementById('attachments_sort');
const removedLogosInput = document.getElementById('removed_logos');

/* ==========================
   OFFCANVAS
========================== */
window.openAddSociety = function () {

  document.getElementById('societyForm').reset();

  selectedFiles = [];
  existingLogos = [];
  removedLogos = [];

  previewContainer.innerHTML = '';
  attachmentsSortInput.value = '';
  removedLogosInput.value = '';

  new bootstrap.Offcanvas('#societyOffcanvas').show();
};

window.openEditSociety = function (society) {

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

  loadExistingLogos(society.attachments);

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
          `<option value="${index}" ${selected}>${city}</option>`
        );
      });
      cityDropdown.prop('disabled', false);
    }
  });
  document.getElementById('city_id').value = selectedCityId;
}
/* ==========================
   FILE UPLOAD
========================== */
browseFiles.addEventListener('click', () => logoInput.click());
logoInput.addEventListener('change', handleFiles);

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

function handleFiles(e) {
  [...e.target.files].forEach(file => {
    if (file.type.startsWith('image/')) {
      selectedFiles.push(file);
    }
  });

  renderPreviews();
  syncInputFiles();
}

/* ==========================
   LOAD EXISTING IMAGES
========================== */
function loadExistingLogos(logos) {

  previewContainer.innerHTML = '';
  existingLogos = [];

  // Ensure correct initial order
  logos.sort((a, b) => a.sort_order - b.sort_order);

  logos.forEach((logo, index) => {

    existingLogos.push({
      id: logo.id,
      sort_order: index + 1
    });

    previewContainer.insertAdjacentHTML('beforeend', `
      <div class="preview-box"
           data-type="existing"
           data-id="${logo.id}">
        <img src="${logo.file_url}">
        <button type="button"
                class="remove-btn"
                onclick="removeExistingLogo(${logo.id}, this)">
          &times;
        </button>
      </div>
    `);
  });

  initSortable();
  syncAttachmentsSort();
}

/* ==========================
   RENDER NEW IMAGES
========================== */
function renderPreviews() {

  // Preserve existing images
  const existingHTML = [...previewContainer.querySelectorAll('[data-type="existing"]')]
    .map(el => el.outerHTML)
    .join('');

  previewContainer.innerHTML = existingHTML;

  selectedFiles.forEach((file, index) => {
    const reader = new FileReader();
    reader.onload = () => {
      previewContainer.insertAdjacentHTML('beforeend', `
        <div class="preview-box"
             data-type="new"
             data-index="${index}">
          <img src="${reader.result}">
          <button type="button"
                  class="remove-btn"
                  onclick="removeImage(${index})">&times;</button>
        </div>
      `);
      initSortable();
    };
    reader.readAsDataURL(file);
  });
}

/* ==========================
   SORTABLE
========================== */
function initSortable() {

  if (sortableInstance) sortableInstance.destroy();

  sortableInstance = new Sortable(previewContainer, {
    animation: 150,
    draggable: '.preview-box',
    ghostClass: 'sortable-ghost',
    onEnd: syncOrderFromDOM
  });
}

/* ==========================
   SYNC ORDER → sort_order
========================== */
function syncOrderFromDOM() {

  let newFiles = [];
  let order = 1;

  previewContainer.querySelectorAll('.preview-box').forEach(box => {

    if (box.dataset.type === 'existing') {
      const logo = existingLogos.find(l => l.id == box.dataset.id);
      if (logo) {
        logo.sort_order = order++;
      }
    }

    if (box.dataset.type === 'new') {
      const idx = box.dataset.index;
      if (selectedFiles[idx]) {
        newFiles.push(selectedFiles[idx]);
      }
    }
  });

  selectedFiles = newFiles;
  syncInputFiles();
  syncAttachmentsSort();
}

/* ==========================
   REMOVE FUNCTIONS (GLOBAL)
========================== */
window.removeImage = function (index) {
  selectedFiles.splice(index, 1);
  renderPreviews();
  syncInputFiles();
};

window.removeExistingLogo = function (id, el) {

  removedLogos.push(id);
  removedLogosInput.value = removedLogos.join(',');

  existingLogos = existingLogos.filter(l => l.id != id);

  // reassign sort_order
  existingLogos.forEach((l, i) => l.sort_order = i + 1);
  syncAttachmentsSort();

  el.closest('.preview-box').remove();
};

/* ==========================
   SYNC INPUTS
========================== */
function syncInputFiles() {
  const dt = new DataTransfer();
  selectedFiles.forEach(file => dt.items.add(file));
  logoInput.files = dt.files;
}

function syncAttachmentsSort() {
  if (!attachmentsSortInput) return;
  attachmentsSortInput.value = JSON.stringify(existingLogos);
}
