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
