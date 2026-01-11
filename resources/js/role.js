document.addEventListener('DOMContentLoaded', function () {

  $.fn.dataTable.ext.errMode = 'none';
  /* ==========================
     DATATABLE
  ========================== */
  $('#rolesTable').DataTable({
    responsive: true,
    pageLength: 10,
    autoWidth: true, // allow columns to fit content
    columnDefs: [
      { targets: -1, orderable: false } // disable sorting for Action
    ]
  });

});

let sortableInstance = null;

/* ==========================
   OFFCANVAS
========================== */
window.openAddRole = function () {
  document.getElementById('roleForm').reset();

  new bootstrap.Offcanvas('#roleOffcanvas').show();
};

window.openEditRole = function (role) {
  console.log(role);
  document.getElementById('role_id').value = role.id;
  document.getElementById('name').value = role.name;
  document.getElementById('slug').value = role.slug;

  new bootstrap.Offcanvas('#roleOffcanvas').show();
}

window.deleteRole = function (id) {
  if (!id) {
    console.error('Role ID missing');
    return;
  }

  Swal.fire({
    title: 'Are you sure?',
    text: "This role will be deleted!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#696cff',
    cancelButtonColor: '#ff3e1d',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      $.ajax({
        url: `/roles/${id}`,   // ✅ Add the ID here
        type: 'DELETE',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF token
        },
        success: function (res) {
          Swal.fire('Deleted!', res.message ?? 'Role deleted successfully', 'success');
          location.reload(); // or use DataTable reload if applicable
        },
        error: function (xhr) {
          Swal.fire('Error', xhr.responseJSON?.message ?? 'Something went wrong', 'error');
        }
      });
    }
  });
}
