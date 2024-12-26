import '../bootstrap';
import '../utils';
import jQuery from 'jquery';
import axios from 'axios';

// SweetAlert2 import and configuration
import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/src/sweetalert2.scss';
window.Swal = Swal.mixin({});

// Assign jQuery to the global window object
window.$ = jQuery;

// Laravel message handling
document.addEventListener('DOMContentLoaded', () => {
    const { Laravel } = window;

    if (Laravel) {
        if (Laravel.success) {
            Swal.fire({
                icon: 'success',
                title: Laravel.success.title,
                text: Laravel.success.text,
            });
        } else if (Laravel.error) {
            Swal.fire({
                icon: 'error',
                title: Laravel.error.title,
                text: Laravel.error.text,
            });
        }
    }

    // jQuery DOM manipulation
    $(function () {
        // Active menu item logic
        $('aside nav ul').on('click', 'li a', function () {
            $('aside nav ul li a').removeClass('active-menu-item');
            $(this).addClass('active-menu-item');
        });
    });
});
