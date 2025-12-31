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
</style>
@endsection




@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Users List</h5>
    <a href="#" class="btn btn-primary btn-sm">
      <i class="bx bx-plus"></i> Add User
    </a>
  </div>

  <div class="card-datatable table-responsive">
    <table class="table table-bordered table-hover users-datatable " id="usersTable">
      <thead class="table-light">
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Created At</th>
          <th width="120">Action</th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $user)
        <tr>
          <td>{{ $user->id }}</td>
          <td>
            <div class="d-flex align-items-center">
              <div class="avatar avatar-sm me-2">
                <span class="avatar-initial rounded-circle bg-label-primary">
                  {{ strtoupper(substr($user->name, 0, 1)) }}
                </span>
              </div>
              <span>{{ $user->name }}</span>
            </div>
          </td>
          <td>{{ $user->email }}</td>
          <td>{{ $user->created_at->format('d M Y') }}</td>
          <td>
            <div class="dropdown">
              <button class="btn btn-sm btn-icon dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bx bx-dots-vertical-rounded"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                <a class="dropdown-item" href="#">
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

@section('page-script')
@vite([
'resources/js/app.js',
'resources/js/society.js'
])

@endsection