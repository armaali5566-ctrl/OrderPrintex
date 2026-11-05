/**
 * ORDERPRINTEX — Enterprise Admin Panel Interactive Controls
 * Sidebar, Quotation Calculator, Modals, Confirmations & Previews
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Sidebar Scroll Persistence & Active Link Visibility (Fix for Sidebar Scroll Reset Bug)
  const sidebarNav = document.getElementById('adminSidebarNav');
  const activeLink = document.querySelector('.sidebar-link.active');

  function saveSidebarScroll() {
    if (sidebarNav) {
      try {
        sessionStorage.setItem('op_admin_sidebar_scroll', sidebarNav.scrollTop);
      } catch (e) {}
    }
  }

  if (sidebarNav) {
    // Restore scroll position if saved
    try {
      const savedScroll = sessionStorage.getItem('op_admin_sidebar_scroll');
      if (savedScroll !== null) {
        sidebarNav.scrollTop = parseInt(savedScroll, 10);
      }
    } catch (e) {}

    // Track scroll position on user scroll
    sidebarNav.addEventListener('scroll', saveSidebarScroll, { passive: true });

    // Save scroll before navigating via any sidebar link
    sidebarNav.querySelectorAll('a').forEach(link => {
      link.addEventListener('click', function () {
        saveSidebarScroll();
        if (window.innerWidth <= 992) {
          closeSidebar();
        }
      });
    });

    // Ensure active link is scrolled into view smoothly if not already visible
    if (activeLink) {
      setTimeout(() => {
        const navRect = sidebarNav.getBoundingClientRect();
        const linkRect = activeLink.getBoundingClientRect();
        if (linkRect.top < navRect.top + 30 || linkRect.bottom > navRect.bottom - 30) {
          activeLink.scrollIntoView({ block: 'nearest', behavior: 'auto' });
          saveSidebarScroll();
        }
      }, 50);
    }
  }

  window.addEventListener('beforeunload', saveSidebarScroll);

  // 1B. Mobile Sidebar Drawer & Touch Backdrop
  const sidebarToggle = document.querySelector('.dashboard-sidebar-toggle');
  const sidebarClose = document.getElementById('sidebarCloseBtn');
  const sidebar = document.querySelector('.dashboard-sidebar');
  let overlay = document.querySelector('.sidebar-overlay');

  if (!overlay && sidebar) {
    overlay = document.createElement('div');
    overlay.className = 'sidebar-overlay';
    document.body.appendChild(overlay);
  }

  function openSidebar() {
    if (sidebar) sidebar.classList.add('open');
    if (overlay) overlay.classList.add('open');
    document.body.classList.add('sidebar-open');
  }

  function closeSidebar() {
    if (sidebar) sidebar.classList.remove('open');
    if (overlay) overlay.classList.remove('open');
    document.body.classList.remove('sidebar-open');
  }

  if (sidebarToggle) {
    sidebarToggle.addEventListener('click', function (e) {
      e.stopPropagation();
      if (sidebar && sidebar.classList.contains('open')) {
        closeSidebar();
      } else {
        openSidebar();
      }
    });
  }

  if (sidebarClose) {
    sidebarClose.addEventListener('click', function (e) {
      e.stopPropagation();
      closeSidebar();
    });
  }

  if (overlay) {
    overlay.addEventListener('click', closeSidebar);
  }

  window.addEventListener('resize', function () {
    if (window.innerWidth > 992 && sidebar && sidebar.classList.contains('open')) {
      closeSidebar();
    }
  });

  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      closeSidebar();
      closeAllModals();
    }
  });

  // 2. Modals Management
  function closeAllModals() {
    document.querySelectorAll('.modal-backdrop.open').forEach(modal => {
      modal.classList.remove('open');
    });
  }

  document.querySelectorAll('[data-modal-open]').forEach(btn => {
    btn.addEventListener('click', function () {
      const targetId = btn.getAttribute('data-modal-open');
      const targetModal = document.getElementById(targetId);
      if (targetModal) {
        targetModal.classList.add('open');
      }
    });
  });

  document.querySelectorAll('.modal-close, [data-modal-close]').forEach(btn => {
    btn.addEventListener('click', function () {
      const modal = btn.closest('.modal-backdrop');
      if (modal) modal.classList.remove('open');
    });
  });

  document.querySelectorAll('.modal-backdrop').forEach(modal => {
    modal.addEventListener('click', function (e) {
      if (e.target === modal) {
        modal.classList.remove('open');
      }
    });
  });

  // 3. Destructive Action Confirmation
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', function (e) {
      const msg = el.getAttribute('data-confirm') || 'Are you sure you want to perform this action?';
      if (!confirm(msg)) {
        e.preventDefault();
        e.stopPropagation();
      }
    });
  });

  // 4. File Upload Image Preview
  document.querySelectorAll('input[type="file"][data-preview-target]').forEach(input => {
    input.addEventListener('change', function () {
      const targetSelector = input.getAttribute('data-preview-target');
      const targetEl = document.querySelector(targetSelector);
      if (!targetEl || !input.files || !input.files[0]) return;

      const file = input.files[0];
      if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function (e) {
          if (targetEl.tagName.toLowerCase() === 'img') {
            targetEl.src = e.target.result;
            targetEl.style.display = 'block';
          } else {
            targetEl.style.backgroundImage = 'url(' + e.target.result + ')';
            targetEl.style.backgroundSize = 'cover';
            targetEl.style.backgroundPosition = 'center';
          }
        };
        reader.readAsDataURL(file);
      }
    });
  });

  // 5. Quotation Line Items Dynamic Calculator
  const itemsContainer = document.getElementById('quotation_items_body');
  const addItemBtn = document.getElementById('add_quotation_item_btn');
  const subtotalEl = document.getElementById('calc_subtotal');
  const taxRateInput = document.getElementById('calc_tax_rate');
  const taxAmountEl = document.getElementById('calc_tax_amount');
  const deliveryInput = document.getElementById('calc_delivery');
  const grandTotalEl = document.getElementById('calc_grand_total');

  function calculateQuotation() {
    if (!itemsContainer) return;

    let subtotal = 0;
    const rows = itemsContainer.querySelectorAll('.quote-item-row');
    rows.forEach(row => {
      const qtyInput = row.querySelector('.item-qty');
      const priceInput = row.querySelector('.item-price');
      const totalCell = row.querySelector('.item-total');

      const qty = parseFloat(qtyInput ? qtyInput.value : 1) || 0;
      const price = parseFloat(priceInput ? priceInput.value : 0) || 0;
      const rowTotal = qty * price;

      if (totalCell) {
        totalCell.textContent = '₹' + rowTotal.toFixed(2);
      }
      subtotal += rowTotal;
    });

    if (subtotalEl) subtotalEl.textContent = '₹' + subtotal.toFixed(2);

    const taxRate = parseFloat(taxRateInput ? taxRateInput.value : 18) || 0;
    const taxAmount = (subtotal * taxRate) / 100;
    if (taxAmountEl) taxAmountEl.textContent = '₹' + taxAmount.toFixed(2);

    const delivery = parseFloat(deliveryInput ? deliveryInput.value : 0) || 0;
    const grandTotal = subtotal + taxAmount + delivery;

    if (grandTotalEl) grandTotalEl.textContent = '₹' + grandTotal.toFixed(2);

    // Update hidden inputs
    const hiddenSubtotal = document.getElementById('hidden_subtotal');
    const hiddenTax = document.getElementById('hidden_tax_amount');
    const hiddenGrand = document.getElementById('hidden_grand_total');
    if (hiddenSubtotal) hiddenSubtotal.value = subtotal.toFixed(2);
    if (hiddenTax) hiddenTax.value = taxAmount.toFixed(2);
    if (hiddenGrand) hiddenGrand.value = grandTotal.toFixed(2);
  }

  if (itemsContainer) {
    itemsContainer.addEventListener('input', calculateQuotation);
    if (taxRateInput) taxRateInput.addEventListener('input', calculateQuotation);
    if (deliveryInput) deliveryInput.addEventListener('input', calculateQuotation);
    calculateQuotation();
  }

  if (addItemBtn && itemsContainer) {
    addItemBtn.addEventListener('click', function () {
      const rowCount = itemsContainer.querySelectorAll('.quote-item-row').length;
      const tr = document.createElement('tr');
      tr.className = 'quote-item-row';
      tr.innerHTML = `
        <td>
          <input type="text" name="items[${rowCount}][description]" class="form-control" placeholder="e.g. DTF Gang Sheet (22x40in)" required>
        </td>
        <td>
          <input type="text" name="items[${rowCount}][dimensions]" class="form-control" placeholder="e.g. 22 x 40 in">
        </td>
        <td style="width: 100px;">
          <input type="number" name="items[${rowCount}][quantity]" class="form-control item-qty" value="1" min="1" required>
        </td>
        <td style="width: 140px;">
          <input type="number" step="0.01" name="items[${rowCount}][unit_price]" class="form-control item-price" value="0.00" min="0" required>
        </td>
        <td style="width: 120px; font-weight: 600;" class="item-total">
          ₹0.00
        </td>
        <td style="width: 60px; text-align: center;">
          <button type="button" class="btn btn-sm btn-outline text-crimson remove-row-btn" title="Remove Item">&times;</button>
        </td>
      `;
      itemsContainer.appendChild(tr);

      tr.querySelector('.remove-row-btn').addEventListener('click', function () {
        tr.remove();
        calculateQuotation();
      });

      calculateQuotation();
    });

    itemsContainer.querySelectorAll('.remove-row-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        btn.closest('.quote-item-row').remove();
        calculateQuotation();
      });
    });
  }
});
