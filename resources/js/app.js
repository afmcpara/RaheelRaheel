import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

document.querySelectorAll('[data-status-chart]').forEach((canvas) => {
    let payload;
    try {
        payload = JSON.parse(canvas.dataset.statusChart || '{}');
    } catch (_) {
        return;
    }
    const labels = payload.labels || [];
    const values = payload.values || [];
    const colors = payload.colors || [];

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Packages',
                    data: values,
                    backgroundColor: colors,
                    borderRadius: 0,
                    borderSkipped: false,
                    barPercentage: 0.55,
                    categoryPercentage: 0.7,
                    maxBarThickness: 28,
                    hoverBackgroundColor: colors.map((c) => c),
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: { top: 18, right: 8, bottom: 0, left: 8 } },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0a1832',
                    titleColor: '#f5d18a',
                    titleFont: { weight: '700', size: 12 },
                    bodyColor: '#fff',
                    bodyFont: { size: 13, weight: '600' },
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: (ctx) => `${ctx.parsed.y} package${ctx.parsed.y === 1 ? '' : 's'}`,
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false, drawBorder: false },
                    ticks: {
                        color: '#475569',
                        font: { size: 11, weight: '600' },
                        autoSkip: false,
                        maxRotation: 0,
                        minRotation: 0,
                        callback: function (value) {
                            const label = this.getLabelForValue(value);
                            if (label.length > 14) {
                                const words = label.split(' ');
                                const mid = Math.ceil(words.length / 2);
                                return [words.slice(0, mid).join(' '), words.slice(mid).join(' ')];
                            }
                            return label;
                        },
                    },
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11, weight: '600' },
                        precision: 0,
                        stepSize: 1,
                    },
                    grid: {
                        color: 'rgba(15, 23, 42, 0.06)',
                        drawBorder: false,
                        drawTicks: false,
                    },
                    border: { display: false },
                },
            },
            animation: { duration: 700, easing: 'easeOutQuart' },
        },
    });
});

const slider = document.querySelector('[data-slider]');

if (slider && window.innerWidth > 900) {
    const track = slider.querySelector('[data-track]');
    const nextBtn = slider.querySelector('[data-next]');
    const prevBtn = slider.querySelector('[data-prev]');
    const dotsWrap = document.querySelector('[data-dots]');
    const cardsPerView = 3;
    let index = 0;
    const maxIndex = Math.max(0, track.children.length - cardsPerView);
    let timer;

    const dots = [];
    if (dotsWrap) {
        for (let i = 0; i <= maxIndex; i += 1) {
            const dot = document.createElement('button');
            dot.type = 'button';
            dot.addEventListener('click', () => {
                index = i;
                update();
                restart();
            });
            dots.push(dot);
            dotsWrap.appendChild(dot);
        }
    }

    const update = () => {
        const offset = index * -(100 / cardsPerView);
        track.style.transform = `translateX(${offset}%)`;
        dots.forEach((dot, i) => {
            dot.classList.toggle('active', i === index);
        });
    };

    nextBtn?.addEventListener('click', () => {
        index = index >= maxIndex ? 0 : index + 1;
        update();
        restart();
    });

    prevBtn?.addEventListener('click', () => {
        index = index <= 0 ? maxIndex : index - 1;
        update();
        restart();
    });

    const start = () => setInterval(() => {
        index = index >= maxIndex ? 0 : index + 1;
        update();
    }, 4000);

    const restart = () => {
        clearInterval(timer);
        timer = start();
    };

    update();
    timer = start();
}

document.querySelectorAll('[data-ship-form]').forEach((form) => {
    const checkboxes = form.querySelectorAll('[data-ship-checkbox]');
    const selectAll = form.querySelector('[data-ship-select-all]');
    const countEl = form.querySelector('[data-ship-count]');
    const weightEl = form.querySelector('[data-ship-weight]');
    const submit = form.querySelector('[data-ship-submit]');

    const update = () => {
        let count = 0;
        let weight = 0;
        checkboxes.forEach((cb) => {
            if (cb.checked) {
                count += 1;
                weight += parseFloat(cb.dataset.weight || '0');
            }
        });
        if (countEl) countEl.textContent = count.toString();
        if (weightEl) weightEl.textContent = weight.toFixed(2);
        if (submit) submit.disabled = count === 0;
        if (selectAll) {
            selectAll.checked = count === checkboxes.length && count > 0;
            selectAll.indeterminate = count > 0 && count < checkboxes.length;
        }
    };

    checkboxes.forEach((cb) => cb.addEventListener('change', update));
    if (selectAll) {
        selectAll.addEventListener('change', () => {
            checkboxes.forEach((cb) => { cb.checked = selectAll.checked; });
            update();
        });
    }
    update();
});

document.querySelectorAll('[data-toggle-note]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const row = btn.closest('[data-queue-row]');
        if (row) row.classList.toggle('has-note');
    });
});

document.querySelectorAll('[data-combobox]').forEach((wrap) => {
    const input = wrap.querySelector('[data-combobox-search]');
    const hidden = wrap.querySelector('[data-combobox-value]');
    const list = wrap.querySelector('[data-combobox-list]');
    const clear = wrap.querySelector('[data-combobox-clear]');
    const valueLabel = wrap.querySelector('[data-combobox-selected]');
    if (!input || !hidden || !list) return;

    const items = Array.from(list.querySelectorAll('[data-combobox-item]'));
    let activeIndex = -1;

    const openList = () => {
        wrap.classList.add('is-open');
    };
    const closeList = () => {
        wrap.classList.remove('is-open');
        activeIndex = -1;
        items.forEach((it) => it.classList.remove('is-active'));
    };
    const setActive = (idx) => {
        items.forEach((it) => it.classList.remove('is-active'));
        const visible = items.filter((it) => !it.hidden);
        if (visible.length === 0) { activeIndex = -1; return; }
        const wrapped = ((idx % visible.length) + visible.length) % visible.length;
        activeIndex = wrapped;
        visible[wrapped].classList.add('is-active');
        visible[wrapped].scrollIntoView({ block: 'nearest' });
    };
    const filter = (q) => {
        const norm = q.trim().toLowerCase();
        items.forEach((it) => {
            const haystack = (it.dataset.search || it.textContent || '').toLowerCase();
            it.hidden = norm !== '' && !haystack.includes(norm);
        });
        const empty = wrap.querySelector('[data-combobox-empty]');
        if (empty) empty.hidden = items.some((it) => !it.hidden);
        activeIndex = -1;
    };
    const selectItem = (item) => {
        hidden.value = item.dataset.value || '';
        input.value = item.dataset.label || item.textContent.trim();
        if (valueLabel) valueLabel.textContent = item.dataset.label || item.textContent.trim();
        items.forEach((it) => it.classList.toggle('is-selected', it === item));
        closeList();
        wrap.classList.add('has-value');
    };

    input.addEventListener('focus', () => { openList(); filter(input.value); });
    input.addEventListener('click', () => { openList(); });
    input.addEventListener('input', () => {
        openList();
        filter(input.value);
        hidden.value = '';
        wrap.classList.remove('has-value');
    });
    input.addEventListener('keydown', (e) => {
        const visible = items.filter((it) => !it.hidden);
        if (e.key === 'ArrowDown') { e.preventDefault(); openList(); setActive(activeIndex + 1); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); setActive(activeIndex - 1); }
        else if (e.key === 'Enter') {
            if (activeIndex >= 0 && visible[activeIndex]) { e.preventDefault(); selectItem(visible[activeIndex]); }
        } else if (e.key === 'Escape') { closeList(); }
    });

    items.forEach((it) => {
        it.addEventListener('mousedown', (e) => { e.preventDefault(); selectItem(it); });
    });

    if (clear) {
        clear.addEventListener('click', (e) => {
            e.preventDefault();
            hidden.value = '';
            input.value = '';
            wrap.classList.remove('has-value');
            items.forEach((it) => it.classList.remove('is-selected'));
            filter('');
            input.focus();
        });
    }

    document.addEventListener('click', (e) => {
        if (!wrap.contains(e.target)) closeList();
    });

    const initialValue = hidden.value;
    if (initialValue) {
        const match = items.find((it) => it.dataset.value === initialValue);
        if (match) selectItem(match);
    }
});

document.querySelectorAll('[data-password-toggle]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const wrap = btn.closest('.password-field');
        if (!wrap) return;
        const input = wrap.querySelector('input');
        const open = btn.querySelector('.eye-open');
        const closed = btn.querySelector('.eye-closed');
        if (!input) return;
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        if (open && closed) {
            open.style.display = isHidden ? 'none' : '';
            closed.style.display = isHidden ? '' : 'none';
        }
        btn.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
    });
});

if ('IntersectionObserver' in window) {
    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

    document.querySelectorAll('[data-reveal]').forEach((el) => revealObserver.observe(el));
} else {
    document.querySelectorAll('[data-reveal]').forEach((el) => el.classList.add('is-visible'));
}

const animateCounter = (el) => {
    const target = parseFloat(el.dataset.counter || '0');
    const decimals = parseInt(el.dataset.decimals || '0', 10);
    const duration = 1600;
    const start = performance.now();
    const easeOut = (t) => 1 - Math.pow(1 - t, 3);
    const tick = (now) => {
        const t = Math.min(1, (now - start) / duration);
        const v = target * easeOut(t);
        el.textContent = decimals > 0 ? v.toFixed(decimals) : Math.round(v).toString();
        if (t < 1) requestAnimationFrame(tick);
        else el.textContent = decimals > 0 ? target.toFixed(decimals) : Math.round(target).toString();
    };
    requestAnimationFrame(tick);
};

const counterRoot = document.querySelector('[data-counters]');
if (counterRoot) {
    const counters = counterRoot.querySelectorAll('[data-counter]');
    if ('IntersectionObserver' in window) {
        const countObs = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    counters.forEach(animateCounter);
                    countObs.disconnect();
                }
            });
        }, { threshold: 0.4 });
        countObs.observe(counterRoot);
    } else {
        counters.forEach(animateCounter);
    }
}

const formatBytes = (bytes) => {
    if (bytes < 1024) return `${bytes} B`;
    if (bytes < 1024 * 1024) return `${(bytes / 1024).toFixed(1)} KB`;
    return `${(bytes / (1024 * 1024)).toFixed(2)} MB`;
};

document.querySelectorAll('[data-upload-form]').forEach((form) => {
    const input = form.querySelector('[data-upload-input]');
    const label = form.querySelector('[data-upload-label]');
    const zone = form.querySelector('[data-upload-zone]');
    const progress = form.querySelector('[data-upload-progress]');
    const bar = form.querySelector('[data-upload-bar]');
    const pct = form.querySelector('[data-upload-pct]');
    const msg = form.querySelector('[data-upload-msg]');
    const submit = form.querySelector('[data-upload-submit]');
    const defaultLabel = label ? label.textContent : '';

    const showMsg = (text, type) => {
        if (!msg) return;
        msg.hidden = false;
        msg.textContent = text;
        msg.className = `upload-msg ${type}`;
    };
    const resetMsg = () => {
        if (!msg) return;
        msg.hidden = true;
        msg.textContent = '';
    };
    const setProgress = (percent) => {
        if (!progress || !bar || !pct) return;
        progress.hidden = false;
        const p = Math.max(0, Math.min(100, Math.round(percent)));
        bar.style.width = `${p}%`;
        pct.textContent = `${p}%`;
    };
    const resetProgress = () => {
        if (!progress || !bar || !pct) return;
        progress.hidden = true;
        bar.style.width = '0%';
        pct.textContent = '0%';
    };

    const MAX_BYTES = 2 * 1024 * 1024;

    if (input && label) {
        input.addEventListener('change', () => {
            resetMsg();
            const file = input.files && input.files[0];
            if (file) {
                label.textContent = `${file.name} (${formatBytes(file.size)})`;
                zone?.classList.add('has-file');
                if (file.size > MAX_BYTES) {
                    showMsg(`File is ${formatBytes(file.size)} — maximum allowed is 2 MB. Please choose a smaller file.`, 'error');
                }
            } else {
                label.textContent = defaultLabel;
                zone?.classList.remove('has-file');
            }
        });
    }

    form.addEventListener('submit', (e) => {
        if (!input || !input.files || input.files.length === 0) return;
        e.preventDefault();
        const file = input.files[0];
        if (file.size > MAX_BYTES) {
            showMsg(`File is ${formatBytes(file.size)} — maximum allowed is 2 MB.`, 'error');
            return;
        }
        resetMsg();
        setProgress(0);
        if (submit) {
            submit.disabled = true;
            submit.textContent = 'Uploading...';
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', form.action, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Accept', 'application/json');

        xhr.upload.addEventListener('progress', (event) => {
            if (event.lengthComputable) {
                setProgress((event.loaded / event.total) * 100);
            }
        });

        xhr.addEventListener('load', () => {
            const tryParseJson = () => {
                try { return JSON.parse(xhr.responseText || '{}'); } catch (_) { return {}; }
            };

            if (xhr.status >= 200 && xhr.status < 300) {
                setProgress(100);
                const res = tryParseJson();
                showMsg(res.message || 'Invoice uploaded successfully.', 'success');
                setTimeout(() => {
                    window.location.href = res.redirect || window.location.href;
                }, 600);
                return;
            }

            const res = tryParseJson();
            let msg;
            switch (xhr.status) {
                case 401:
                    msg = 'Your session has expired. Please refresh the page and sign in again.';
                    break;
                case 403:
                    msg = "You don't have permission to upload this invoice.";
                    break;
                case 405:
                    msg = 'Upload route mismatch (405). Please refresh the page and try again — if the problem persists the form URL may need updating.';
                    break;
                case 413:
                    msg = 'File is too large for the server. Please choose a smaller file (max 2 MB).';
                    break;
                case 419:
                    msg = 'Security token expired. Please refresh the page and try again.';
                    break;
                case 422: {
                    msg = res.errors
                        ? Object.values(res.errors).flat()[0]
                        : (res.message || 'Validation failed.');
                    break;
                }
                case 500:
                case 502:
                case 503:
                case 504:
                    msg = `Server error (${xhr.status}). Please try again in a moment.`;
                    break;
                default:
                    msg = res.message || `Upload failed (HTTP ${xhr.status}). Please try again.`;
            }
            showMsg(msg, 'error');
            resetProgress();
            if (submit) { submit.disabled = false; submit.textContent = 'Upload Invoice'; }

            if (xhr.status === 405 || xhr.status === 419) {
                console.warn('[Ship2Aruba] Upload failed', {
                    status: xhr.status,
                    formAction: form.action,
                    method: form.method,
                    responsePreview: (xhr.responseText || '').slice(0, 400),
                });
            }
        });

        xhr.addEventListener('error', () => {
            showMsg('Network error. Please check your connection and try again.', 'error');
            resetProgress();
            if (submit) { submit.disabled = false; submit.textContent = 'Upload Invoice'; }
        });

        xhr.send(new FormData(form));
    });
});
