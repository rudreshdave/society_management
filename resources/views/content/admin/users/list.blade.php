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

  .sortable-ghost {
    opacity: 0.4;
  }
</style>
@endsection

@section('content')
<div class="card">


  {{-- Filters --}}
  <div class="row g-2  mt-2 d-flex align-items-center">
    <div class="col-md-3">
      <select id="roleFilter" class="form-select">
        <option value="">All Roles</option>
        @foreach($roles as $key => $role)
        <option value="{{ $key }}" {{ request('role_id') == $key ? 'selected' : '' }}>
          {{ $role }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <select id="societyFilter" class="form-select">
        <option value="">All Societies</option>
        @foreach($societies as $index => $society)
        <option value="{{ $index }}" {{ request('society_id') == $index ? 'selected' : '' }}>
          {{ $society }}
        </option>
        @endforeach
      </select>
    </div>

    <div class="col-md-2 d-flex gap-2">
      <button class="btn btn-primary btn-sm" onclick="applyFilters()">
        Apply
      </button>

      <button class="btn btn-outline-secondary btn-sm" onclick="resetFilters()">
        Reset
      </button>
    </div>
  </div>

  <div class="card-datatable table-responsive">
    <table class="table table-bordered table-hover users-datatable " id="usersTable">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Mobile</th>
          <th>Status</th>
          <th width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        @if(isset($users) && !empty($users))
        @foreach($users as $key => $user)
        <tr>
          <td>{{ $key+1 }}</td>
          <td>{{ $user->name }}</td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->mobile }}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm dropdown-toggle badge bg-label-{{ 
      $user->status == 1 ? 'success' : 
      ($user->status == 2 ? 'warning' : 'danger') 
    }}"
                data-bs-toggle="dropdown">
                {{ $user->status_label }}
              </button>

              <ul class="dropdown-menu">
                <li><a class="dropdown-item" onclick="changeStatus({{ $user->id }},1)">Active</a></li>
                <li><a class="dropdown-item" onclick="changeStatus({{ $user->id }},2)">Inactive</a></li>
                <li><a class="dropdown-item" onclick="changeStatus({{ $user->id }},3)">Block</a></li>
              </ul>
            </div>
          </td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item text-danger" href="javascript:void(0)" onclick="deleteUser({{ $user->id }})">
                  <i class="bx bx-trash me-1"></i> Delete
                </a>
              </div>
            </div>
          </td>
        </tr>
        @endforeach
        @else
        <tr>
          <td colspan="6" class="text-center text-muted">
            No users found
          </td>
        </tr>
        @endif
      </tbody>
    </table>
  </div>
</div>
@endsection

@include('content/admin/users/user')

@section('page-script')
@vite([
'resources/js/app.js',
'resources/js/user.js'
])

@endsection