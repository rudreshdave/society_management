@extends('layouts/contentNavbarLayout')

@section('title', 'Roles List')


@section('page-style')
<style>
  /* Square pagination only for this table */
  #rolesTable .pagination .page-link {
    border-radius: 0 !important;
    min-width: 36px;
    height: 36px;
    line-height: 34px;
    padding: 0;
    text-align: center;
    font-weight: 500;
  }

  /* Active page (Sneat primary) */
  .roles-datatable .pagination .page-item.active .page-link {
    background-color: #696cff;
    border-color: #696cff;
    color: #fff;
  }

  .roles-datatable th,
  .roles-datatable td {
    white-space: nowrap;
    /* content won't wrap */
    width: auto;
    /* allow auto width */
  }

  .roles-datatable {
    table-layout: auto;
    /* auto size based on content */
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

  .sortable-ghost {
    opacity: 0.4;
  }
</style>
@endsection

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Roles List</h5>
    <a href="javascript:void(0)" class="btn btn-primary btn-sm" onclick="openAddRole()">
      <i class="bx bx-plus"></i> Add Role
    </a>
  </div>

  <div class="card-datatable table-responsive">
    <table class="table table-bordered table-hover roles-datatable " id="rolesTable">
      <thead class="table-light">
        <tr>
          <th>No.</th>
          <th>Name</th>
          <th width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($roles as $key => $role)
        <tr>
          <td>{{ $key+1 }}</td>
          <td>{{ $role->name }}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="javascript:void(0)" onclick='openEditRole(@json($role))'>
                  <i class="bx bx-edit-alt me-1"></i> Edit
                </a>
                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteRole({{ $role->id }})">
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

@include('content/admin/roles/role')

@section('page-script')
@vite([
'resources/js/app.js',
'resources/js/role.js'
])

@endsection