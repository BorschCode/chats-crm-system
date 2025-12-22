<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Catalog - Telegram Mini App</title>
    <script src="https://telegram.org/js/telegram-web-app.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: var(--tg-theme-bg-color, #ffffff);
            color: var(--tg-theme-text-color, #000000);
            padding: 0;
            margin: 0;
            overflow-x: hidden;
        }

        .container {
            max-width: 100%;
            padding: 16px;
        }

        .header {
            padding: 20px 16px;
            background: var(--tg-theme-secondary-bg-color, #f4f4f5);
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .header p {
            color: var(--tg-theme-hint-color, #999999);
            font-size: 14px;
        }

        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 16px;
            overflow-x: auto;
            padding-bottom: 8px;
        }

        .tab {
            padding: 10px 20px;
            border-radius: 20px;
            background: var(--tg-theme-secondary-bg-color, #f4f4f5);
            color: var(--tg-theme-text-color, #000000);
            border: none;
            cursor: pointer;
            white-space: nowrap;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .tab.active {
            background: var(--tg-theme-button-color, #3390ec);
            color: var(--tg-theme-button-text-color, #ffffff);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
            margin-bottom: 80px;
        }

        .card {
            background: var(--tg-theme-secondary-bg-color, #f4f4f5);
            border-radius: 12px;
            padding: 12px;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .card:active {
            transform: scale(0.98);
        }

        .card-image {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 8px;
            background: var(--tg-theme-bg-color, #ffffff);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            margin-bottom: 8px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card-title {
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .card-price {
            font-size: 16px;
            font-weight: 600;
            color: var(--tg-theme-button-color, #3390ec);
        }

        .card-description {
            font-size: 12px;
            color: var(--tg-theme-hint-color, #999999);
            margin-top: 4px;
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .loading {
            text-align: center;
            padding: 40px;
            color: var(--tg-theme-hint-color, #999999);
        }

        .spinner {
            border: 3px solid var(--tg-theme-secondary-bg-color, #f4f4f5);
            border-top: 3px solid var(--tg-theme-button-color, #3390ec);
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 0 auto 16px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .error {
            background: #fee;
            color: #c33;
            padding: 16px;
            border-radius: 8px;
            margin: 16px;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: var(--tg-theme-bg-color, #ffffff);
            z-index: 1000;
            overflow-y: auto;
            display: none;
        }

        .modal.active {
            display: block;
        }

        .modal-header {
            position: sticky;
            top: 0;
            background: var(--tg-theme-bg-color, #ffffff);
            padding: 16px;
            border-bottom: 1px solid var(--tg-theme-secondary-bg-color, #f4f4f5);
            z-index: 10;
        }

        .modal-content {
            padding: 16px;
        }

        .modal-image {
            width: 100%;
            aspect-ratio: 1;
            border-radius: 12px;
            background: var(--tg-theme-secondary-bg-color, #f4f4f5);
            margin-bottom: 16px;
            overflow: hidden;
        }

        .modal-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .modal-title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .modal-price {
            font-size: 28px;
            font-weight: 700;
            color: var(--tg-theme-button-color, #3390ec);
            margin-bottom: 16px;
        }

        .modal-description {
            font-size: 15px;
            line-height: 1.5;
            color: var(--tg-theme-text-color, #000000);
            margin-bottom: 16px;
        }

        .modal-group {
            font-size: 14px;
            color: var(--tg-theme-hint-color, #999999);
            padding: 8px 12px;
            background: var(--tg-theme-secondary-bg-color, #f4f4f5);
            border-radius: 8px;
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 id="welcomeText">🛍️ Catalog</h1>
            <p id="subText">Loading...</p>
        </div>

        <div class="tabs" id="tabs"></div>
        <div id="content" class="loading">
            <div class="spinner"></div>
            <p>Loading catalog...</p>
        </div>
    </div>

    <div class="modal" id="itemModal">
        <div class="modal-header">
            <button onclick="closeModal()" style="background: none; border: none; font-size: 18px; cursor: pointer;">← Back</button>
        </div>
        <div class="modal-content" id="modalContent"></div>
    </div>

    <script>
        // Initialize Telegram WebApp
        const tg = window.Telegram.WebApp;
        tg.ready();
        tg.expand();

        // Set header color
        tg.setHeaderColor('#ffffff');

        // Get user data
        const user = tg.initDataUnsafe.user;
        const initData = tg.initData;

        console.log('Telegram WebApp initialized', { user });

        // State
        let currentView = 'groups';
        let currentGroup = null;
        let groups = [];
        let items = [];

        // Update welcome text with user name
        if (user) {
            document.getElementById('welcomeText').textContent = `Hello, ${user.first_name}! 👋`;
        }

        // API Base URL
        const API_BASE = '{{ config('app.url') }}/api/telegram';

        // Fetch with Telegram auth
        async function fetchAPI(endpoint) {
            const url = `${API_BASE}${endpoint}`;
            const headers = {
                'Content-Type': 'application/json',
            };

            if (initData) {
                headers['X-Telegram-Init-Data'] = initData;
            }

            const response = await fetch(url, { headers });
            if (!response.ok) {
                throw new Error(`API error: ${response.status}`);
            }
            return response.json();
        }

        // Load groups
        async function loadGroups() {
            try {
                const data = await fetchAPI('/groups');
                groups = data.groups;
                renderTabs();
                renderGroups();
            } catch (error) {
                console.error('Error loading groups:', error);
                showError('Failed to load catalog. Please try again.');
            }
        }

        // Load items
        async function loadItems(groupSlug = null) {
            try {
                const endpoint = groupSlug ? `/items/${groupSlug}` : '/items';
                const data = await fetchAPI(endpoint);
                items = data.items;
                currentGroup = groupSlug;
                renderItems();
            } catch (error) {
                console.error('Error loading items:', error);
                showError('Failed to load items. Please try again.');
            }
        }

        // Load item details
        async function loadItemDetails(slug) {
            try {
                const data = await fetchAPI(`/item/${slug}`);
                showItemModal(data.item);
            } catch (error) {
                console.error('Error loading item:', error);
                tg.showAlert('Failed to load item details');
            }
        }

        // Render tabs
        function renderTabs() {
            const tabsEl = document.getElementById('tabs');
            const tabs = [
                { id: 'groups', label: '📁 All Groups' },
                ...groups.map(g => ({ id: g.slug, label: g.title }))
            ];

            tabsEl.innerHTML = tabs.map(tab => `
                <button
                    class="tab ${currentView === tab.id ? 'active' : ''}"
                    onclick="switchTab('${tab.id}')"
                >
                    ${tab.label}
                </button>
            `).join('');
        }

        // Switch tab
        function switchTab(tabId) {
            currentView = tabId;
            renderTabs();

            if (tabId === 'groups') {
                renderGroups();
            } else {
                loadItems(tabId);
            }
        }

        // Render groups
        function renderGroups() {
            const content = document.getElementById('content');
            document.getElementById('subText').textContent = 'Browse our product categories';

            content.className = 'grid';
            content.innerHTML = groups.map(group => `
                <div class="card" onclick="switchTab('${group.slug}')">
                    <div class="card-image">📦</div>
                    <div class="card-title">${group.title}</div>
                    ${group.description ? `<div class="card-description">${group.description}</div>` : ''}
                </div>
            `).join('');
        }

        // Render items
        function renderItems() {
            const content = document.getElementById('content');
            const currentGroupObj = groups.find(g => g.slug === currentGroup);
            document.getElementById('subText').textContent = currentGroupObj
                ? `${items.length} items in ${currentGroupObj.title}`
                : `${items.length} items`;

            if (items.length === 0) {
                content.className = 'loading';
                content.innerHTML = '<p>No items found in this category</p>';
                return;
            }

            content.className = 'grid';
            content.innerHTML = items.map(item => `
                <div class="card" onclick="loadItemDetails('${item.slug}')">
                    <div class="card-image">
                        ${item.image ? `<img src="${item.image}" alt="${item.title}">` : '🛍️'}
                    </div>
                    <div class="card-title">${item.title}</div>
                    <div class="card-price">$${item.price}</div>
                </div>
            `).join('');
        }

        // Show item modal
        function showItemModal(item) {
            const modal = document.getElementById('itemModal');
            const content = document.getElementById('modalContent');

            content.innerHTML = `
                <div class="modal-image">
                    ${item.image ? `<img src="${item.image}" alt="${item.title}">` : '<div style="display:flex;align-items:center;justify-content:center;height:100%;font-size:80px;">🛍️</div>'}
                </div>
                <h2 class="modal-title">${item.title}</h2>
                <div class="modal-price">$${item.price}</div>
                <p class="modal-description">${item.description || 'No description available'}</p>
                ${item.group ? `<span class="modal-group">📁 ${item.group.title}</span>` : ''}
            `;

            modal.classList.add('active');
            tg.BackButton.show();
            tg.BackButton.onClick(closeModal);
        }

        // Close modal
        function closeModal() {
            document.getElementById('itemModal').classList.remove('active');
            tg.BackButton.hide();
        }

        // Show error
        function showError(message) {
            document.getElementById('content').innerHTML = `
                <div class="error">${message}</div>
            `;
        }

        // Initialize
        loadGroups();

        // Handle viewport changes
        window.addEventListener('resize', () => {
            tg.expand();
        });
    </script>
</body>
</html>
