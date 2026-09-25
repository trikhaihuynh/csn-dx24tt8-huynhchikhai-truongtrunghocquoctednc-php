(function () {
    'use strict';

    var navToggle = document.querySelector('.nav-toggle');
    var siteNav = document.getElementById('site-nav');
    if (navToggle && siteNav) {
        navToggle.addEventListener('click', function () {
            var isOpen = siteNav.classList.toggle('is-open');
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            navToggle.setAttribute('aria-label', isOpen ? 'Đóng menu' : 'Mở menu');
        });
    }

    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!window.confirm(form.getAttribute('data-confirm'))) {
                event.preventDefault();
            }
        });
    });

    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var preview = document.querySelector(input.getAttribute('data-preview'));
            var file = input.files && input.files[0];
            if (!preview || !file || file.type.indexOf('image/') !== 0) {
                return;
            }
            preview.src = URL.createObjectURL(file);
            preview.hidden = false;
        });
    });
})();
