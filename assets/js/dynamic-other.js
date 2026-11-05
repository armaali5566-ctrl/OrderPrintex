/**
 * OrderPrintex - Universal Dynamic "Other" Dropdown Engine
 *
 * Automatically monitors and enriches user-selectable <select> dropdowns.
 * When "Other" is chosen:
 *   - Dynamically reveals a contextual custom input field directly below the dropdown.
 *   - Smooth animated slide-down / fade-in transition.
 *   - Pre-fills and pre-selects existing custom values in Edit Mode.
 *   - Clears values and suppresses stale data when switching away.
 *   - Enforces frontend validation and passes clean custom text to backend.
 *
 * Compatible with Public Storefront & Admin Black & Gold theme, modals, and AJAX.
 */

(function (window, document) {
    'use strict';

    // Inject dedicated responsive styles once
    function injectStyles() {
        if (document.getElementById('dynamic-other-engine-styles')) return;

        const style = document.createElement('style');
        style.id = 'dynamic-other-engine-styles';
        style.textContent = `
            .dynamic-other-container {
                display: none;
                opacity: 0;
                transform: translateY(-6px);
                transition: opacity 0.25s cubic-bezier(0.16, 1, 0.3, 1), transform 0.25s cubic-bezier(0.16, 1, 0.3, 1);
                margin-top: 0.65rem;
                margin-bottom: 0.35rem;
                width: 100%;
                box-sizing: border-box;
            }

            .dynamic-other-container.is-visible {
                display: block;
                opacity: 1;
                transform: translateY(0);
            }

            .dynamic-other-label {
                display: block;
                font-size: 0.82rem;
                font-weight: 700;
                margin-bottom: 0.35rem;
                color: inherit;
                letter-spacing: 0.02em;
            }

            .dynamic-other-label .required-star {
                color: #e53e3e;
                margin-left: 3px;
                font-weight: 800;
            }

            .dynamic-other-input {
                width: 100%;
                box-sizing: border-box;
                font-family: inherit;
                font-size: 0.92rem;
                padding: 0.65rem 0.85rem;
                border-radius: 6px;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            /* Public Storefront Dark / Brand styling */
            body:not(.admin-body) .dynamic-other-label {
                color: #E8C45A;
            }
            body:not(.admin-body) .dynamic-other-input {
                background: #181818;
                border: 1px solid #40351E;
                color: #FFFFFF;
            }
            body:not(.admin-body) .dynamic-other-input:focus {
                outline: none;
                border-color: #FFD76A;
                box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.25);
            }

            /* Admin Panel Black & Gold Theme styling */
            .admin-body .dynamic-other-label,
            .admin-card .dynamic-other-label,
            .admin-modal-content .dynamic-other-label {
                color: var(--admin-gold, #D4AF37) !important;
            }
            .admin-body .dynamic-other-input,
            .admin-card .dynamic-other-input,
            .admin-modal-content .dynamic-other-input {
                background: var(--admin-bg-input, #12151E) !important;
                border: 1px solid var(--admin-border-input, #252B3B) !important;
                color: var(--admin-text-main, #FFFFFF) !important;
            }
            .admin-body .dynamic-other-input:focus,
            .admin-card .dynamic-other-input:focus,
            .admin-modal-content .dynamic-other-input:focus {
                outline: none;
                border-color: var(--admin-gold, #D4AF37) !important;
                box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2) !important;
            }

            .dynamic-other-input.has-error {
                border-color: #e53e3e !important;
                box-shadow: 0 0 0 3px rgba(229, 62, 62, 0.25) !important;
                animation: dynamicOtherShake 0.35s ease-in-out;
            }

            @keyframes dynamicOtherShake {
                0%, 100% { transform: translateX(0); }
                20%, 60% { transform: translateX(-4px); }
                40%, 80% { transform: translateX(4px); }
            }

            .dynamic-other-hint {
                font-size: 0.74rem;
                color: #8E887E;
                margin-top: 0.3rem;
                display: block;
                line-height: 1.4;
            }
            .admin-body .dynamic-other-hint,
            .admin-card .dynamic-other-hint {
                color: var(--admin-text-muted, #7E869B);
            }
        `;
        document.head.appendChild(style);
    }

    const DynamicOther = {
        init: function (root) {
            injectStyles();
            const container = root || document;
            const selects = container.querySelectorAll('select[data-enable-other="true"], select.dynamic-other-select');

            selects.forEach(function (select) {
                if (select._dynamicOtherInitialized) return;
                DynamicOther.bind(select);
            });
        },

        bind: function (select) {
            if (select._dynamicOtherInitialized) {
                if (typeof select._dynamicOtherSync === 'function') {
                    select._dynamicOtherSync(true);
                }
                return;
            }
            select._dynamicOtherInitialized = true;

            const otherValue = (select.getAttribute('data-other-value') || 'other').toLowerCase();
            const inputName = select.getAttribute('data-other-name') || ('custom_' + (select.name || 'field').replace(/\[\]$/, ''));
            const inputLabel = select.getAttribute('data-other-label') || 'Please Specify Custom Option';
            const inputPlaceholder = select.getAttribute('data-other-placeholder') || 'Enter custom specification...';
            const inputHint = select.getAttribute('data-other-hint') || '';
            const initialCustomValue = select.getAttribute('data-other-value-init') || '';

            // 1. Ensure an "Other" option exists in the select
            let hasOtherOption = false;
            let otherOptionElement = null;
            for (let i = 0; i < select.options.length; i++) {
                const opt = select.options[i];
                if (opt.value.toLowerCase() === otherValue) {
                    hasOtherOption = true;
                    otherOptionElement = opt;
                    break;
                }
            }

            if (!hasOtherOption) {
                otherOptionElement = document.createElement('option');
                otherOptionElement.value = otherValue;
                otherOptionElement.textContent = select.getAttribute('data-other-text') || 'Other (Specify Below)';
                select.appendChild(otherOptionElement);
            }

            // 2. Locate or create the container for the custom input
            let wrapper = null;
            const explicitTargetId = select.getAttribute('data-other-target');
            if (explicitTargetId) {
                wrapper = document.getElementById(explicitTargetId);
            }

            if (!wrapper) {
                wrapper = document.createElement('div');
                wrapper.className = 'dynamic-other-container';
                wrapper.setAttribute('data-for-select', select.name || select.id || 'select');

                const labelEl = document.createElement('label');
                labelEl.className = 'dynamic-other-label';
                labelEl.innerHTML = `<span>${inputLabel}</span><span class="required-star">*</span>`;

                const inputEl = document.createElement('input');
                inputEl.type = 'text';
                inputEl.name = inputName;
                inputEl.className = 'dynamic-other-input';
                inputEl.placeholder = inputPlaceholder;
                inputEl.autocomplete = 'off';
                inputEl.addEventListener('input', function () {
                    if (this.value.trim()) {
                        this.classList.remove('has-error');
                    }
                });

                wrapper.appendChild(labelEl);
                wrapper.appendChild(inputEl);

                if (inputHint) {
                    const hintEl = document.createElement('small');
                    hintEl.className = 'dynamic-other-hint';
                    hintEl.textContent = inputHint;
                    wrapper.appendChild(hintEl);
                }

                // Insert wrapper directly after select (or after select's form-group wrapper if needed)
                if (select.parentNode) {
                    // Check if select has a parent .form-group or container
                    select.parentNode.insertBefore(wrapper, select.nextSibling);
                }
            }

            const customInput = wrapper.querySelector('input') || wrapper.querySelector('textarea');

            // 3. Helper to update UI visibility
            function syncVisibility(immediate) {
                const currentValue = (select.value || '').toLowerCase();
                const isOther = (currentValue === otherValue);

                if (isOther) {
                    wrapper.classList.add('is-visible');
                    if (customInput) {
                        customInput.disabled = false;
                        if (select.required || select.getAttribute('data-other-required') !== 'false') {
                            customInput.required = true;
                        }
                        const dynamicInit = select.getAttribute('data-other-value-init');
                        if (dynamicInit && !customInput.value) {
                            customInput.value = dynamicInit;
                        }
                        if (!immediate) {
                            setTimeout(function () {
                                customInput.focus();
                            }, 100);
                        }
                    }
                } else {
                    wrapper.classList.remove('is-visible');
                    if (customInput) {
                        customInput.required = false;
                        customInput.disabled = false;
                        customInput.value = '';
                        customInput.classList.remove('has-error');
                    }
                }
            }
            select._dynamicOtherSync = syncVisibility;

            // 4. Change listener
            select.addEventListener('change', function () {
                syncVisibility(false);
            });

            // 5. Parent Form validation hook to prevent submitting blank "Other"
            const form = select.closest('form');
            if (form && !form._dynamicOtherValidated) {
                form._dynamicOtherValidated = true;
                form.addEventListener('submit', function (e) {
                    const activeOtherWrappers = form.querySelectorAll('.dynamic-other-container.is-visible');
                    let hasError = false;

                    activeOtherWrappers.forEach(function (activeWrap) {
                        const input = activeWrap.querySelector('input, textarea');
                        if (input && input.required && !input.disabled) {
                            const trimmed = input.value.trim();
                            if (!trimmed || trimmed.toLowerCase() === 'other') {
                                hasError = true;
                                input.classList.add('has-error');
                                input.focus();
                            } else {
                                input.classList.remove('has-error');
                            }
                        }
                    });

                    if (hasError) {
                        e.preventDefault();
                        e.stopPropagation();
                        if (window.AdminToast) {
                            window.AdminToast.show('Please enter a specific custom value for the selected "Other" option.', 'warning');
                        }
                    }
                });
            }

            // 6. Handle Edit Mode / Pre-populated custom value
            let resolvedInitVal = initialCustomValue;
            if (!resolvedInitVal && select.value) {
                // If the select currently has a custom string that doesn't match any standard option
                let matched = false;
                for (let j = 0; j < select.options.length; j++) {
                    if (select.options[j].value === select.value && select.options[j].value.toLowerCase() !== otherValue) {
                        matched = true;
                        break;
                    }
                }
                if (!matched && select.value.toLowerCase() !== otherValue && select.value !== '') {
                    resolvedInitVal = select.value;
                }
            }

            if (resolvedInitVal) {
                select.value = otherValue;
                if (customInput) {
                    customInput.value = resolvedInitVal;
                }
                syncVisibility(true);
            } else if ((select.value || '').toLowerCase() === otherValue) {
                syncVisibility(true);
            }
        }
    };

    // Auto-initialize on DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            DynamicOther.init();
        });
    } else {
        DynamicOther.init();
    }

    // Auto-detect modals and dynamic nodes
    if (typeof MutationObserver !== 'undefined') {
        const observer = new MutationObserver(function (mutations) {
            let shouldInit = false;
            for (let i = 0; i < mutations.length; i++) {
                if (mutations[i].addedNodes.length > 0) {
                    shouldInit = true;
                    break;
                }
            }
            if (shouldInit) {
                DynamicOther.init();
            }
        });
        observer.observe(document.documentElement, { childList: true, subtree: true });
    }

    // Expose globally
    window.DynamicOther = DynamicOther;

})(window, document);
