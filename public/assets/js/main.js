document.addEventListener('DOMContentLoaded', function () {

    /* -----------------------------------------
       Event date: past dates nahi chun sakte
       (har enquiry form me same date field hai)
    ----------------------------------------- */

    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const minDate = `${year}-${month}-${day}`;

    document.querySelectorAll('input[name="event_date"]').forEach(function (input) {
        input.min = minDate;
    });

    /* -----------------------------------------
       Blog category filter
    ----------------------------------------- */

    const blogPage = document.querySelector('.blog-page');

    if (blogPage) {

        const filterButtons = blogPage.querySelectorAll('.blog-filter-btn');
        const blogItems = blogPage.querySelectorAll('.blog-filter-item');

        if (filterButtons.length && blogItems.length) {

            filterButtons.forEach(function (button) {

                button.addEventListener('click', function () {

                    const selectedFilter = this.getAttribute('data-filter');

                    filterButtons.forEach(function (btn) {
                        btn.classList.remove('active');
                        btn.setAttribute('aria-selected', 'false');
                    });

                    this.classList.add('active');
                    this.setAttribute('aria-selected', 'true');

                    blogItems.forEach(function (item) {
                        const category = item.getAttribute('data-category');

                        if (selectedFilter === 'all' || category === selectedFilter) {
                            item.classList.remove('blog-filter-hidden');
                        } else {
                            item.classList.add('blog-filter-hidden');
                        }
                    });

                });

            });

        }

    }

});

/* -----------------------------------------
   Service page: "Book This Service" button
   enquiry modal me service pehle se select kar deta hai.
----------------------------------------- */

document.addEventListener('show.bs.modal', function (event) {
    const modal = event.target;

    if (!modal || modal.id !== 'enquiryModal') {
        return;
    }

    const service = event.relatedTarget
        ? event.relatedTarget.getAttribute('data-service')
        : null;

    if (!service) {
        return;
    }

    const select = modal.querySelector('select[name="service"]');

    if (!select) {
        return;
    }

    select.value = service;

    if (select.value !== service) {
        const option = Array.from(select.options).find(function (item) {
            return item.text.trim() === service.trim();
        });

        if (option) {
            select.value = option.value;
        }
    }
});

/* -----------------------------------------
   Homepage "Wedding & Event Decoration Gallery"
   responsive slider: arrows, dots, autoplay, swipe.
   Every slide is rendered from the database by Blade.
----------------------------------------- */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('[data-gallery-slider]').forEach(function (slider) {
        const viewport = slider.querySelector('.gallery-slider-viewport');
        const track = slider.querySelector('.gallery-slider-track');
        const slides = track ? Array.from(track.children) : [];
        const prevButton = slider.querySelector('[data-gallery-prev]');
        const nextButton = slider.querySelector('[data-gallery-next]');
        const dotsWrap = slider.querySelector('[data-gallery-dots]');

        if (!viewport || !track || !slides.length || !dotsWrap) {
            return;
        }

        const interval = parseInt(slider.getAttribute('data-autoplay') || '0', 10);
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        let index = 0;
        let maxIndex = 0;
        let timer = null;
        let touchStartX = null;

        function gapSize() {
            const styles = window.getComputedStyle(track);
            return parseFloat(styles.columnGap || styles.gap || '0') || 0;
        }

        function stepSize() {
            return slides[0].getBoundingClientRect().width + gapSize();
        }

        function renderDots() {
            dotsWrap.innerHTML = '';

            if (maxIndex < 1) {
                dotsWrap.hidden = true;
                return;
            }

            dotsWrap.hidden = false;

            for (let dotIndex = 0; dotIndex <= maxIndex; dotIndex += 1) {
                const dot = document.createElement('button');
                dot.type = 'button';
                dot.className = 'gallery-slider-dot';
                dot.setAttribute('role', 'tab');
                dot.setAttribute('aria-label', 'Go to gallery page ' + (dotIndex + 1));
                dot.setAttribute('aria-selected', dotIndex === index ? 'true' : 'false');

                dot.addEventListener('click', function () {
                    index = dotIndex;
                    update(false);
                    restart();
                });

                dotsWrap.appendChild(dot);
            }
        }

        function updateDots() {
            Array.from(dotsWrap.children).forEach(function (dot, dotIndex) {
                dot.classList.toggle('is-active', dotIndex === index);
                dot.setAttribute('aria-selected', dotIndex === index ? 'true' : 'false');
            });
        }

        function update(animate) {
            track.style.transition = animate === false ? 'none' : '';
            track.style.transform = 'translate3d(-' + (index * stepSize()) + 'px, 0, 0)';
            updateDots();

            if (animate === false) {
                window.requestAnimationFrame(function () {
                    track.style.transition = '';
                });
            }
        }

        function measure() {
            const viewportWidth = viewport.clientWidth;
            const gap = gapSize();
            const slideWidth = slides[0].getBoundingClientRect().width;
            const visible = Math.max(1, Math.round((viewportWidth + gap) / (slideWidth + gap)));

            maxIndex = Math.max(0, slides.length - visible);

            if (index > maxIndex) {
                index = maxIndex;
            }

            renderDots();
            update(false);
        }

        function go(direction) {
            if (maxIndex < 1) {
                return;
            }

            index += direction;

            if (index < 0) {
                index = maxIndex;
            }

            if (index > maxIndex) {
                index = 0;
            }

            update(true);
        }

        function stop() {
            if (timer) {
                window.clearInterval(timer);
                timer = null;
            }
        }

        function start() {
            stop();

            if (interval >= 1000 && !reduceMotion && !document.hidden) {
                timer = window.setInterval(function () {
                    go(1);
                }, interval);
            }
        }

        function restart() {
            start();
        }

        if (prevButton) {
            prevButton.addEventListener('click', function () {
                go(-1);
                restart();
            });
        }

        if (nextButton) {
            nextButton.addEventListener('click', function () {
                go(1);
                restart();
            });
        }

        slider.addEventListener('mouseenter', stop);
        slider.addEventListener('mouseleave', start);
        slider.addEventListener('focusin', stop);
        slider.addEventListener('focusout', function (event) {
            if (!slider.contains(event.relatedTarget)) {
                start();
            }
        });

        document.addEventListener('visibilitychange', function () {
            if (document.hidden) {
                stop();
            } else {
                start();
            }
        });

        let resizeTimer = null;
        window.addEventListener('resize', function () {
            window.clearTimeout(resizeTimer);
            resizeTimer = window.setTimeout(measure, 150);
        });

        viewport.addEventListener('touchstart', function (event) {
            touchStartX = event.touches[0].clientX;
            stop();
        }, { passive: true });

        viewport.addEventListener('touchend', function (event) {
            if (touchStartX === null) {
                return;
            }

            const distance = event.changedTouches[0].clientX - touchStartX;
            touchStartX = null;

            if (Math.abs(distance) > 40) {
                go(distance < 0 ? 1 : -1);
            }

            start();
        }, { passive: true });

        measure();
        start();
    });
});

/* -----------------------------------------
   Homepage hero slider pagination.
   Bootstrap changes the slide, but the custom vertical pagination needs its
   own active-state sync so the highlighted dot always matches the banner.
----------------------------------------- */

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('#heroSlider').forEach(function (carousel) {
        const buttons = Array.from(carousel.querySelectorAll('.hero-pagination [data-bs-slide-to]'));

        if (!buttons.length) {
            return;
        }

        function setActive(index) {
            buttons.forEach(function (button, buttonIndex) {
                const isActive = buttonIndex === index;
                button.classList.toggle('active', isActive);

                if (isActive) {
                    button.setAttribute('aria-current', 'true');
                } else {
                    button.removeAttribute('aria-current');
                }
            });
        }

        carousel.addEventListener('slide.bs.carousel', function (event) {
            setActive(event.to);
        });

        buttons.forEach(function (button, buttonIndex) {
            button.addEventListener('click', function () {
                setActive(buttonIndex);
            });
        });

        setActive(0);
    });
});
