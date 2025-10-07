
  // Quantity and total update logic Cart Page
  const qtyInputs = document.querySelectorAll('.qty');
  const subtotalEl = document.getElementById('subtotal');
  const grandtotalEl = document.getElementById('grandtotal');
  const removeBtns = document.querySelectorAll('.remove');

  function updateTotals() {
    let subtotal = 0;
    document.querySelectorAll('tbody tr').forEach(row => {
      const price = parseFloat(row.children[1].textContent.replace('$',''));
      const qty = parseInt(row.querySelector('.qty').value);
      const total = price * qty;
      row.querySelector('.total').textContent = `$${total}`;
      subtotal += total;
    });
    subtotalEl.textContent = `$${subtotal}`;
    grandtotalEl.textContent = `$${subtotal + 5}`;
  }

  qtyInputs.forEach(input => {
    input.addEventListener('input', updateTotals);
  });

  removeBtns.forEach(btn => {
    btn.addEventListener('click', e => {
      e.target.closest('tr').remove();
      updateTotals();
    });
  });



  document.getElementById("checkoutForm").addEventListener("submit", function(e){
  e.preventDefault();
  
  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const phone = document.getElementById("phone").value.trim();
  const address = document.getElementById("address").value.trim();
  const payment = document.getElementById("payment").value;

  if(!name || !email || !phone || !address || !payment){
    alert("Please fill all required fields!");
    return;
  }

  alert("✅ Order placed successfully!");
  window.location.href = "success.php"; // redirect after success
});