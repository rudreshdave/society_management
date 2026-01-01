@extends('layouts/contentNavbarLayout')

@section('title', 'Users List')


@section('page-style')
<style>
  /* Square pagination only for this table */
  #usersTable .pagination .page-link {
    border-radius: 0 !important;
    min-width: 36px;
    height: 36px;
    line-height: 34px;
    padding: 0;
    text-align: center;
    font-weight: 500;
  }

  /* Active page (Sneat primary) */
  .users-datatable .pagination .page-item.active .page-link {
    background-color: #696cff;
    border-color: #696cff;
    color: #fff;
  }

  #dropArea {
    border: 2px dashed #d9dee3;
    background: #f9fafb;
    transition: 0.3s;
  }

  #dropArea.dragover {
    background: #eef4ff;
    border-color: #696cff;
  }

  .preview-box {
    position: relative;
    width: 100px;
    height: 100px;
  }

  .preview-box img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #ddd;
  }

  .preview-box .remove-btn {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #ff3e1d;
    color: #fff;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 14px;
    border: none;
    cursor: pointer;
  }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Societies List</h5>
    <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="openAddSociety()">
      <i class="bx bx-plus"></i> Add Society
    </a>
  </div>

  <div class="card-datatable table-responsive">
    <table class="table table-bordered table-hover users-datatable " id="usersTable">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Register No.</th>
          <th>City</th>
          <th>Total Wings</th>
          <th>Total Flats</th>
          <th>Status</th>
          <th width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($societies as $key => $society)
        <tr>
          <td>{{ $key+1 }}</td>
          <td>{{ $society->society_name }}</td>
          <td>{{ $society->registration_no }}</td>
          <td>{{ $society->city->name }}</td>
          <td>{{ $society->total_wings ?? '-' }}</td>
          <td>{{ $society->total_flats ?? '-' }}</td>
          <?php if ($society->status == 1) { ?>
            <td><span class="badge bg-label-success" text-capitalized="">{{ $society->status_label ?? null }}</span></td>
          <?php } elseif ($society->status == 2) { ?>
            <td><span class="badge bg-label-danger" text-capitalized="">{{ $society->status_label ?? null }}</span></td>
          <?php } ?>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="javascript:void(0)" onclick='openEditSociety(@json($society))'>
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <a class="dropdown-item text-danger" href="#">
                  <i class="bx bx-trash me-1"></i> Delete
                </a>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endsection

@include('content/admin/societies/society')

@section('page-script')
@vite([
'resources/js/app.js',
'resources/js/society.js'
])

@endsection