document.addEventListener('DOMContentLoaded', function () {

  $.fn.dataTable.ext.errMode = 'none';
  /* ==========================
     DATATABLE
  ========================== */
  $('#usersTable').DataTable({
    responsive: true,
    pageLength: 10,
    autoWidth: true, // allow columns to fit content
    columnDefs: [
      { targets: -1, orderable: false } // disable sorting for Action
    ]
  });



});

window.applyFilters = function () {

  const roleId = document.getElementById('roleFilter').value;
  const societyId = document.getElementById('societyFilter').value;

  const params = new URLSearchParams(window.location.search);

  if (roleId) {
    params.set('role_id', roleId);
  } else {
    params.delete('role_id');
  }

  if (societyId) {
    params.set('society_id', societyId);
  } else {
    params.delete('society_id');
  }

  window.location.href = window.location.pathname + '?' + params.toString();
}

window.resetFilters = function () {
  document.getElementById('roleFilter').value = '';
  document.getElementById('societyFilter').value = '';
  console.log(window.location.search);
  const params = new URLSearchParams(window.location.search);
  window.location.href = window.location.pathname;
}

let sortableInstance = null;

/* ==========================
   OFFCANVAS
========================== */
window.openAddUser = function () {
  const form = document.getElementById('userForm');

  form.reset();

  // 🔥 FORCE STORE MODE
  form.action = '/users';
  document.getElementById('formMethod').value = 'POST';
  document.getElementById('user_id').value = '';

  new bootstrap.Offcanvas('#userOffcanvas').show();
};

window.openEditUser = function (user) {
  const form = document.getElementById('userForm');

  document.getElementById('user_id').value = user.id;
  document.getElementById('name').value = user.name;
  document.getElementById('email').value = user.email;
  document.getElementById('mobile').value = user.mobile;
  // document.getElementById('status').value = user.status;

  form.action = `/users/${user.id}`;
  document.getElementById('formMethod').value = 'PUT';

  new bootstrap.Offcanvas('#userOffcanvas').show();
}

window.deleteUser = function (id) {
  if (!id) {
    console.error('User ID missing');
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: "This user will be deleted!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#696cff',
    cancelButtonColor: '#ff3e1d',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      console.log("/users/" + id);
      $.ajax({
        url: `/users/${id}`,   // ✅ Add the ID here
        type: 'POST',
        data: {
          _token: $('meta[name="csrf-token"]').attr('content'),
          _method: 'DELETE'
        },
        success: function (res) {
          Swal.fire({
            title: 'Deleted!',
            text: res.message ?? 'Role deleted successfully',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then((result) => {
            if (result.isConfirmed) {
              location.reload();
            }
          });
          // location.reload(); // or use DataTable reload if applicable
        },
        error: function (xhr) {
          Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong', 'error');
        }
      });
    }
  });
}

window.changeStatus = function (id, status) {
  $.ajax({
    url: `/users/change-status`,
    type: 'POST',
    data: {
      _token: $('meta[name="csrf-token"]').attr('content'),
      id: id,
      status: status
    },
    success: function (res) {
      toastr.success(res.message);
      location.reload();
    },
    error: function () {
      toastr.error('Failed to update status');
    }
  });
};
