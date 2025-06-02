<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log in.html");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Anaheim, sans-serif;
            background-color: #543310;
            color: #FFF2E1;
        }
        .header {
            background-color: #8C785E;
            color: #FFF2E1;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .content {
            padding: 2rem;
            background-color: #FFF2E1;
            color: #543310;
        }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #8C785E;
            color: #543310;
        }
        .cart-item:last-child {
            border-bottom: none;
        }
        .item-info {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .item-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .btn-remove {
            background-color: #e63946;
            color: #FFF2E1;
            border: none;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        .btn-remove:hover {
            background-color: #a32b3f;
        }
        .total {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
            font-size: 1.5rem;
            font-weight: bold;
        }
        #checkoutButton:hover {
            background-color: #543310;
        }
        .form-group label {
            color: #543310;
        }
    </style>
</head>
<body>
    <div class="header">
        <button class="btn btn-back d-flex align-items-center me-auto" onclick="window.location.href='menu.php';">
            <i class="bi bi-arrow-left"></i>
        </button>
        <h1>Shopping Cart</h1>
    </div>
    <div class="content">
        <div id="cartItems"></div>
        <div class="total">Total: ₱<span id="totalPrice">0.00</span></div>
        <div class="form-group mt-3">
            <label for="paymentMethod" class="text-color">Payment Method:</label>
            <select class="form-control" id="paymentMethod" style="background-color: #F4E6D4; color: #543310; border: none;">
                <option value="creditCard" style="background-color: #F4E6D4; color: #543310;">Credit Card</option>
                <option value="paypal" style="background-color: #F4E6D4; color: #543310;">PayPal</option>
                <option value="gcash" style="background-color: #F4E6D4; color: #543310;">GCash</option>
                <option value="grabPay" style="background-color: #F4E6D4; color: #543310;">GrabPay</option>
                <option value="debitCard" style="background-color: #F4E6D4; color: #543310;">Debit Card</option>
            </select>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button class="btn btn-primary" id="checkoutButton" style="background-color: #8C785E; border-color: #8C785E; color: #FFF2E1;" data-bs-toggle="modal" data-bs-target="#confirmationModal">
                <h5>Checkout</h5>
            </button>
        </div>
    </div>

    <!-- Bootstrap Confirmation Modal -->
    <div class="modal fade" id="confirmationModal" tabindex="-1" aria-labelledby="confirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="background-color: #FFF2E1; color: #543310;">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmationModalLabel">Confirm Checkout</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to checkout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Not Yet</button>
                    <button type="button" class="btn btn-primary" id="confirmCheckout" style="background-color: #8C785E; border-color: #8C785E;">Yes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Load cart items from localStorage
        const cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Function to render cart items
        function renderCartItems() {
            const cartItemsContainer = document.getElementById('cartItems');
            const totalPriceElement = document.getElementById('totalPrice');
            cartItemsContainer.innerHTML = '';
            let totalPrice = 0;

            cart.forEach((item, index) => {
                const cartItem = document.createElement('div');
                cartItem.classList.add('cart-item');
                
                const itemInfo = document.createElement('div');
                itemInfo.classList.add('item-info');
                itemInfo.innerHTML = `
                    <span>${item.name}</span>
                    <span>Size: ${item.size || 'N/A'}</span>
                    <span>Quantity: ${item.quantity}</span>
                    <span>Price: ${item.price}</span>
                `;

                const itemActions = document.createElement('div');
                itemActions.classList.add('item-actions');
                const removeButton = document.createElement('button');
                removeButton.classList.add('btn-remove');
                removeButton.textContent = 'Remove';
                removeButton.addEventListener('click', () => {
                    removeItemFromCart(index);
                });

                itemActions.appendChild(removeButton);
                cartItem.appendChild(itemInfo);
                cartItem.appendChild(itemActions);
                cartItemsContainer.appendChild(cartItem);

                const itemTotalPrice = parseFloat(item.price.replace('₱', '')) * item.quantity;
                totalPrice += itemTotalPrice;
            });

            totalPriceElement.textContent = totalPrice.toFixed(2);
        }

        // Function to remove item from cart
        function removeItemFromCart(index) {
            cart.splice(index, 1);
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCartItems();
        }

        // Render cart items on page load
        renderCartItems();

        const confirmCheckout = document.getElementById('confirmCheckout');
        confirmCheckout.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Your cart is empty!');
                return;
            }

            // Prepare menu_orders as a string
            const menuOrders = cart.map(item => `${item.name} x${item.quantity}`).join(', ');

            // Prepare order data
            const paymentMethod = document.getElementById('paymentMethod').value;
            const mappedPaymentMethod = paymentMethod === 'creditCard' ? 'Credit Card' : 'Online Payment';
            const orderData = {
                cart: cart,
                menuOrders: menuOrders, // Add menu_orders
                paymentMethod: mappedPaymentMethod,
                orderSource: 'Online'
            };

            // Send order data to the server
            fetch('process_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(orderData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Save order details to localStorage for order confirmation
                    localStorage.setItem('order', JSON.stringify(cart));
                    localStorage.setItem('paymentMethod', mappedPaymentMethod);

                    // Clear the cart
                    localStorage.removeItem('cart');

                    // Redirect to order confirmation page
                    window.location.href = 'orderconfirmation.html';
                } else {
                    alert('Failed to place the order. Please try again.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while processing your order.');
            });

            // Hide the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmationModal'));
            modal.hide();
        });
    </script>
</body>
</html>