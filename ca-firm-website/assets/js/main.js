(function () {
    const navToggle = document.querySelector('.nav-toggle');
    const nav = document.querySelector('.primary-nav');

    if (navToggle && nav) {
        navToggle.addEventListener('click', function () {
            const expanded = navToggle.getAttribute('aria-expanded') === 'true';
            navToggle.setAttribute('aria-expanded', String(!expanded));
            nav.classList.toggle('is-open');
        });
    }

    const searchInput = document.querySelector('[data-service-search]');
    const filterInput = document.querySelector('[data-service-filter]');
    const cards = Array.from(document.querySelectorAll('[data-service-card]'));

    function filterServices() {
        const query = searchInput ? searchInput.value.trim().toLowerCase() : '';
        const category = filterInput ? filterInput.value : '';

        cards.forEach(function (card) {
            const text = card.textContent.toLowerCase();
            const cardCategory = card.getAttribute('data-category') || '';
            const matchesQuery = query === '' || text.indexOf(query) !== -1;
            const matchesCategory = category === '' || cardCategory === category;
            card.style.display = matchesQuery && matchesCategory ? '' : 'none';
        });
    }

    if (searchInput) {
        searchInput.addEventListener('input', filterServices);
    }

    if (filterInput) {
        filterInput.addEventListener('change', filterServices);
    }

    function clearCloneFields(item) {
        item.querySelectorAll('input, textarea, select').forEach(function (field) {
            if (field.type === 'date') {
                field.value = new Date().toISOString().slice(0, 10);
            } else {
                field.value = '';
            }
        });
        const title = item.querySelector('.admin-item-head h3');
        if (title) {
            title.textContent = 'New item';
        }
    }

    function reindex(list) {
        const items = Array.from(list.querySelectorAll('[data-repeater-item]'));
        items.forEach(function (item, index) {
            item.querySelectorAll('[name]').forEach(function (field) {
                field.name = field.name.replace(/\[\d+\]/, '[' + index + ']');
            });
        });
    }

    document.querySelectorAll('[data-add-item]').forEach(function (button) {
        button.addEventListener('click', function () {
            const selector = button.getAttribute('data-add-item');
            const list = selector ? document.querySelector(selector) : null;
            if (!list) {
                return;
            }

            const source = list.querySelector('[data-repeater-item]');
            if (!source) {
                return;
            }

            const clone = source.cloneNode(true);
            clearCloneFields(clone);
            list.appendChild(clone);
            reindex(list);
        });
    });

    document.addEventListener('click', function (event) {
        const removeButton = event.target.closest('[data-remove-item]');
        if (!removeButton) {
            return;
        }

        const item = removeButton.closest('[data-repeater-item]');
        const list = item ? item.parentElement : null;
        if (!item || !list) {
            return;
        }

        if (list.querySelectorAll('[data-repeater-item]').length > 1) {
            item.remove();
            reindex(list);
        } else {
            clearCloneFields(item);
        }
    });
})();
