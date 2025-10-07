// Update quantity
document.querySelectorAll('.qty').forEach(input => {
    input.addEventListener('change', function(){
        const row = input.closest('tr');
        const productId = row.dataset.id;
        const qty = input.value;

        fetch('add-to-cart.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `product_id=${productId}&quantity=${qty}`
        }).then(()=> location.reload());
    });
});

// Remove item
document.querySelectorAll('.remove').forEach(btn => {
    btn.addEventListener('click', function(){
        const row = btn.closest('tr');
        const productId = row.dataset.id;

        fetch('remove-cart.php', {
            method: 'POST',
            headers: {'Content-Type':'application/x-www-form-urlencoded'},
            body: `product_id=${productId}`
        }).then(()=> location.reload());
    });
});
