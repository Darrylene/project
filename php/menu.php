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
    <title>Menu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="src/components/menu.css">
</head>
<body>
    <a href="homepage.php" class="back-button">
        <i class="bi bi-arrow-left"></i>
    </a>

    <div class="container mt-4">
        <!-- Search Bar -->
        <div class="d-flex mb-3">
        <input type="text" id="search" class="form-control me-2 search-input" placeholder="Search...">
        <button class="btn custom-search-button">Search</button>
    </div>

        <!-- Category Buttons -->
        <div class="d-flex justify-content-center mb-4">
            <button class="category-button" data-category="all">
                <img src="espresso.png" alt="All">
                <p>All</p>
            </button>
            <button class="category-button" data-category="1">
                <img src="caffèmericano.png" alt="Hot Coffee">
                <p>Hot Coffee</p>
            </button>
            <button class="category-button" data-category="2">
                <img src="ice.png" alt="Iced Coffee">
                <p>Iced Coffee</p>
            </button>
            <button class="category-button" data-category="3">
                <img src="frappe.png" alt="Frappé">
                <p>Frappé</p>
            </button>
            <button class="category-button" data-category="4">
                <img src="milkshake.png" alt="Milkshake">
                <p>Milkshake</p>
            </button>
            <button class="category-button" data-category="5">
                <img src="tea.png" alt="Tea">
                <p>Tea</p>
            </button>
            <button class="category-button" data-category="6">
                <img src="choco.png" alt="Hot Choco">
                <p>Hot Choco</p>
            </button>
            <button class="category-button" data-category="7">
                <img src="dessert.png" alt="Dessert">
                <p>Dessert</p>
            </button>
        </div>

        <!-- Menu Pages -->
        <div class="menu-container">
            <div class="row row-cols-2 row-cols-md-5 menu-row" id="menu">
                <?php
                // Connect to the database
                $conn = new mysqli("localhost", "root", "", "login");

                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Fetch menu items from the database
                $sql = "SELECT menu_item_id, image_url, menu_item_name, price, menu_category_id FROM menuitems";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output each menu item
                    while ($row = $result->fetch_assoc()) {
                        // Check menu_category_id to determine the redirection
                        if (in_array($row['menu_category_id'], [1, 2, 3, 4, 5, 6])) {
                            // Redirect to drink.php for categories 1-6
                            echo '<button class="menu-button col menu-item" data-category="' . htmlspecialchars($row['menu_category_id']) . '" onclick="window.location.href=\'drink.php?id=' . htmlspecialchars($row['menu_item_id']) . '\'">';
                        } elseif ($row['menu_category_id'] == 7) {
                            // Redirect to dessert.php for category 7
                            echo '<button class="menu-button col menu-item" data-category="' . htmlspecialchars($row['menu_category_id']) . '" onclick="window.location.href=\'dessert.php?id=' . htmlspecialchars($row['menu_item_id']) . '\'">';
                        }
                        echo '<img src="' . htmlspecialchars($row['image_url']) . '" class="img-fluid" alt="' . htmlspecialchars($row['menu_item_name']) . '" style="width: 130px; height: 130px;">';
                        echo '<p>' . htmlspecialchars($row['menu_item_name']) . '</p>';
                        echo '<p class="menu-price">₱' . htmlspecialchars($row['price']) . '</p>';
                        echo '</button>';
                    }
                } else {
                    echo '<p>No menu items available.</p>';
                }

                // Close the connection
                $conn->close();
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/menu.js"></script>
    <script>
        // JavaScript for filtering menu items by category
        document.querySelectorAll('.category-button').forEach(button => {
            button.addEventListener('click', () => {
                const category = button.getAttribute('data-category');
                document.querySelectorAll('.menu-item').forEach(item => {
                    if (category === 'all' || item.getAttribute('data-category') === category) {
                        item.style.display = 'block';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>