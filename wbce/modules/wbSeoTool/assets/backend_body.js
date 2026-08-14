/**
 * WBCE CMS AdminTool: wbSeoTool
 *
 * backend_body.js
 * Vanilla JS — no jQuery, no vendored plugins. HTMX (loaded separately)
 * handles the actual save requests declared via hx-* attributes in the
 * Twig templates; this file only covers UI state (column toggles, tree
 * collapse persistence) and the character counters.
 */
(function () {
	'use strict';

	var LS_COLS = 'wbseotool_cols';
	var LS_TREE = 'wbseotool_tree';

	function readJSON(key, fallback) {
		try {
			var raw = window.localStorage.getItem(key);
			return raw ? JSON.parse(raw) : fallback;
		} catch (e) {
			return fallback;
		}
	}

	function writeJSON(key, value) {
		try {
			window.localStorage.setItem(key, JSON.stringify(value));
		} catch (e) { /* storage unavailable — degrade silently */ }
	}

	// ── Column visibility toggles ──────────────────────────────────────────
	function initColumnToggles() {
		var state = readJSON(LS_COLS, {});
		var checkboxes = document.querySelectorAll('[data-toggle-col]');

		checkboxes.forEach(function (cb) {
			var col = cb.getAttribute('data-toggle-col');
			var visible = state.hasOwnProperty(col) ? state[col] : true;
			cb.checked = visible;
			applyColumn(col, visible);

			cb.addEventListener('change', function () {
				state[col] = cb.checked;
				writeJSON(LS_COLS, state);
				applyColumn(col, cb.checked);
			});
		});
	}

	function applyColumn(col, visible) {
		document.querySelectorAll('.wbseotool-col-' + col).forEach(function (row) {
			row.style.display = visible ? '' : 'none';
		});
	}

	// ── Tree collapse persistence ──────────────────────────────────────────
	function initTreeState() {
		var closed = readJSON(LS_TREE, []);
		document.querySelectorAll('details[data-node]').forEach(function (details) {
			var id = details.getAttribute('data-node');
			if (closed.indexOf(id) !== -1) {
				details.open = false;
			}
			details.addEventListener('toggle', function () {
				var current = readJSON(LS_TREE, []);
				var idx = current.indexOf(id);
				if (details.open && idx !== -1) {
					current.splice(idx, 1);
				} else if (!details.open && idx === -1) {
					current.push(id);
				}
				writeJSON(LS_TREE, current);

				// Textareas inside a collapsed <details> can't be measured
				// (scrollHeight is 0 while hidden) — resize them once revealed.
				if (details.open) {
					var table = details.querySelector(':scope > table.wbseotool-fields');
					if (table) {
						table.querySelectorAll('.wbseotool-edit').forEach(autoGrow);
					}
				}
			});
		});
	}

	// ── Character counters ──────────────────────────────────────────────────
	function counterConfigFor(type) {
		if (type === 'title') {
			return {
				use: window.iTitleCount_use == 1,
				minimum: window.iTitleCount_minimum,
				optimum: window.iTitleCount_optimum,
				maximum: window.iTitleCount_maximum
			};
		}
		if (type === 'description') {
			return {
				use: window.iDescriptionCount_use == 1,
				minimum: window.iDescriptionCount_minimum,
				optimum: window.iDescriptionCount_optimum,
				maximum: window.iDescriptionCount_maximum
			};
		}
		return null;
	}

	function updateCounter(textarea) {
		var type = textarea.getAttribute('data-counter-type');
		var cfg = counterConfigFor(type);
		var cell = textarea.closest('tr').querySelector('.wbseotool-counter');
		if (!cfg || !cfg.use || !cell) {
			if (cell) cell.textContent = '';
			return;
		}

		var len = textarea.value.length;
		var remaining = cfg.optimum - len;
		var text = (window.sCounterText || '[%2]{COUNTER_REMAINING}')
			.replace('%2', len)
			.replace('{COUNTER_REMAINING}', '')
			.replace('%1', Math.abs(remaining));

		cell.textContent = len + (remaining >= 0 ? (' (' + remaining + ' left)') : (' (' + Math.abs(remaining) + ' over)'));
		cell.classList.remove('cssOptimum', 'cssExceeded');
		if (len < cfg.minimum) {
			cell.classList.add('cssExceeded');
		} else if (len <= cfg.optimum) {
			cell.classList.add('cssOptimum');
		} else {
			cell.classList.add('cssExceeded');
		}
	}

	function initCounters() {
		document.querySelectorAll('textarea[data-counter-type]').forEach(function (textarea) {
			updateCounter(textarea);
			textarea.addEventListener('input', function () {
				updateCounter(textarea);
				updateStatusDot(textarea);
				updateDuplicatesFor(textarea.getAttribute('data-counter-type'));
			});
		});
	}

	// ── Status dots (empty / short / optimal / long) + duplicate check ──────
	// Visible even while the tree node is collapsed — the whole point is to
	// spot problems without expanding every single page.
	var STATUS_LABELS = window.STATUS_LABELS || {};

	function fieldStatus(len, cfg) {
		if (!cfg || !cfg.use) return null;
		if (len === 0) return 'empty';
		if (len < cfg.minimum) return 'short';
		if (len > cfg.maximum) return 'long';
		return 'optimal';
	}

	function fieldForType(li, type) {
		var details = li.querySelector(':scope > details');
		return details ? details.querySelector(':scope > table.wbseotool-fields textarea[data-counter-type="' + type + '"]') : null;
	}

	function dotForType(li, type) {
		var details = li.querySelector(':scope > details');
		return details ? details.querySelector(':scope > summary .wbseotool-dot[data-dot-for="' + type + '"]') : null;
	}

	function applyDotState(dot, ta, status, isDuplicate) {
		if (!dot) return;
		dot.className = 'wbseotool-dot' + (status ? ' status-' + status : ' status-off') + (isDuplicate ? ' has-duplicate' : '');
		var label = status ? (STATUS_LABELS[status] || status) : (STATUS_LABELS.off || '');
		if (isDuplicate) {
			label += (label ? ' — ' : '') + (STATUS_LABELS.duplicate || 'Duplicate');
		}
		dot.title = label;

		if (ta) {
			ta.classList.remove('status-empty', 'status-short', 'status-optimal', 'status-long');
			if (status) ta.classList.add('status-' + status);
			ta.classList.toggle('wbseotool-duplicate', !!isDuplicate);
		}
	}

	// Updates a single field's status dot (length only — duplicate state is
	// left untouched here since that requires a tree-wide rescan).
	function updateStatusDot(textarea) {
		var type = textarea.getAttribute('data-counter-type');
		if (!type) return;
		var li = textarea.closest('.wbseotool-node');
		if (!li) return;
		var cfg = counterConfigFor(type);
		var len = textarea.value.trim().length;
		var status = fieldStatus(len, cfg);
		var dot = dotForType(li, type);
		var isDuplicate = dot ? dot.classList.contains('has-duplicate') : false;
		applyDotState(dot, textarea, status, isDuplicate);
	}

	function initStatusDots() {
		document.querySelectorAll('.wbseotool-node').forEach(function (li) {
			['title', 'description'].forEach(function (type) {
				var ta = fieldForType(li, type);
				if (ta) updateStatusDot(ta);
			});
		});
	}

	// Tree-wide: find every title (or description) field with the same
	// trimmed value and flag all of them as duplicates. Empty fields are
	// excluded — "both empty" is already covered by the empty-status dot.
	function updateDuplicatesFor(type) {
		if (type !== 'title' && type !== 'description') return;
		var textareas = document.querySelectorAll('textarea[data-counter-type="' + type + '"]');
		var valueMap = {};
		textareas.forEach(function (ta) {
			var val = ta.value.trim().toLowerCase();
			if (!val) return;
			(valueMap[val] = valueMap[val] || []).push(ta);
		});
		textareas.forEach(function (ta) {
			var val = ta.value.trim().toLowerCase();
			var isDuplicate = !!val && valueMap[val].length > 1;
			var li = ta.closest('.wbseotool-node');
			var dot = li ? dotForType(li, type) : null;
			var cfg = counterConfigFor(type);
			var status = fieldStatus(ta.value.trim().length, cfg);
			applyDotState(dot, ta, status, isDuplicate);
		});
	}

	function initDuplicateCheck() {
		updateDuplicatesFor('title');
		updateDuplicatesFor('description');
	}

	// ── Autogrow (title/description/keywords/rewrite-url textareas) ─────────
	function autoGrow(ta) {
		ta.style.height = 'auto';
		ta.style.height = ta.scrollHeight + 'px';
	}

	function initAutoGrow() {
		document.querySelectorAll('.wbseotool-edit').forEach(function (ta) {
			autoGrow(ta);
			ta.addEventListener('input', function () { autoGrow(ta); });
		});
	}

	// ── Save feedback (htmx) ─────────────────────────────────────────────────
	function initSaveFeedback() {
		document.body.addEventListener('htmx:afterRequest', function (evt) {
			var el = evt.detail.elt;
			if (!el.classList || !el.classList.contains('wbseotool-edit')) return;
			el.classList.remove('wbseotool-save-ok', 'wbseotool-save-error');
			el.classList.add(evt.detail.successful ? 'wbseotool-save-ok' : 'wbseotool-save-error');
			window.setTimeout(function () {
				el.classList.remove('wbseotool-save-ok', 'wbseotool-save-error');
			}, 1500);
		});
	}

	// ── Menu title: double-click to edit (catalogue_hub "epslug" pattern) ───
	// Not a plain <textarea>+hx-post like the other fields — the display is a
	// compact inline hint (<code>/<span>) that only turns into an <input> on
	// dblclick, mirroring modules/DynamicFields/assets/image_manager.js.
	function flashSaveState(el, ok) {
		el.classList.remove('wbseotool-save-ok', 'wbseotool-save-error');
		el.classList.add(ok ? 'wbseotool-save-ok' : 'wbseotool-save-error');
		window.setTimeout(function () {
			el.classList.remove('wbseotool-save-ok', 'wbseotool-save-error');
		}, 1500);
	}

	function saveMenuTitle(ajaxUrl, idkey, value, el) {
		fetch(ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({ field: 'menu_title', idkey: idkey, value: value })
		}).then(function (resp) {
			flashSaveState(el, resp.ok);
		}).catch(function () {
			flashSaveState(el, false);
		});
	}

	function bindMenuTitleSpan(span, ajaxUrl) {
		// The title lives inside <summary> — any click on it (single or as
		// part of a dblclick) would otherwise also toggle the <details>
		// accordion via the browser's default summary-click behaviour.
		span.addEventListener('click', function (e) {
			e.preventDefault();
			e.stopPropagation();
		});

		span.addEventListener('dblclick', function (e) {
			e.preventDefault();
			e.stopPropagation();
			if (span.classList.contains('wbseotool-editing')) return;

			var orig  = span.textContent;
			var idkey = span.getAttribute('data-idkey');
			var title = span.getAttribute('title');

			var inp = document.createElement('input');
			inp.type = 'text';
			inp.className = 'wbseotool-menutitle-inp';
			inp.value = orig;
			inp.autocomplete = 'off';
			inp.spellcheck = false;
			inp.style.width = Math.max(6, orig.length + 2) + 'ch';
			inp.addEventListener('input', function () {
				inp.style.width = Math.max(6, inp.value.length + 2) + 'ch';
			});
			inp.addEventListener('click', function (e) {
				e.preventDefault();
				e.stopPropagation();
			});

			span.classList.add('wbseotool-editing');
			span.replaceWith(inp);
			inp.focus();
			inp.select();

			var committed = false;
			function commit(cancel) {
				// replaceWith() below detaches inp, which itself fires a
				// native 'blur' — without this guard that re-enters commit()
				// a second time on an already-removed element.
				if (committed) return;
				committed = true;

				var clean = cancel ? orig : (inp.value.trim() || orig);
				var newSpan = document.createElement('span');
				newSpan.className = 'wbseotool-menutitle-val';
				newSpan.setAttribute('data-idkey', idkey);
				newSpan.setAttribute('title', title);
				newSpan.textContent = clean;
				inp.replaceWith(newSpan);
				bindMenuTitleSpan(newSpan, ajaxUrl);

				if (!cancel && clean !== orig) {
					saveMenuTitle(ajaxUrl, idkey, clean, newSpan);
				}
			}

			inp.addEventListener('blur', function () { commit(false); });
			inp.addEventListener('keydown', function (e) {
				if (e.key === 'Enter') { e.preventDefault(); inp.blur(); }
				if (e.key === 'Escape') { e.preventDefault(); commit(true); }
			});
		});
	}

	function initMenuTitleEdit() {
		var tree = document.getElementById('pageTree');
		var ajaxUrl = tree ? tree.getAttribute('data-ajax-url') : null;
		if (!ajaxUrl) return;
		document.querySelectorAll('.wbseotool-menutitle-val').forEach(function (span) {
			bindMenuTitleSpan(span, ajaxUrl);
		});
	}

	document.addEventListener('DOMContentLoaded', function () {
		initColumnToggles();
		initTreeState();
		initCounters();
		initStatusDots();
		initDuplicateCheck();
		initAutoGrow();
		initSaveFeedback();
		initMenuTitleEdit();
	});
})();
