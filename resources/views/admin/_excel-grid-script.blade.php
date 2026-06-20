<div class="save-indicator" id="saveIndicator">
    <span id="saveText">Menyimpan...</span>
</div>

<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const indicator = document.getElementById('saveIndicator');
    const indicatorText = document.getElementById('saveText');
    let saveTimer = null;

    function showIndicator(msg, type) {
        clearTimeout(saveTimer);
        indicator.className = 'save-indicator visible ' + type;
        indicatorText.textContent = msg;
        if (type !== 'loading') {
            saveTimer = setTimeout(() => indicator.classList.remove('visible'), 2500);
        }
    }

    function getSelectOptions(optionStr) {
        // Format: "value:Label,value2:Label2"
        return optionStr.split(',').map(pair => {
            const [val, label] = pair.split(':');
            return { val: val.trim(), label: label.trim() };
        });
    }

    function saveCell(td, newValue) {
        const tr = td.closest('tr');
        const url = tr.dataset.url;
        const field = td.dataset.field;
        if (!url || !field) return;

        // Optimistically mark as saved
        td.classList.add('saved');
        setTimeout(() => td.classList.remove('saved'), 900);

        showIndicator('Menyimpan...', 'loading');

        const body = new URLSearchParams();
        body.append(field, newValue);
        body.append('_method', 'PATCH');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest',
            },
            body: body.toString(),
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showIndicator('✓ Tersimpan', 'success');
            } else {
                td.classList.add('error');
                showIndicator('✗ Gagal menyimpan', 'error');
            }
        })
        .catch(() => {
            td.classList.add('error');
            showIndicator('✗ Gagal menyimpan', 'error');
        });
    }

    function makeTextInput(td) {
        const original = td.textContent.trim();
        td.classList.add('editing');
        td.innerHTML = '';

        const input = document.createElement('input');
        input.type = 'text';
        input.value = original;
        td.appendChild(input);
        input.focus();
        input.select();

        function commit() {
            const newVal = input.value.trim();
            td.classList.remove('editing');
            td.textContent = newVal;
            if (newVal !== original) saveCell(td, newVal);
        }

        input.addEventListener('keydown', e => {
            if (e.key === 'Enter') { e.preventDefault(); input.blur(); }
            if (e.key === 'Escape') { td.classList.remove('editing'); td.textContent = original; }
            if (e.key === 'Tab') {
                e.preventDefault();
                const nextTd = findNextEditableTd(td, e.shiftKey);
                input.blur();
                if (nextTd) activateCell(nextTd);
            }
        });
        input.addEventListener('blur', commit);
    }

    function makeSelectInput(td) {
        const options = getSelectOptions(td.dataset.options || '');
        const original = td.textContent.trim();
        td.classList.add('editing');
        td.innerHTML = '';

        const select = document.createElement('select');
        options.forEach(opt => {
            const o = document.createElement('option');
            o.value = opt.val;
            o.textContent = opt.label;
            if (opt.label === original) o.selected = true;
            select.appendChild(o);
        });
        td.appendChild(select);
        select.focus();

        function commit() {
            const selectedOpt = options.find(o => o.val === select.value);
            const displayLabel = selectedOpt ? selectedOpt.label : select.value;
            td.classList.remove('editing');
            td.textContent = displayLabel;
            if (displayLabel !== original) saveCell(td, select.value);
        }

        select.addEventListener('change', () => { select.blur(); });
        select.addEventListener('keydown', e => {
            if (e.key === 'Escape') { td.classList.remove('editing'); td.textContent = original; }
        });
        select.addEventListener('blur', commit);
    }

    function activateCell(td) {
        // Deselect others
        document.querySelectorAll('.excel-grid td.selected').forEach(el => el.classList.remove('selected'));
        td.classList.add('selected');

        const type = td.dataset.type || 'text';
        if (type === 'select') {
            makeSelectInput(td);
        } else {
            makeTextInput(td);
        }
    }

    function findNextEditableTd(currentTd, reverse) {
        const allEditables = Array.from(document.querySelectorAll('.excel-grid td.editable'));
        const idx = allEditables.indexOf(currentTd);
        if (reverse) return allEditables[idx - 1] || null;
        return allEditables[idx + 1] || null;
    }

    // Click to edit
    document.querySelectorAll('.excel-grid td.editable').forEach(td => {
        td.addEventListener('click', function () {
            if (this.classList.contains('editing')) return;
            activateCell(this);
        });
    });

    // Keyboard navigation when a cell is selected but not editing
    document.addEventListener('keydown', e => {
        const selected = document.querySelector('.excel-grid td.selected:not(.editing)');
        if (!selected) return;

        if (e.key === 'Enter' || e.key === 'F2') {
            e.preventDefault();
            activateCell(selected);
        }
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            const next = findNextEditableTd(selected, false);
            if (next) { selected.classList.remove('selected'); next.classList.add('selected'); }
        }
        if (e.key === 'ArrowLeft') {
            e.preventDefault();
            const prev = findNextEditableTd(selected, true);
            if (prev) { selected.classList.remove('selected'); prev.classList.add('selected'); }
        }
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const tr = selected.closest('tr');
            const nextTr = tr.nextElementSibling;
            if (nextTr) {
                const sameCol = nextTr.querySelector(`td[data-field="${selected.dataset.field}"]`);
                if (sameCol && sameCol.classList.contains('editable')) {
                    selected.classList.remove('selected');
                    sameCol.classList.add('selected');
                }
            }
        }
        if (e.key === 'ArrowUp') {
            e.preventDefault();
            const tr = selected.closest('tr');
            const prevTr = tr.previousElementSibling;
            if (prevTr) {
                const sameCol = prevTr.querySelector(`td[data-field="${selected.dataset.field}"]`);
                if (sameCol && sameCol.classList.contains('editable')) {
                    selected.classList.remove('selected');
                    sameCol.classList.add('selected');
                }
            }
        }
        // Start typing on a selected cell
        if (e.key.length === 1 && !e.ctrlKey && !e.metaKey) {
            activateCell(selected);
        }
        if (e.key === 'Delete' || e.key === 'Backspace') {
            const td = selected;
            const original = td.textContent.trim();
            if (original !== '') {
                td.textContent = '';
                saveCell(td, '');
            }
        }
    });

    // Click outside deselects
    document.addEventListener('click', e => {
        if (!e.target.closest('.excel-grid')) {
            document.querySelectorAll('.excel-grid td.selected').forEach(el => el.classList.remove('selected'));
        }
    });

    // Delete row buttons
    document.querySelectorAll('.btn-delete-row').forEach(btn => {
        btn.addEventListener('click', function () {
            if (!confirm('Hapus baris ini? Tindakan tidak dapat dibatalkan.')) return;
            const url = this.dataset.url;
            const tr = this.closest('tr');

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: '_method=DELETE',
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    tr.style.transition = 'opacity .3s';
                    tr.style.opacity = '0';
                    setTimeout(() => tr.remove(), 300);
                    showIndicator('✓ Dihapus', 'success');
                } else {
                    showIndicator('✗ Gagal menghapus', 'error');
                }
            })
            .catch(() => showIndicator('✗ Gagal menghapus', 'error'));
        });
    });
})();
</script>
