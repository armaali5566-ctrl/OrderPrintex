/**
 * OrderPrintex - Premium Admin Notifications & Custom Modal System
 * Provides non-blocking toasts, luxury confirmation modals, and intercepts native popups.
 */

(function () {
  'use strict';

  // =========================================================================
  // 1. TOAST NOTIFICATION ENGINE (AdminToast)
  // =========================================================================
  const AdminToast = {
    getContainer() {
      let container = document.getElementById('adminToastContainer');
      if (!container) {
        container = document.createElement('div');
        container.id = 'adminToastContainer';
        container.setAttribute('aria-live', 'polite');
        document.body.appendChild(container);
      }
      return container;
    },

    show({ type = 'info', title = '', message = '', duration = 4500 }) {
      const container = this.getContainer();

      const icons = {
        success: '&#x2713;', // Check
        error: '&#x2715;',   // Cross
        warning: '&#x26A0;', // Warning triangle
        info: '&#x2139;'     // Info
      };

      const defaultTitles = {
        success: 'Action Successful',
        error: 'Action Failed',
        warning: 'Attention Required',
        info: 'System Information'
      };

      const toast = document.createElement('div');
      toast.className = `admin-toast toast-${type}`;
      toast.setAttribute('role', type === 'error' ? 'alert' : 'status');

      toast.innerHTML = `
        <div class="admin-toast-icon">${icons[type] || icons.info}</div>
        <div class="admin-toast-content">
          <div class="admin-toast-title">${escapeHtml(title || defaultTitles[type])}</div>
          <p class="admin-toast-message">${escapeHtml(message)}</p>
        </div>
        <button type="button" class="admin-toast-close" aria-label="Dismiss notification">&times;</button>
      `;

      const dismiss = () => {
        if (toast.classList.contains('closing')) return;
        toast.classList.add('closing');
        setTimeout(() => toast.remove(), 260);
      };

      toast.querySelector('.admin-toast-close').addEventListener('click', dismiss);

      if (duration > 0) {
        setTimeout(dismiss, duration);
      }

      container.appendChild(toast);
      return toast;
    },

    success(message, title = 'Success', duration = 4500) {
      return this.show({ type: 'success', title, message, duration });
    },

    error(message, title = 'Error Encountered', duration = 6000) {
      return this.show({ type: 'error', title, message, duration });
    },

    warning(message, title = 'Warning', duration = 5000) {
      return this.show({ type: 'warning', title, message, duration });
    },

    info(message, title = 'Notification', duration = 4500) {
      return this.show({ type: 'info', title, message, duration });
    }
  };

  // =========================================================================
  // 2. CONFIRMATION MODAL ENGINE (AdminModal)
  // =========================================================================
  const AdminModal = {
    modalEl: null,
    resolvePromise: null,

    init() {
      if (document.getElementById('adminConfirmModal')) return;

      const backdrop = document.createElement('div');
      backdrop.id = 'adminConfirmModal';
      backdrop.className = 'admin-confirm-backdrop';
      backdrop.innerHTML = `
        <div class="admin-confirm-card" role="dialog" aria-modal="true" aria-labelledby="adminConfirmTitle">
          <div class="admin-confirm-header">
            <div class="admin-confirm-icon-box" id="adminConfirmIconBox">&#x26A0;</div>
            <div class="admin-confirm-title-area">
              <h3 class="admin-confirm-title" id="adminConfirmTitle">Confirm Action</h3>
              <div id="adminConfirmItemTag" class="admin-confirm-item-tag" style="display:none;"></div>
            </div>
          </div>
          <div class="admin-confirm-body">
            <p class="admin-confirm-message" id="adminConfirmMessage">Are you sure you want to proceed?</p>
          </div>
          <div class="admin-confirm-footer">
            <button type="button" class="admin-confirm-btn admin-confirm-btn-cancel" id="adminConfirmCancelBtn">Cancel</button>
            <button type="button" class="admin-confirm-btn admin-confirm-btn-primary" id="adminConfirmOkBtn">Confirm</button>
          </div>
        </div>
      `;

      document.body.appendChild(backdrop);
      this.modalEl = backdrop;

      // Event listeners
      const cancelBtn = backdrop.querySelector('#adminConfirmCancelBtn');
      const okBtn = backdrop.querySelector('#adminConfirmOkBtn');

      cancelBtn.addEventListener('click', () => this.close(false));
      okBtn.addEventListener('click', () => this.handleConfirm());

      backdrop.addEventListener('click', (e) => {
        if (e.target === backdrop) this.close(false);
      });

      document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && this.modalEl.classList.contains('active')) {
          this.close(false);
        }
      });
    },

    /**
     * Show custom confirmation modal
     * Returns Promise<boolean>
     */
    confirm({
      title = 'Confirm Action',
      message = 'Are you sure you want to perform this action?',
      itemName = '',
      isDestructive = false,
      confirmText = '',
      cancelText = 'Cancel',
      icon = ''
    } = {}) {
      this.init();

      return new Promise((resolve) => {
        this.resolvePromise = resolve;

        const card = this.modalEl.querySelector('.admin-confirm-card');
        const iconBox = this.modalEl.querySelector('#adminConfirmIconBox');
        const titleEl = this.modalEl.querySelector('#adminConfirmTitle');
        const itemTag = this.modalEl.querySelector('#adminConfirmItemTag');
        const messageEl = this.modalEl.querySelector('#adminConfirmMessage');
        const cancelBtn = this.modalEl.querySelector('#adminConfirmCancelBtn');
        const okBtn = this.modalEl.querySelector('#adminConfirmOkBtn');

        // Reset state
        okBtn.disabled = false;
        okBtn.innerHTML = confirmText || (isDestructive ? 'Yes, Proceed' : 'Confirm');
        cancelBtn.disabled = false;
        cancelBtn.innerText = cancelText;

        if (isDestructive) {
          card.classList.add('destructive');
          iconBox.className = 'admin-confirm-icon-box icon-destructive';
          iconBox.innerHTML = icon || '&#x1F5D1;'; // Trash icon
          okBtn.className = 'admin-confirm-btn admin-confirm-btn-danger';
        } else {
          card.classList.remove('destructive');
          iconBox.className = 'admin-confirm-icon-box icon-positive';
          iconBox.innerHTML = icon || '&#x2713;'; // Checkmark
          okBtn.className = 'admin-confirm-btn admin-confirm-btn-primary';
        }

        titleEl.innerText = title;
        messageEl.innerText = message;

        if (itemName) {
          itemTag.innerText = itemName;
          itemTag.style.display = 'inline-block';
        } else {
          itemTag.style.display = 'none';
        }

        // Show modal and lock background scroll
        this.modalEl.classList.add('active');
        document.body.style.overflow = 'hidden';

        setTimeout(() => okBtn.focus(), 80);
      });
    },

    handleConfirm() {
      const okBtn = this.modalEl.querySelector('#adminConfirmOkBtn');
      const cancelBtn = this.modalEl.querySelector('#adminConfirmCancelBtn');

      okBtn.disabled = true;
      cancelBtn.disabled = true;
      okBtn.innerHTML = '<span class="admin-spinner"></span> Processing...';

      if (this.resolvePromise) {
        this.resolvePromise(true);
        this.resolvePromise = null;
      }

      setTimeout(() => this.close(true), 150);
    },

    close(wasConfirmed = false) {
      if (!this.modalEl) return;
      this.modalEl.classList.remove('active');
      document.body.style.overflow = '';

      if (this.resolvePromise) {
        this.resolvePromise(wasConfirmed);
        this.resolvePromise = null;
      }
    }
  };

  // =========================================================================
  // 3. LEGACY POPUP INTERCEPTION (alert, confirm replacement)
  // =========================================================================
  // Intercept window.alert with non-blocking AdminToast
  window.alert = function (msg) {
    AdminToast.warning(String(msg || ''));
  };

  // Helper function to escape HTML
  function escapeHtml(str) {
    if (typeof str !== 'string') return str;
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // =========================================================================
  // 4. DESTRUCTIVE ACTION INTERCEPTION ([data-confirm] & forms)
  // =========================================================================
  document.addEventListener('DOMContentLoaded', function () {
    // Intercept buttons with [data-confirm]
    document.addEventListener('click', async function (e) {
      const target = e.target.closest('[data-confirm]');
      if (!target) return;

      e.preventDefault();
      e.stopPropagation();

      const message = target.getAttribute('data-confirm') || 'Are you sure you want to perform this action?';
      const title = target.getAttribute('data-confirm-title') || 'Confirm Action';
      const itemName = target.getAttribute('data-confirm-item') || '';
      const isDestructive = target.classList.contains('btn-danger') || target.getAttribute('data-destructive') === 'true' || /delete|remove|suspend|reject/i.test(message + title);
      const confirmText = target.getAttribute('data-confirm-btn') || (isDestructive ? 'Yes, Delete' : 'Confirm');

      const confirmed = await AdminModal.confirm({
        title,
        message,
        itemName,
        isDestructive,
        confirmText
      });

      if (confirmed) {
        // If it's a submit button inside a form, submit the parent form
        const form = target.closest('form');
        if (form && (target.type === 'submit' || target.tagName === 'BUTTON')) {
          // If the button had a name/value, append a hidden input so backend receives it
          if (target.name && target.value) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = target.name;
            hidden.value = target.value;
            form.appendChild(hidden);
          }
          form.submit();
        } else if (target.tagName === 'A' && target.href) {
          window.location.href = target.href;
        }
      }
    }, true);
  });

  // Expose globally
  window.AdminToast = AdminToast;
  window.AdminModal = AdminModal;
})();

