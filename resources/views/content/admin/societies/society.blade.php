<div class="offcanvas offcanvas-end" tabindex="-1" id="societyOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="societyOffcanvasTitle">
      Add Society
    </h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
  </div>

  <div class="offcanvas-body">

    {{-- Global Errors --}}
    @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
    @endif

    <form id="societyForm" method="POST" action="{{ isset($society) ? route('societies.update', $society->id) : route('societies.store') }}" enctype="multipart/form-data">
      @if(isset($society))
      @method('PUT')
      @endif
      @csrf

      <input type="hidden" name="id" id="society_id">

      <div class="mb-3">
        <label class="form-label">Society Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="society_name" name="society_name">
      </div>

      <div class="mb-3">
        <label class="form-label">Registration No <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="registration_no" name="registration_no">
      </div>

      <!-- Address Line 1 -->
      <div class="mb-3">
        <label class="form-label">Address Line 1 <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="address_line_1" name="address_line_1">
      </div>

      <!-- Address Line 2 (Optional) -->
      <div class="mb-3">
        <label class="form-label">Address Line 2</label>
        <input type="text" class="form-control" id="address_line_2" name="address_line_2">
      </div>

      <div class="mb-3">
        <label class="form-label">State <span class="text-danger">*</span></label>
        <select class="form-select" id="state_id" name="state_id" required>
          <option value="">Select State</option>
          @foreach($states as $key => $state)
          <option value="{{ $key }}">{{ $state }}</option>
          @endforeach
        </select>
      </div>

      <div class="mb-3">
        <label class="form-label">City <span class="text-danger">*</span></label>
        <select class="form-select" id="city_id" name="city_id" required disabled>
          <option value="">Select City</option>
        </select>
      </div>

      <!-- Pincode -->
      <div class="mb-3">
        <label class="form-label">Pincode <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="pincode" name="pincode" maxlength="6" required>
      </div>

      <!-- Contact Email -->
      <div class="mb-3">
        <label class="form-label">Contact Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="contact_email" name="contact_email" required>
      </div>

      <!-- Contact Mobile -->
      <div class="mb-3">
        <label class="form-label">Contact Mobile <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="contact_mobile" name="contact_mobile" maxlength="10" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Total Wings</label>
        <input type="number" class="form-control" id="total_wings" name="total_wings">
      </div>

      <div class="mb-3">
        <label class="form-label">Total Flats</label>
        <input type="number" class="form-control" id="total_flats" name="total_flats">
      </div>

      <!-- Society Logo (Optional) -->
      <div class="mb-3">
        <label class="form-label">Society Logo</label>

        <div id="dropArea" class="border rounded p-3 text-center">
          <p class="mb-2 text-muted">
            Drag & drop images here or
            <span class="text-primary fw-bold" style="cursor:pointer" id="browseFiles">
              browse
            </span>
          </p>

          <input type="file"
            id="logoInput"
            name="logos[]"
            accept="image/*"
            multiple
            hidden>

          <div id="previewContainer" class="d-flex flex-wrap gap-2 mt-3">
          </div>
        </div>

        <input type="hidden" name="attachments_sort" id="attachments_sort">
        <!-- Existing images (edit mode) -->
        <input type="hidden" name="removed_logos" id="removed_logos">
      </div>

      <div class="d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">
          Cancel
        </button>
        <button type="submit" class="btn btn-primary">
          Save
        </button>
      </div>
    </form>
  </div>
</div>