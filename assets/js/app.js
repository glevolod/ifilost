/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import 'admin-lte/plugins/fontawesome-free/css/all.min.css'
import 'admin-lte/plugins/daterangepicker/daterangepicker.css'
import 'admin-lte/plugins/icheck-bootstrap/icheck-bootstrap.min.css'
import 'admin-lte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css'
import 'admin-lte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css'
import 'admin-lte/plugins/select2/css/select2.min.css'
import 'admin-lte/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css'
import 'admin-lte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css'
import 'admin-lte/dist/css/adminlte.css'
import '../css/app.css';


// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
import $ from 'jquery';
window.$ = $;
import moment from 'moment'
moment.locale(navigator.language);
window.moment = moment;
require('admin-lte/plugins/bootstrap/js/bootstrap.bundle')
require('admin-lte/plugins/select2/js/select2.full')
require('admin-lte/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min')
require('moment/moment')
require('admin-lte/plugins/inputmask/jquery.inputmask.min')
require('admin-lte/plugins/daterangepicker/daterangepicker')
require('admin-lte/plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min')
require('admin-lte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min')
require('admin-lte/plugins/bootstrap-switch/js/bootstrap-switch.min')
require('admin-lte/dist/js/adminlte')
require('./all-pages/datetimepickers')
