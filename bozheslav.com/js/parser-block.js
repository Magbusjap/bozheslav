// Column names
const COLUMN_LABELS = {
    city:        'Город',
    currency:    'Валюта',
    employer:    'Компания',
    experience:  'Опыт',
    id:          'ID',
    name:        'Вакансия',
    published:   'Дата',
    salary_from: 'От',
    salary_to:   'До',
    schedule:    'График',
    url:         'Ссылка',
};

function initParserBlock(block) {
    const endpoint  = block.dataset.endpoint;
    const paramName = block.dataset.param;
    const dataKey   = block.dataset.key;

    const input     = block.querySelector('.parser-block__input');
    const btn       = block.querySelector('.parser-block__btn');
    const status    = block.querySelector('.parser-block__status');
    const error     = block.querySelector('.parser-block__error');
    const tableWrap = block.querySelector('.parser-block__table-wrap');
    const thead     = block.querySelector('.parser-block__thead');
    const tbody     = block.querySelector('.parser-block__tbody');
    const empty     = block.querySelector('.parser-block__empty');
    const pagination = block.querySelector('.parser-block__pagination');

    // Status
    let allItems   = [];
    let sortCol    = null;
    let sortDir    = 1;
    let pageSize   = 25;
    let currentPage = 1;

    function show(el) { el.style.display = 'block'; }
    function hide(el) { el.style.display = 'none'; }

    // Sort by column
    function getSorted() {
        if (!sortCol) return allItems;
        return [...allItems].sort((a, b) => {
            const va = a[sortCol] ?? '';
            const vb = b[sortCol] ?? '';
            if (va < vb) return -1 * sortDir;
            if (va > vb) return  1 * sortDir;
            return 0;
        });
    }

    // Render rows based on current sort and pagination
    function renderRows() {
        const sorted = getSorted();
        const total  = sorted.length;
        const pages  = Math.ceil(total / pageSize);
        currentPage  = Math.min(currentPage, pages || 1);

        const start = (currentPage - 1) * pageSize;
        const slice = sorted.slice(start, start + pageSize);
        const columns = Object.keys(allItems[0]);

        tbody.innerHTML = slice.map(row =>
            '<tr class="parser-block__tr">' +
            columns.map(c => {
                const val = row[c];
                const cell = (typeof val === 'string' && val.startsWith('http'))
                    ? `<a href="${val}" target="_blank" rel="noopener noreferrer" class="parser-block__link">открыть</a>`
                    : (val ?? '<span class="parser-block__muted">—</span>');
                return `<td class="parser-block__td">${cell}</td>`;
            }).join('') +
            '</tr>'
        ).join('');

        renderPagination(total, pages);
    }

    // Render headers (once after loading)
    function renderHead() {
        const columns = Object.keys(allItems[0]);
        thead.innerHTML = '<tr>' +
            columns.map(c =>
                `<th class="parser-block__th" data-col="${c}">
                    ${COLUMN_LABELS[c] ?? c}
                    <span class="parser-block__sort-icon"></span>
                </th>`
            ).join('') +
            '</tr>';

        thead.querySelectorAll('.parser-block__th').forEach(th => {
            th.addEventListener('click', () => {
                const col = th.dataset.col;
                if (sortCol === col) {
                    sortDir *= -1;
                } else {
                    sortCol = col;
                    sortDir = 1;
                }
                // Update sort
                thead.querySelectorAll('.parser-block__th').forEach(t => {
                    t.querySelector('.parser-block__sort-icon').textContent = '';
                });
                th.querySelector('.parser-block__sort-icon').textContent =
                    sortDir === 1 ? ' ↑' : ' ↓';

                currentPage = 1;
                renderRows();
            });
        });
    }

    // Pagination rendering
    function renderPagination(total, pages) {
        if (!pagination) return;

        // Buttons for page size
        const sizes = [25, 50, 100, 250];
        const sizeHtml = sizes.map(s =>
            `<button class="parser-block__page-size ${s === pageSize ? 'active' : ''}" data-size="${s}">${s}</button>`
        ).join('');

        // Numbers with "..." if more than 7 pages
        let pageButtons = '';
        const range = [];
        if (pages <= 7) {
            for (let i = 1; i <= pages; i++) range.push(i);
        } else {
            range.push(1);
            if (currentPage > 3) range.push('...');
            for (let i = Math.max(2, currentPage - 1); i <= Math.min(pages - 1, currentPage + 1); i++) {
                range.push(i);
            }
            if (currentPage < pages - 2) range.push('...');
            range.push(pages);
        }

        pageButtons = range.map(p =>
            p === '...'
                ? `<span class="parser-block__page-dots">…</span>`
                : `<button class="parser-block__page-btn ${p === currentPage ? 'active' : ''}" data-page="${p}">${p}</button>`
        ).join('');

        pagination.innerHTML = `
            <div class="parser-block__pagination-info">
                Показано ${Math.min((currentPage - 1) * pageSize + 1, total)}–${Math.min(currentPage * pageSize, total)} из ${total}
            </div>
            <div class="parser-block__pagination-controls">
                <button class="parser-block__page-btn" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>←</button>
                ${pageButtons}
                <button class="parser-block__page-btn" data-page="${currentPage + 1}" ${currentPage === pages ? 'disabled' : ''}>→</button>
            </div>
            <div class="parser-block__page-sizes">
                Показывать: ${sizeHtml}
            </div>
        `;

        // Pagination events
        pagination.querySelectorAll('.parser-block__page-btn[data-page]').forEach(b => {
            b.addEventListener('click', () => {
                const p = parseInt(b.dataset.page);
                if (p >= 1 && p <= pages) {
                    currentPage = p;
                    renderRows();
                    tableWrap.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Page size events
        pagination.querySelectorAll('.parser-block__page-size').forEach(b => {
            b.addEventListener('click', () => {
                pageSize = parseInt(b.dataset.size);
                currentPage = 1;
                renderRows();
            });
        });
    }

    // Search
    async function doSearch() {
        const query = input.value.trim();
        if (!query) return;

        btn.disabled = true;
        const originalText = btn.textContent;
        btn.textContent = 'Загрузка...';

        hide(tableWrap); hide(empty); hide(error);
        if (pagination) hide(pagination);
        status.textContent = 'Запрос...';
        show(status);

        try {
            const res  = await fetch(`${endpoint}?${paramName}=${encodeURIComponent(query)}`);
            if (!res.ok) throw new Error('Ошибка сервера: ' + res.status);
            const json = await res.json();
            allItems   = json[dataKey] ?? [];

            if (allItems.length === 0) {
                hide(status);
                show(empty);
            } else {
                status.textContent = `Найдено: ${json.total ?? allItems.length}, загружено: ${allItems.length}`;
                sortCol = null; sortDir = 1; currentPage = 1;
                renderHead();
                renderRows();
                show(tableWrap);
                if (pagination) show(pagination);
            }
        } catch (e) {
            hide(status);
            error.textContent = 'Ошибка: ' + e.message;
            show(error);
        } finally {
            btn.disabled = false;
            btn.textContent = originalText;
        }
    }

    btn.addEventListener('click', doSearch);
    input.addEventListener('keydown', e => { if (e.key === 'Enter') doSearch(); });
}

export function initParserBlocks() {
    document.querySelectorAll('.parser-block').forEach(initParserBlock);
}