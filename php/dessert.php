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
    <title>Dessert Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* ...styles from chocotiramisu.html... */
        body {
            margin: 0;
            padding: 0;
            font-family: Anaheim, sans-serif;
            height: 100vh;
            overflow: hidden; 
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
            display: flex;
            flex-direction: column;
            height: 100%;
        }
        .section-1 {
            background-color: #543310;
            padding: 2rem;
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            flex: 1;
            overflow: hidden; 
        }
        .section-1 .product-image {
            display: flex;
            justify-content: center;
            flex: 1 1 300px;
        }
        .section-1 .product-info {
            flex: 2 1 600px;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            gap: 2rem;
        }
        .product-info h1 {
            font-size: 2.5rem;
            margin: 0;
            font-family: Agbalumo, sans-serif;
        }
        .section-1 .text-color {
            color: #FFF2E1;
        }
        .quantity-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .quantity-container button {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            background-color: #FFF2E1;
            border: 1px solid #FFF2E1;
            color: #543310;
        }
        .quantity-container button:hover {
            background-color: #543310;
            color: #FFF2E1;
        }
        .price {
            font-size: 1.5rem;
            margin-left: 1rem;
            font-family: Adamina, serif;
            font-weight: 700;
        }
        .section-2 {
            background-color: #FFF2E1;
            padding: 2rem;
            flex: 1;
        }
        .section-2 .text-color {
            color: #543310;
            margin-left: 20px;
            font-family: Adamina, serif;
            font-weight: 700;
        }
        .cart-summary {
            position: relative;
        }
        .cart-summary .cart-count {
            position: absolute;
            top: -10px;
            right: -10px;
            background-color: red;
            color: #FFF2E1;
            border-radius: 50%;
            padding: 0.2rem 0.5rem;
            font-size: 0.8rem;
            font-family: Anaheim, sans-serif;
        }
        .btn-back {
            color: #FFF2E1;
            font-size: 1.5rem;
            padding: 0.5rem 1rem;
            width: auto;
            height: auto;
            font-family: Anaheim, sans-serif;
        }
        .btn-cart-summary {
            color: #FFF2E1;
            background-color: #543310;
            border: none;
        }
        .btn-primary {
            background-color: #8C785E;
            border-color: #8C785E;
            color: #FFF2E1;
        }
        .btn-favorites {
            background: #e63946;
            border: 2px solid #e63946;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .btn-favorites.active {
            background: #a79277;
            color: #fff;
        }
    </style>
</head>
<body>
    <?php
    // Connect to the database
    $conn = new mysqli("localhost", "root", "", "login");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Get the dessert ID from the query parameter
    $dessert_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    // Fetch dessert details from the database
    $sql = "SELECT menu_item_name, price, description, image_url FROM menuitems WHERE menu_item_id = ? AND menu_category_id = 7";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $dessert_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the dessert exists
    if ($result->num_rows > 0) {
        $dessert = $result->fetch_assoc();
    } else {
        echo "<p>Dessert not found.</p>";
        exit;
    }

    // Close the database connection
    $stmt->close();
    $conn->close();
    ?>

    <div class="header">
        <button class="btn btn-back d-flex align-items-center me-auto" onclick="window.location.href='menu.php';">
            <i class="bi bi-arrow-left"></i>
        </button>
        <div class="cart-summary">
            <button class="btn btn-cart-summary" onclick="window.location.href='shoppingcart.php';">
                <i class="bi bi-cart"></i>
            </button>
            <span class="cart-count" id="cartCount">0</span>
        </div>
    </div>

    <div class="content">
        <!-- First Section -->
        <div class="section-1">
            <div class="product-image">
                <img src="<?php echo htmlspecialchars($dessert['image_url']); ?>" alt="<?php echo htmlspecialchars($dessert['menu_item_name']); ?>" style="height: 260px; width: 210px;">
            </div>
            <div class="product-info">
                <h1 class="text-color"><?php echo htmlspecialchars($dessert['menu_item_name']); ?></h1>
                <div class="quantity-container">
                    <button class="btn btn-outline-secondary" onclick="decreaseQuantity()">
                        <i class="bi bi-dash"></i>
                    </button>
                    <span id="quantity" class="text-color">0</span>
                    <button class="btn btn-outline-secondary" onclick="increaseQuantity()">
                        <i class="bi bi-plus"></i>
                    </button>
                    <span class="price text-color">₱<?php echo htmlspecialchars($dessert['price']); ?></span>
                </div>
            </div>
        </div>

        <!-- Second Section -->
        <div class="section-2">
            <h5 class="text-color"><?php echo htmlspecialchars($dessert['description']); ?></h5>
            <br>
            <br>
            <div class="d-flex justify-content-end">
                <button class="btn btn-primary" id="addToCartButton">
                    <h5>Add to Order</h5>
                </button>
                <button class="btn btn-favorites ms-2">Add to Favorites</button>
            </div>
        </div>
    </div>

    <script>
        function decreaseQuantity() {
            const quantitySpan = document.getElementById('quantity');
            let quantity = parseInt(quantitySpan.innerText);
            if (quantity > 0) {
                quantity--;
                quantitySpan.innerText = quantity;
            }
        }

        function increaseQuantity() {
            const quantitySpan = document.getElementById('quantity');
            let quantity = parseInt(quantitySpan.innerText);
            quantity++;
            quantitySpan.innerText = quantity;
        }

        function updateCartCount() {
            const cart = JSON.parse(localStorage.getItem("cart")) || [];
            const totalItems = cart.reduce((total, item) => total + item.quantity, 0);
            document.getElementById("cartCount").innerText = totalItems;
        }

        document.addEventListener("DOMContentLoaded", () => {
            // Initialize cart count
            updateCartCount();

            // Handle Favorites button toggle
            const favoritesButton = document.querySelector(".btn-favorites");
            favoritesButton.addEventListener("click", () => {
                favoritesButton.classList.toggle("active");
            });

            // Add to Cart functionality
            const addToCartButton = document.getElementById("addToCartButton");
            addToCartButton.addEventListener("click", () => {
                const quantitySpan = document.getElementById("quantity");
                const quantity = parseInt(quantitySpan.innerText);
                const productName = document.querySelector(".product-info h1").innerText;
                const price = document.querySelector(".price").innerText;

                if (quantity === 0) {
                    alert("Please select a quantity before adding to the cart.");
                    return;
                }

                const cart = JSON.parse(localStorage.getItem("cart")) || [];
                const item = {
                    name: productName,
                    quantity: quantity,
                    price: price
                };
                cart.push(item);
                localStorage.setItem("cart", JSON.stringify(cart));

                // Update cart count
                updateCartCount();

                alert(`${quantity} ${productName} added to the cart!`);
            });
        });
    </script>
</body>
</html>
