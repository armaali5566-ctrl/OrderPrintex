/**
 * ORDERPRINTEX — Admin Portal Dynamic Calculations & Controls
 */

document.addEventListener('DOMContentLoaded', function () {
  'use strict';

  // 1. Admin Sidebar Toggle for Small Screens
  const sidebarToggle = document.querySelector('.dashboard-sidebar-toggle');
  const sidebar = document.querySelector('.dashboard-sidebar');
  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('open');
    });

    document.addEventListener('click', function (e) {
      if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && sidebar.classList.contains('open')) {
        sidebar.classList.remove('open');
      }
    });
  }

  // 2. Quotation Line Items Repeater & Dynamic Calculation
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

    // Update hidden inputs if present
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

    // Initial calculation
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
          <button type="button" class="btn btn-sm btn-outline text-crimson remove-row-btn">&times;</button>
        </td>
      `;
      itemsContainer.appendChild(tr);

      tr.querySelector('.remove-row-btn').addEventListener('click', function () {
        tr.remove();
        calculateQuotation();
      });

      calculateQuotation();
    });

    // Attach remove to existing rows
    itemsContainer.querySelectorAll('.remove-row-btn').forEach(btn => {
      btn.addEventListener('click', function () {
        btn.closest('.quote-item-row').remove();
        calculateQuotation();
      });
    });
  }
});
