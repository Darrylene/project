<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: log in.html");
    exit();
}

// Connect to the database
$conn = new mysqli("localhost", "root", "", "login");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$sql = "SELECT FirstName, LastName FROM user WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
$conn->close();

// Check if user details were found
if (!$user) {
    error_log("User not found for user_id: $user_id"); // Log the issue
    header("Location: log in.html"); // Redirect to login page
    exit();
}

// Make user details available
$first_name = htmlspecialchars($user['FirstName']);
$last_name = htmlspecialchars($user['LastName']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brew & Blend Café</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Agbalumo:wght@400;700&family=Bricolage+Grotesque:wght@400;700&family=Open+Sans:wght@400;700&family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        /* Full-Screen Coffee Image */
        .coffee-image {
            width: 100%;
            height: 100vh;
            object-fit: cover;
            display: block;
        }

        /* Solid Navbar */
        .navbar {
            background-color: #543310;
            color: #fff;
            font-family: 'Agbalumo', sans-serif;
        }

        .navbar .navbar-brand {
            color: #fff;
            font-size: 1.8rem;
        }

        .navbar a {
            color: #fff;
        }

        .navbar-nav .nav-link {
            padding: 10px;
        }

        /* Sidebar */
        .sidebar {
            height: 100%;
            position: fixed;
            top: 0;
            left: -250px;
            width: 250px;
            background-color: #8C785E;
            padding-top: 50px;
            transition: left 0.3s ease;
            z-index: 1000;
        }

        .sidebar a {
            display: block;
            color: #fff2e1;
            padding: 10px;
            text-decoration: none;
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

        .sidebar a:hover {
            background-color: #543310;
        }

        .sidebar i {
            margin-right: 10px;
        }

        /* Main Content */
        .content {
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }

        /* Toggle Button */
        .toggle-btn {
            position: fixed;
            top: 5px;
            left: 5px;
            background-color: transparent;
            color: #fff2e1;
            border: none;
            font-size: 1.5rem;
            padding: 10px;
            cursor: pointer;
            z-index: 1001;
        }

        /* Close Button for Sidebar */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 1.5rem;
            color: #fff2e1;
            background: none;
            border: none;
            cursor: pointer;
        }

        /* Content */
        .carousel-item img {
            max-width: 100%;
            height: 80vh;
            object-fit: cover;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Roboto', sans-serif; /* Default font for the body */
            background-color: #543310;
            color: #fff2e1;
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        /* Best Sellers Section */
        .best-sellers-coffee {
            background-color: #543310;
            padding: 50px 0;
            text-align: center;
        }

        .best-sellers-coffee h2 {
            font-family: 'Agbalumo', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
            color: #fff2e1;
        }

        .best-sellers-coffee .carousel-inner {
            height: 400px;
        }

       /* Best Sellers Desserts Section */
        .best-sellers-desserts .carousel-item img {
            display: block;
            margin: 0 auto;
            width: 220px;  /* Adjust this value to match other images */
            height: 220px; /* Uniform height for consistency */
            object-fit: cover;  /* Ensures the image doesn't get distorted */
            border-radius: 15px; /* Optional: add rounded corners */
        }

        .carousel-caption {
            position: relative;
            text-align: center;
            left: 10px;
        }
        .spacer {
            height: 50px; 
        }

        .divider {
            height: 2px;
            background-color: #D2B48C;
            margin: 50px 0;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        .about-us {
            background-color: #a79277;
            padding: 60px 0;
            text-align: center;
            color: #fff2e1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; 
            align-items: center;
            height: 400px;
        }

        .about-us h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-top: -30px;
            align-self: flex-start; 
        }

        .about-us p {
            font-family: 'Open Sans', sans-serif;
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto;
            line-height: 1.6;
            margin-top: 80px; 
        }

        .comment-section {
            background-color: #a79277;
            width: 100%; 
            max-width: 1200px; 
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
        }

        .comment-section h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .comment-section p {
            font-family: 'Open Sans', sans-serif;
            font-size: 1.2rem;
            margin-bottom: 30px;
        }

        .feedback-section {
            background-color: #a79277;
            width: 100%; 
            height: 600px;
            max-width: 1200px; 
            padding: 30px;
            border-radius: 20px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            margin: 0 auto;
        }

        .feedback-section h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 700;
            font-size: 2.5rem;
            margin-bottom: 30px;
        }

        .feedback-section p {
            font-family: 'Open Sans', sans-serif;
            font-size: 1.2rem;
            margin-bottom: 30px; 
        }

        .star-rating {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .star {
            font-size: 40px;
            color: rgb(75, 72, 72);
            cursor: pointer;
            transition: color 0.2s ease-in-out;
        }

        .star:hover, 
        .star.selected {
            color: yellow;
        }

        .star-rating {
            margin-bottom: 30px;
        }

        .feedback-input {
            width: 100%;
            padding: 10px;
            font-size: 1rem;
            border-radius: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .submit-btn {
            padding: 10px 20px;
            background-color: #8C785E;
            color: #fff2e1;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .submit-btn:hover {
            background-color: #8C785E;
        }
        .checkbox {
            transform: scale(1.5); /* Adjust the number to make it bigger or smaller */
        }

        .comment-card {
            background-color: #8C785E;
            padding: 2rem;
            border-radius: 8px;
            text-align: center;
            color: #fff2e1;
            height: 100%;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .comment-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 1rem;
        }

        .comment-card h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 1.25rem;
            font-weight: bold;
        }

        .comment-card p {
            font-family: 'Open Sans', sans-serif;
            font-size: 0.9rem;
            margin-top: 0.5rem;
        }

        .stars {
            color: gold;
            margin: 0.5rem 0;
        }

        .footer {
            background-color: #8C785E;
            padding: 1.5rem 0;
            text-align: center;
        }

        .footer a {
            color: #fff2e1;
            text-decoration: none;
            margin: 0.5rem;
            display: inline-block;
            font-family: 'Open Sans', sans-serif;
        }

        .footer-logo img {
            width: 100px;
        }

        .footer a i {
            color: #543310; 
            font-size: 2rem; 
        }

        .footer a i:hover {
            color: #fff2e1; 
        }

        /* Custom fade animation for carousels */
        .carousel-fade .carousel-item {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }

        .carousel-fade .carousel-item.active {
            opacity: 1;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <img src="b&blogo.png" class="d-block mx-auto" alt="Logo" style="width: 50px; height: 50px;">
            <a class="navbar-brand" href="#">Brews That Bring You Home</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="homepage.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="menu.php">Menu</a></li>
                    <li class="nav-item"><a class="nav-link" href="shoppingcart.html">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="StoreLocator.html">Store Locator</a></li>
                </ul>
                <span class="ms-auto text-white">Welcome <?php echo $first_name . ', ' . $last_name; ?></span>
            </div>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <button class="close-btn" onclick="toggleSidebar()">&times;</button>
        <a href="myaccount.php"><i class="fas fa-user"></i> My Account</a>
        <a href="giftcard.html"><i class="fas fa-gift"></i> Gift Cards</a>
        <a href="#"><i class="fas fa-tags"></i> Promotions</a>
        <a href="#"><i class="fas fa-calendar-alt"></i> Events</a>
        <a href="Return&Refunds.html"><i class="fas fa-undo-alt"></i> Return & Refund</a>
        <a href="#" id="logoutBtn"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>

    <!-- Toggle Button -->
    <button class="toggle-btn" onclick="toggleSidebar()">☰</button>

    <!-- Main Content -->
    <div id="imageSlider" class="carousel slide" data-bs-ride="carousel" data-bs-wrap="true">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="order.jpeg" class="d-block mx-auto" alt="Strawberry Latte" style="width: 1790px; height: 900px;">
            </div>
            <div class="carousel-item">
                <img src="470050699_2002605550236366_3228946526130812084_n.png" class="d-block mx-auto" alt="Strawberry Latte" style="width: 1790px; height: 1000px;">
            </div>
            <div class="carousel-item">
                <img src="coffee (2) (1).jpg" class="d-block mx-auto" alt="Strawberry Latte" style="width: 1790px; height: 1000px;">
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#imageSlider" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#imageSlider" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Best Sellers Section -->
    <div class="best-sellers-coffee">
        <h2>Best Sellers Coffee</h2>
        <div id="carousel1" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="strawberrylatte.png" class="d-block mx-auto" alt="Strawberry Latte" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Strawberry Latte</h5>
                        <p>₱150.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="caramelhazelnut.png" class="d-block mx-auto" alt="Caramel Hazelnut" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Caramel Hazelnut</h5>
                        <p>₱170.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="doublechoco.png" class="d-block mx-auto" alt="Double Chocolate Chip" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Double Chocolate Chip</h5>
                        <p>₱160.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="ubedream.png" class="d-block mx-auto" alt="Ube Dream" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Ube Dream</h5>
                        <p>₱160.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Best Sellers Desserts Section -->
    <div class="best-sellers-coffee">
        <h2>Best Sellers Dessert</h2>
        <div id="carousel2" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="3000">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="macaroon.png" class="d-block mx-auto" alt="Dessert 1" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Macaron</h5>
                        <p>₱90.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="croissant.png" class="d-block mx-auto" alt="Dessert 2" style="width: 290px; height: 290px;">
                    <div class="carousel-caption">
                        <h5>Chocolate Croissant</h5>
                        <p>₱120.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="raspberrylemonbar.png" class="d-block mx-auto" alt="Dessert 3" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Raspberry Lemon Bar</h5>
                        <p>₱196.00</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="cherrymaltmilkshakecupcake.png" class="d-block mx-auto" alt="Cherry Malt Milkshake Cupcake" style="width: 220px; height: 300px;">
                    <div class="carousel-caption">
                        <h5>Cherry Malt Milkshake Cupcake</h5>
                        <p>₱220.00</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div id="about-us" class="about-us">
        <div>
            <img src="" alt="">
            <h2>ABOUT US</h2>
            <p>Brew & Blend Café is an online store specializing in ready-to-drink coffee and specialty beverages. With a focus on convenience and quality, they provide a curated selection of cold brews, lattes, and other drinks, making great coffee easily accessible for coffee lovers at home.</p>
        </div>
    </div>

    <div class="spacer"></div>

    <div class="divider"></div>

    <!-- Feedback Section -->
    <div id="feedback-section" class="feedback-section">
        <h2>We value your opinion!</h2>
        <p>How would you rate your overall experience?</p>
        <div class="star-rating">
            <span class="star" data-value="1">&#9733;</span>
            <span class="star" data-value="2">&#9733;</span>
            <span class="star" data-value="3">&#9733;</span>
            <span class="star" data-value="4">&#9733;</span>
            <span class="star" data-value="5">&#9733;</span>
        </div>
        <p>Kindly take a moment to tell us what you think.</p>
        <textarea class="feedback-input" rows="4" placeholder="Share your feedback"></textarea>
        
        <!-- Anonymous option -->
        <div class="anonymous-option">
            <label for="anonymous">
                <input type="checkbox" id="anonymous" name="anonymous" class="checkbox"> Submit anonymously
            </label>
        </div>
        <br>
        <button class="submit-btn">Share your feedback</button>
    </div>
    
    <div class="divider"></div>

    <!-- Comment Section -->
    <div class="container comment-section py-5">
        <h2>Comment Section</h2>
        <div class="row">
            <!-- Card 1 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="comment-card w-100">
                    <img src="alice.png" alt="Alice Guo" style="width: 130px; height: 130px;">
                    <h4>Alice Guo</h4>
                    <div class="stars">★★★★★</div>
                    <p>Beautiful ambiance, fast delivery, and welcoming staff make this store a gem! Perfect waffles and lattes!</p>
                </div>
            </div>
            <!-- Card 2 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="comment-card w-100">
                    <img src="wilkins.png" alt="Wilkins Custodio" style="width: 130px; height: 130px;">
                    <h4>Wilkins Custodio</h4>
                    <div class="stars">★★★★★</div>
                    <p>Brew & Blend's cozy atmosphere and affordable prices make it my favorite place to visit!</p>
                </div>
            </div>
            <!-- Card 3 -->
            <div class="col-md-4 mb-4 d-flex align-items-stretch">
                <div class="comment-card w-100">
                    <img src="dem.png" alt="Demrose Gangan" style="width: 130px; height: 130px;">
                    <h4>Demrose Gangan</h4>
                    <div class="stars">★★★★★</div>
                    <p>The ambiance is cozy, and the staff is quick and friendly. Highly recommended!</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <div class="row">
                <!-- Footer Logo -->
                <div class="col-md-4 footer-logo text-left">
                    <img src="b&blogo.png" alt="Brew & Blend Logo">
                </div>
                <!-- Links -->
                <div class="col-md-4">
                    <p>
                        <a href="#about-us">About Us</a> |
                        <a href="#feedback-section">Feedback</a> |
                        <a href="#">Terms of Service</a> |
                        <a href="#">FAQs</a> |
                        <a href="#">Returns & Refunds</a> |
                        <a href="#">Privacy Policy</a> |
                        <a href="#" id="contactUsLink">Contact Us</a>
                    </p>
                </div>
                <!-- Social Media -->
                <div class="col-md-4">
                    <p>Follow us on:</p>
                    <a href="https://www.facebook.com/"><i class="fab fa-facebook"></i></a>
                    <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/"><i class="fab fa-tiktok"></i></a>
                    <a href="https://twitter.com/i/moments"><i class="fab fa-twitter"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Message Inquiry Modal -->
<div id="messageInquiryModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Message Inquiry</h2>
        <form id="messageInquiryForm">
            <label for="fullName">Full Name:</label>
            <input type="text" id="fullName" name="fullName" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="4" required></textarea>
            
            <label for="date">Date:</label>
            <input type="text" id="date" name="date" readonly>
            
            <button type="submit">Submit</button>
            <button type="button" id="cancelButton">Cancel</button>
        </form>
    </div>
</div>

<style>

    :root {
        --darkbrown: #543310;
        --lightbrown: #8C785E;
        --CoffeeLight: #F4E6D4;
        --CoffeeLight1: #AB947A;
        --white: #fff;
        --black: #000000;
        --brownhover: #d1bb9e;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content {
        background-color: var(--CoffeeLight);
        margin: 10% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 50%;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        font-family: 'Open Sans', sans-serif;
    }

    .modal-content h2 {
        text-align: center;
        font-size: 1.8rem;
        margin-bottom: 20px;
        color: #543310;
    }

    .modal-content label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #543310;
    }

    .modal-content input,
    .modal-content textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 1rem;
    }

    .modal-content button {
        padding: 10px 20px;
        margin-right: 10px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
    }

    .modal-content button[type="submit"] {
        background-color: #543310;
        color: #fff;
    }

    .modal-content button[type="submit"]:hover {
        background-color: #8C785E;
    }

    .modal-content button#cancelButton {
        background-color: #ccc;
        color: #000;
    }

    .modal-content button#cancelButton:hover {
        background-color: #aaa;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
</style>

    <script>
        const stars = document.querySelectorAll(".star");
        let selectedRating = 0; // Keeps track of the selected rating (0 means no rating)
    
        // Function to highlight stars dynamically based on hover or selected state
        function highlightStars(starIndex) {
            stars.forEach((star, index) => {
                if (index <= starIndex) {
                    star.classList.add("highlight");
                } else {
                    star.classList.remove("highlight");
                }
            });
        }
    
        // Function to update stars based on the selected rating
        function updateSelectedStars() {
            stars.forEach((star, index) => {
                if (index < selectedRating) {
                    star.classList.add("selected");
                } else {
                    star.classList.remove("selected");
                }
            });
        }
    
        // Add event listeners for each star
        stars.forEach((star, index) => {
            // Highlight stars on hover
            star.addEventListener("mouseover", () => highlightStars(index));
    
            // Reset highlights when the mouse leaves
            star.addEventListener("mouseout", () => {
                if (selectedRating === 0) {
                    // Clear all highlights if no rating is selected
                    stars.forEach(star => star.classList.remove("highlight"));
                } else {
                    // Restore highlight to the selected rating
                    highlightStars(selectedRating - 1);
                }
            });
    
            // Set or clear the selected rating on click
            star.addEventListener("click", () => {
                if (selectedRating === index + 1) {
                    // Clear selection if the same star is clicked
                    selectedRating = 0;
                } else {
                    // Update the selected rating
                    selectedRating = index + 1;
                }
                updateSelectedStars(); // Update star display
            });
        });

        // Function to toggle the sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const isOpen = sidebar.style.left === '0px';
            sidebar.style.left = isOpen ? '-250px' : '0px';
        }

        // Logout functionality
        document.getElementById("logoutBtn").addEventListener("click", function (event) {
            event.preventDefault(); // Prevent default navigation

            // Clear session on the server
            fetch('logout.php', { method: 'POST' })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Redirect to the login page
                        window.location.href = 'log in.html';
                    } else {
                        alert('Logout failed. Please try again.');
                    }
                })
                .catch(error => {
                    console.error('Error during logout:', error);
                });
        });

        // Synchronize the Coffee and Dessert carousels
        const coffeeCarousel = new bootstrap.Carousel('#carousel1', {
            interval: 3000,
            ride: 'carousel'
        });

        const dessertCarousel = new bootstrap.Carousel('#carousel2', {
            interval: 3000,
            ride: 'carousel'
        });

        // Synchronize both carousels to change at the same time
        const syncCarousels = () => {
            coffeeCarousel.next();
            dessertCarousel.next();
        };

        // Set intervals to synchronize both carousels
        setInterval(syncCarousels, 3000);

        // Modal-related code
        const messageInquiryModal = document.getElementById("messageInquiryModal");
        const contactUsLink = document.getElementById("contactUsLink");
        const closeModalButton = document.querySelector(".modal .close");
        const cancelButton = document.getElementById("cancelButton");
        const messageInquiryForm = document.getElementById("messageInquiryForm");
        const dateInput = document.getElementById("date");

        contactUsLink.addEventListener("click", (event) => {
            event.preventDefault();
            messageInquiryModal.style.display = "block";
            const today = new Date().toISOString().split("T")[0];
            if (dateInput) {
                dateInput.value = today; // Set the current date in the date input
            }
        });

        closeModalButton.addEventListener("click", () => {
            messageInquiryModal.style.display = "none";
        });

        cancelButton.addEventListener("click", () => {
            messageInquiryModal.style.display = "none";
        });

        window.addEventListener("click", (event) => {
            if (event.target == messageInquiryModal) {
                messageInquiryModal.style.display = "none";
            }
        });

        messageInquiryForm.addEventListener("submit", (event) => {
            event.preventDefault();
            const formData = new FormData(messageInquiryForm);
            const fullName = formData.get("fullName");
            const email = formData.get("email");
            const message = formData.get("message");
            const date = formData.get("date");

            const inquiries = JSON.parse(localStorage.getItem("inquiries")) || [];
            inquiries.push({ fullName, email, message, date });
            localStorage.setItem("inquiries", JSON.stringify(inquiries));

            messageInquiryModal.style.display = "none";
            messageInquiryForm.reset();
            alert("Your inquiry has been submitted!");
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>