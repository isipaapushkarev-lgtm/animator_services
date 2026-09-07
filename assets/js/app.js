(() => {
    'use strict';
    const toggle = document.querySelector('.menu-toggle');
    const nav = document.querySelector('.main-nav');
    if (toggle && nav) {
        toggle.addEventListener('click', () => {
            const open = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!open));
            nav.classList.toggle('is-open', !open);
        });
    }

    document.querySelectorAll('[data-phone]').forEach((input) => {
        input.addEventListener('input', () => {
            const digits = input.value.replace(/\D/g, '').replace(/^8/, '7').slice(0, 11);
            const d = digits.padEnd(11, '_');
            input.value = digits.length ? `+${d[0]} (${d.slice(1,4)}) ${d.slice(4,7)}-${d.slice(7,9)}-${d.slice(9,11)}`.replace(/[_()-]+\s*$/,'') : '';
        });
    });

    document.querySelectorAll('form[data-validate]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const password = form.querySelector('[name="password"]');
            const confirmation = form.querySelector('[name="password_confirmation"]');
            let message = '';
            form.querySelectorAll('[required]').forEach((field) => {
                if (!String(field.value).trim() && !message) message = 'Заполните все обязательные поля.';
            });
            if (password && password.value && !/^(?=.*[A-Za-z])(?=.*\d).{8,}$/.test(password.value)) {
                message = 'Пароль должен содержать минимум 8 символов, латинскую букву и цифру.';
            }
            if (confirmation && password && confirmation.value !== password.value) message = 'Пароли не совпадают.';
            if (message) {
                event.preventDefault();
                const box = form.querySelector('.form-message') || document.createElement('div');
                box.className = 'form-message alert alert--error'; box.textContent = message;
                form.prepend(box); box.scrollIntoView({behavior:'smooth', block:'center'});
            }
        });
    });

    document.querySelectorAll('[data-confirm]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!window.confirm(button.dataset.confirm || 'Подтвердить действие?')) event.preventDefault();
        });
    });

    window.YarkoMap = () => {
        const mapNode = document.getElementById('map');
        if (!mapNode || typeof L === 'undefined') return;
        const point = [55.7518, 37.6176];
        const map = L.map(mapNode, {
            scrollWheelZoom: false,
            zoomAnimation: false,
            fadeAnimation: false,
            markerZoomAnimation: false
        }).setView(point, 12);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            updateWhenIdle: true,
            keepBuffer: 6,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);
        L.marker(point).addTo(map).bindPopup('<b>ЯРКО! Night Show</b><br>Москва, ул. Праздничная, 12').openPopup();
        window.setTimeout(() => map.invalidateSize(false), 300);
    };
})();
