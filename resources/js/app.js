import './bootstrap';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import $ from 'jquery';
window.$ = window.jQuery = $;

// jQuery Validation
import 'jquery-validation';


// DataTables core
import DataTable from 'datatables.net-bs5';
import 'datatables.net-responsive-bs5';
import 'datatables.net-buttons-bs5';

$.fn.DataTable = DataTable;