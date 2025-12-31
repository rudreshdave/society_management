document.addEventListener('DOMContentLoaded', function () {

  const table = $('#usersTable').DataTable({
    responsive: true,
    pageLength: 10,
    drawCallback: function () {
      forceSquarePagination();
    }
  });

  forceSquarePagination();
});

/**
 * Force square pagination (override Bootstrap 5)
 */
function forceSquarePagination() {

  document.querySelectorAll('.dataTables_wrapper .pagination .page-item')
    .forEach(function (item) {

      // remove bootstrap rounded styles
      item.classList.remove('rounded', 'rounded-pill');

      const link = item.querySelector('.page-link');
      if (!link) return;

      link.classList.remove('rounded', 'rounded-pill');

      // FORCE square shape
      link.style.setProperty('border-radius', '0px', 'important');
      link.style.setProperty('min-width', '36px', 'important');
      link.style.setProperty('height', '36px', 'important');
      link.style.setProperty('line-height', '34px', 'important');
      link.style.setProperty('padding', '0', 'important');
      link.style.setProperty('text-align', 'center', 'important');
      link.style.setProperty('font-weight', '500', 'important');
    });

  // Active state (Sneat color)
  document.querySelectorAll('.dataTables_wrapper .pagination .page-item.active .page-link')
    .forEach(function (el) {
      el.style.setProperty('background-color', '#696cff', 'important');
      el.style.setProperty('border-color', '#696cff', 'important');
      el.style.setProperty('color', '#fff', 'important');
    });
}
