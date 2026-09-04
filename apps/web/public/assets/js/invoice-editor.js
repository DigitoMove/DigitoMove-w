(() => {
  const container = document.getElementById('invoice-items');
  if (!container) return;
  const template = container.firstElementChild.cloneNode(true);
  const format = value => `UGX ${new Intl.NumberFormat('en-UG').format(value)}`;
  const update = () => {
    let total = 0;
    const rows = [...container.children];
    rows.forEach((row, i) => {
      row.querySelectorAll('[data-field]').forEach(input => { input.name = `items[${i}][${input.dataset.field}]`; });
      const quantity = Number(row.querySelector('[data-field="quantity"]').value) || 0;
      const price = Number(row.querySelector('[data-field="unit_price"]').value) || 0;
      row.querySelector('.line-total').textContent = format(quantity * price);
      row.querySelector('.remove-item').disabled = rows.length === 1;
      total += quantity * price;
    });
    document.getElementById('invoice-total').textContent = format(total);
    document.getElementById('add-item').disabled = rows.length >= 50;
  };
  container.addEventListener('input', update);
  container.addEventListener('click', event => {
    if (event.target.closest('.remove-item') && container.children.length > 1) {
      event.target.closest('.invoice-item').remove(); update();
    }
  });
  document.getElementById('add-item').addEventListener('click', () => {
    if (container.children.length >= 50) return;
    const row = template.cloneNode(true);
    row.querySelectorAll('[data-field]').forEach(input => { input.value = input.dataset.field === 'quantity' ? '1' : ''; });
    container.append(row); update(); row.querySelector('input').focus();
  });
  update();
})();
