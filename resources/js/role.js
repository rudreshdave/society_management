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
  const form = document.getElementById('roleForm');

  form.reset();

  // 🔥 FORCE STORE MODE
  form.action = '/roles';
  document.getElementById('formMethod').value = 'POST';
  document.getElementById('role_id').value = '';

  new bootstrap.Offcanvas('#roleOffcanvas').show();
};

window.openEditRole = function (role) {
  const form = document.getElementById('roleForm');

  document.getElementById('role_id').value = role.id;
  document.getElementById('name').value = role.name;

  form.action = `/roles/${role.id}`;
  document.getElementById('formMethod').value = 'PUT';

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
      console.log("/roles/" + id);
      $.ajax({
        url: `/roles/${id}`,   // ✅ Add the ID here
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
