<div class="offcanvas offcanvas-end" tabindex="-1" id="userOffcanvas">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title" id="userOffcanvasTitle">
      Add Role
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

    <form id="userForm" method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
      @csrf
      <input type="hidden" name="_method" id="formMethod" value="POST">
      <input type="hidden" name="id" id="user_id">

      <div class="mb-3">
        <label class="form-label">Name <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="name" name="name">
      </div>

      <div class="mb-3">
        <label class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" class="form-control" id="email" name="email">
      </div>

      <div class="mb-3">
        <label class="form-label">Mobile <span class="text-danger">*</span></label>
        <input type="mobile" class="form-control" id="mobile" name="mobile">
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