/* =========================================================
   PREMIUM REDESIGN BEHAVIOUR
   1. Continuous decoration gallery marquee with hover slowdown.
   2. Service-area search filter (service areas component).
   3. Locations directory search filter.
   ========================================================= */

document.addEventListener('DOMContentLoaded', function () {

    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* -----------------------------------------
       Continuous gallery marquee
    ----------------------------------------- */

    document.querySelectorAll('[data-marquee-gallery]').forEach(function (marquee) {

        var track = marquee.querySelector('.loc-gallery-track');

        if (!track || reduceMotion) {
            return;
        }

        var baseSpeed = parseFloat(marquee.getAttribute('data-speed') || '0.38');
        var hoverSpeed = parseFloat(marquee.getAttribute('data-hover-speed') || '0.12');

        if (!isFinite(baseSpeed) || baseSpeed <= 0) {
            baseSpeed = 0.38;
        }

        if (!isFinite(hoverSpeed) || hoverSpeed < 0) {
            hoverSpeed = baseSpeed / 3;
        }

        var speed = baseSpeed;
        var offset = 0;
        var distance = 0;
        var running = true;
        var lastTime = 0;

        function measure() {
            distance = track.scrollWidth / 2;

            if (offset > distance) {
                offset = distance > 0 ? offset % distance : 0;
            }

            if (distance > 0) {
                track.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';
            }
        }

        function frame(time) {
            if (!running) {
                return;
            }

            if (!lastTime) {
                lastTime = time;
            }

            var delta = Math.min(time - lastTime, 64);
            lastTime = time;

            if (distance > 0 && !document.hidden) {
                offset += speed * (delta / 16.6667);

                if (offset >= distance) {
                    offset -= distance;
                }

                track.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';
            }

            window.requestAnimationFrame(frame);
        }

        marquee.addEventListener('mouseenter', function () {
            speed = hoverSpeed;
        });

        marquee.addEventListener('mouseleave', function () {
            speed = baseSpeed;
        });

        marquee.addEventListener('focusin', function () {
            speed = hoverSpeed;
        });

        marquee.addEventListener('focusout', function () {
            speed = baseSpeed;
        });

        if ('ResizeObserver' in window) {
            var observer = new ResizeObserver(measure);
            observer.observe(track);
        }

        window.addEventListener('load', measure);
        window.addEventListener('resize', measure);

        measure();
        window.requestAnimationFrame(frame);
    });

    /* -----------------------------------------
       Generic text filter
    ----------------------------------------- */

    function setupFilter(options) {

        var input = document.querySelector(options.input);
        var count = options.count ? document.querySelector(options.count) : null;
        var empty = options.empty ? document.querySelector(options.empty) : null;
        var items = Array.prototype.slice.call(document.querySelectorAll(options.item));

        if (!input || !items.length) {
            return;
        }

        var total = items.length;

        function updateCount(visible, filtered) {
            if (!count) {
                return;
            }

            count.textContent = filtered
                ? visible + ' of ' + total
                : total + ' areas';
        }

        function applyFilter() {

            var term = input.value.trim().toLowerCase();
            var visible = 0;

            items.forEach(function (item) {
                var haystack = item.getAttribute(options.nameAttribute) || item.textContent || '';
                var matches = term === '' || haystack.toLowerCase().indexOf(term) !== -1;

                item.hidden = !matches;

                if (matches) {
                    visible += 1;
                }
            });

            (options.groups || []).forEach(function (selector) {
                document.querySelectorAll(selector).forEach(function (group) {
                    var childSelector = options.item + ':not([hidden])';
                    group.hidden = !group.querySelector(childSelector);
                });
            });

            if (empty) {
                empty.hidden = visible !== 0;
            }

            updateCount(visible, term !== '');
        }

        input.addEventListener('input', applyFilter);
        input.addEventListener('search', applyFilter);

        applyFilter();
    }

    setupFilter({
        input: '[data-area-filter]',
        item: '.sa-card',
        groups: ['.sa-state'],
        count: '[data-area-count]',
        nameAttribute: 'data-area-name'
    });

    setupFilter({
        input: '[data-location-filter]',
        item: '.locations-city-card',
        groups: ['.locations-localities-block', '.locations-district-block', '.locations-state-block'],
        count: '[data-location-count]',
        empty: '[data-location-empty]',
        nameAttribute: 'data-location-name'
    });

});
