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

    var lightboxLinks = document.querySelectorAll('a[data-lightbox]');
    if (lightboxLinks.length > 0) {
        var lightbox = document.createElement('div');
        lightbox.className = 'lightbox';
        lightbox.hidden = true;
        lightbox.setAttribute('role', 'dialog');
        lightbox.setAttribute('aria-modal', 'true');
        lightbox.setAttribute('aria-label', 'Xem ảnh');
        lightbox.innerHTML = '<button type="button" class="lightbox__close" aria-label="Đóng">&times;</button>'
            + '<img class="lightbox__image" alt=""><p class="lightbox__caption"></p>';
        document.body.appendChild(lightbox);
        var lightboxImage = lightbox.querySelector('.lightbox__image');
        var lightboxCaption = lightbox.querySelector('.lightbox__caption');
        var closeButton = lightbox.querySelector('.lightbox__close');
        var lastTrigger = null;

        var closeLightbox = function () {
            lightbox.hidden = true;
            lightboxImage.removeAttribute('src');
            document.body.classList.remove('has-lightbox');
            if (lastTrigger) {
                lastTrigger.focus();
            }
        };

        lightboxLinks.forEach(function (link) {
            link.addEventListener('click', function (event) {
                event.preventDefault();
                lastTrigger = link;
                lightboxImage.src = link.getAttribute('href');
                lightboxImage.alt = link.getAttribute('data-caption') || '';
                lightboxCaption.textContent = link.getAttribute('data-caption') || '';
                lightbox.hidden = false;
                document.body.classList.add('has-lightbox');
                closeButton.focus();
            });
        });

        closeButton.addEventListener('click', closeLightbox);
        lightbox.addEventListener('click', function (event) {
            if (event.target === lightbox) {
                closeLightbox();
            }
        });
        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !lightbox.hidden) {
                closeLightbox();
            }
        });
    }
})();
