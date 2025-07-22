let cartItems = {};

    function addToCart(id, name, price) {
        if (cartItems[id]) {
            cartItems[id].quantity++;
        } else {
            cartItems[id] = { id: id, name: name, price: price, quantity: 1 };
        }
        renderCart();
    }

function renderCart() {
    var cart = document.getElementById('cart');
    cart.innerHTML = '';

    var subtotal = 0;

    for (var id in cartItems) {
        var item = cartItems[id];
        subtotal += item.price * item.quantity;

        var itemDiv = document.createElement('div');
        itemDiv.className = 'cart-item d-flex align-items-center mb-2';

        // Left - name (flex:1, align left)
        // Center - price x qty (flex:1, text-center)
        // Right - buttons (flex:1, align right)

        itemDiv.innerHTML =

            '<div style="flex: 1; text-align: left;">' +
                '<strong>' + item.name + '</strong>' +
            '</div>' +
            '<div style="flex: 1; text-align: center;">' +
                '$' + item.price.toFixed(2) + ' x ' + item.quantity +
            '</div>' +
            '<div style="flex: 1; text-align: right;">' +
                '<button onclick="decrementQuantity(' + id + ')" class="btn btn-sm btn-outline-secondary me-1">-</button>' +
                '<button onclick="incrementQuantity(' + id + ')" class="btn btn-sm btn-outline-secondary me-2">+</button>' +
                '<button onclick="removeItem(' + id + ')" class="btn btn-sm btn-danger">&times;</button>' +
            '</div>';

        cart.appendChild(itemDiv);
    }

    updateTotals(subtotal);
    // Scroll to bottom of cart-area
var cartArea = document.getElementById('cart-area');
cartArea.scrollTo({
    top: cartArea.scrollHeight,
    behavior: 'smooth' // optional: for smooth scrolling
});
}

    function incrementQuantity(id) {
        cartItems[id].quantity++;
        renderCart();
    }

    function decrementQuantity(id) {
        if (cartItems[id].quantity > 1) {
            cartItems[id].quantity--;
        } else {
            delete cartItems[id];
        }
        renderCart();
    }

    function removeItem(id) {
        delete cartItems[id];
        renderCart();
    }

 function updateTotals(subtotal) {
    var taxRate = 0.1;
    var tax = subtotal * taxRate;
    var payout = subtotal + tax;

    // Update the respective spans
    document.getElementById('subtotal').textContent = '$' + subtotal.toFixed(2);
    document.getElementById('tax').textContent = '$' + tax.toFixed(2);
    document.getElementById('total').textContent = '$' + payout.toFixed(2);
}
// clear cart
function clearCart() {
    // Clear all items from the cart data
    cartItems = {};

    // Re-render the cart UI
    renderCart();
}


// ------------------ Order Cart

    // Before submitting, convert cartItems to JSON string for backend
document.addEventListener("DOMContentLoaded", function () {
    const orderForm = document.getElementById('orderForm');
    const cartDataInput = document.getElementById('cartData');

    if (orderForm && cartDataInput) {
        orderForm.addEventListener('submit', function(e) {
            const cartArray = Object.values(cartItems).map(item => ({
                product_id: item.id,
                price: item.price,
                quantity: item.quantity,
            }));
            cartDataInput.value = JSON.stringify(cartArray);
        });
    }
});

// ----------------------------- Proceed
$('#proceed').click(function () {
    let customerId = $('#customerSelect').val();

    if (!customerId) {
        alert('Please select a customer first!');
        return;
    }

    let cartArray = Object.values(cartItems).map(item => ({
        product_id: item.id,
        price: item.price,
        quantity: item.quantity,
    }));

    if (cartArray.length === 0) {
        alert('Your cart is empty!');
        return;
    }

    $.post('/orders/save', {
        customer_id: customerId,
        cart: JSON.stringify(cartArray)
    })
    .done(function (response) {
        alert(response.message || 'Order saved successfully!');
        clearCart();
    })
    .fail(function (xhr) {
        alert('Failed to save order: ' + xhr.responseText);
    });
});














