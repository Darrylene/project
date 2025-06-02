-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2025 at 06:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitytracking`
--

CREATE TABLE `activitytracking` (
  `activity_id` int(11) NOT NULL,
  `activity_type` varchar(255) NOT NULL,
  `timestamp` datetime NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `status` enum('Success','Failed','Pending') NOT NULL,
  `remarks` text DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditlogs`
--

CREATE TABLE `auditlogs` (
  `log_id` int(11) NOT NULL,
  `action_type` varchar(255) NOT NULL,
  `performed_by` int(11) DEFAULT NULL,
  `affected_user_id` int(11) DEFAULT NULL,
  `timestamp` datetime NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Baking Essentials'),
(2, 'Dairy'),
(3, 'Spices '),
(4, 'Chocolate'),
(5, 'Beverages'),
(6, 'Specialty Coffee'),
(7, 'Alcoholic Beverages');

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `delivery_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `delivery_address` text NOT NULL,
  `delivery_status` enum('Pending','Out for Delivery','Delivered','Failed') NOT NULL,
  `delivery_date` datetime NOT NULL,
  `CustName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`delivery_id`, `order_id`, `delivery_address`, `delivery_status`, `delivery_date`, `CustName`) VALUES
(1, 7, 'Blk 6A Lot 8 Ocampo St Terrassa 2 Buhay na Tubig Imus Cavite', 'Pending', '2025-04-24 21:50:26', 'Amante Jr Amurao');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_assignments`
--

CREATE TABLE `delivery_assignments` (
  `assignment_id` int(11) NOT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `order_id` int(11) DEFAULT NULL,
  `assignment_date` datetime NOT NULL,
  `delivery_status` enum('Assigned','In Transit','Delivered','Failed') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_type` varchar(255) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date_incurred` datetime NOT NULL,
  `description` text DEFAULT NULL,
  `ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ingredients`
--

CREATE TABLE `ingredients` (
  `ingredient_id` int(11) NOT NULL,
  `ingredient_name` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `quantity_in_stock` decimal(10,2) NOT NULL,
  `unit` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ingredients`
--

INSERT INTO `ingredients` (`ingredient_id`, `ingredient_name`, `supplier_id`, `quantity_in_stock`, `unit`) VALUES
(1, 'Caster Sugar', 1, 5000.00, 'grams'),
(2, 'Large Free-Range Eggs', 10, 300.00, 'pieces'),
(3, 'Unsalted Butter', 2, 2000.00, 'grams'),
(4, 'Plain Flour', 8, 10000.00, 'grams'),
(5, 'Cocoa Powder', 3, 1000.00, 'grams'),
(6, 'White Chocolate', 4, 500.00, 'grams'),
(7, 'Milk', 2, 100.00, 'liters'),
(8, 'Mascarpone Cheese', 7, 300.00, 'grams'),
(9, 'Egg Yolk', 10, 200.00, 'pieces'),
(10, 'Sweet Dessert Wine', 9, 1000.00, 'milliliters'),
(11, 'Tia Maria (Coffee Liqueur)', 5, 500.00, 'milliliters'),
(12, 'Espresso Coffee', 6, 1000.00, 'milliliters'),
(13, 'Dark Chocolate', 4, 500.00, 'grams');

-- --------------------------------------------------------

--
-- Table structure for table `menuitems`
--

CREATE TABLE `menuitems` (
  `menu_item_id` int(11) NOT NULL,
  `menu_item_name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `menu_category_id` int(11) DEFAULT NULL,
  `ingredients_used` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menuitems`
--

INSERT INTO `menuitems` (`menu_item_id`, `menu_item_name`, `description`, `price`, `image_url`, `menu_category_id`, `ingredients_used`) VALUES
(1, 'Chocolate Tiramisu', 'Chocolate Tiramisu combines creamy mascarpone, espresso-soaked ladyfingers, and rich chocolate ganache, all topped with a dusting of cocoa for a perfect blend of sweetness and coffee flavor.\n', 220.00, 'chocotiramisu.png', 7, NULL),
(2, 'Americano', 'A bold and smooth classic, our Americano blends rich espresso with hot water for a perfectly balanced, robust coffee experience. Simple yet full of flavor.\n', 120.00, 'caffèmericano.png', 1, NULL),
(3, 'Oreo Iced Coffee', 'Indulge in our Oreo Iced Coffee, a delightful blend of rich coffee and creamy milk, enhanced with crunchy Oreo cookie pieces and a drizzle of chocolate, making for a refreshing and sweet treat perfect for coffee lovers.\n', 195.00, 'oreoicedcoffee.png', 2, NULL),
(4, 'Double Chocolate Chip', 'Indulge in our Double Chocolate Chip Frappuccino, a creamy blend of rich chocolate, chocolate chips, and espresso, topped with whipped cream for an irresistibly decadent treat that’s perfect for chocolate lovers.\n', 200.00, 'doublechoco.png', 3, NULL),
(5, 'Red Velvet Milkshake', 'Indulge in the rich, creamy goodness of our Red Velvet Milkshake, blending the classic flavors of red velvet cake with smooth vanilla ice cream for a decadent, sweet treat.\n', 180.00, 'redvelvetmilkshake.png', 4, NULL),
(6, 'Dalgona Coffee', 'Dalgona Coffee features a frothy, creamy coffee layer atop chilled milk, delivering a smooth and velvety coffee experience with every sip.\n', 140.00, 'dalgonacoffee.png', 2, NULL),
(7, 'Cinnamon Honey Tea', 'A soothing blend of warm cinnamon and sweet honey, this tea offers comforting flavors with a touch of natural sweetness.\n', 120.00, 'cinnamonhoneytea.png', 5, NULL),
(8, 'Matcha Hot Chocolate', 'Experience a delightful twist on a classic with our Matcha Hot Chocolate, blending rich cocoa with smooth matcha green tea for a creamy, comforting drink that\'s both indulgent and energizing.', 150.00, 'matchahotchoco.png', 6, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `menu_category_id` int(11) NOT NULL,
  `menu_category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`menu_category_id`, `menu_category_name`, `description`) VALUES
(1, 'Hot Coffee', NULL),
(2, 'Iced Coffee', NULL),
(3, 'Frappe', NULL),
(4, 'Milkshake', NULL),
(5, 'Tea', NULL),
(6, 'Hot Choco', NULL),
(7, 'Dessert', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `message_content` text NOT NULL,
  `message_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Pending','Reviewed','Resolved') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `menu_orders` varchar(255) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_source` enum('Online','In-Store') NOT NULL,
  `payment_method` enum('Cash','Credit Card','Online Payment') NOT NULL,
  `order_status` enum('Pending','In-Transit','Returned','Delivered','Cancelled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `ID`, `menu_orders`, `quantity`, `total_price`, `order_source`, `payment_method`, `order_status`) VALUES
(1, '2025-04-22 15:40:56', 1, 'Chocolate Tiramisu x3, Americano x3', 6, 1020.00, 'Online', 'Credit Card', 'Pending'),
(2, '2025-04-22 15:44:12', 2, 'Americano x3', 3, 360.00, 'Online', 'Online Payment', 'Pending'),
(3, '2025-04-23 13:29:28', 2, 'Oreo Iced Coffee x3, Chocolate Tiramisu x3', 6, 1245.00, 'Online', 'Online Payment', 'Pending'),
(4, '2025-04-24 13:41:43', 1, 'Cinnamon Honey Tea x2', 2, 240.00, 'Online', 'Credit Card', 'Pending'),
(5, '2025-04-24 14:01:51', 1, 'Americano x2, Chocolate Tiramisu x2', 4, 680.00, 'Online', 'Online Payment', 'Pending'),
(6, '2025-04-24 14:06:53', 2, 'Chocolate Tiramisu x2', 2, 440.00, 'Online', 'Online Payment', 'Pending'),
(7, '2025-04-24 15:41:43', 3, 'Chocolate Tiramisu x2, Americano x2', 4, 680.00, 'Online', 'Online Payment', 'In-Transit'),
(8, '2025-05-05 10:02:22', 2, 'Americano x2', 2, 240.00, 'Online', 'Online Payment', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price_per_unit` decimal(10,2) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `stock_level` int(11) NOT NULL,
  `reorder_level` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `price_per_unit`, `unit`, `stock_level`, `reorder_level`) VALUES
(1, 'Caster Sugar', 1, 2.50, 'grams', 5000, 1000),
(2, 'Large Free-Range Eggs', 2, 0.20, 'pieces', 300, 50),
(3, 'Unsalted Butter', 2, 3.00, 'grams', 2000, 500),
(4, 'Plain Flour', 1, 1.50, 'grams', 10000, 2000),
(5, 'Cocoa Powder', 1, 4.00, 'grams', 1000, 200),
(6, 'White Chocolate', 4, 5.00, 'grams', 500, 100),
(7, 'Milk', 2, 1.00, 'liters', 100, 20),
(8, 'Mascarpone Cheese', 2, 6.00, 'grams', 300, 50),
(9, 'Egg Yolk', 2, 0.15, 'pieces', 200, 40),
(10, 'Sweet Dessert Wine', 7, 10.00, 'milliliters', 1000, 200),
(11, 'Tia Maria (Coffee Liqueur)', 7, 15.00, 'milliliters', 500, 100),
(12, 'Espresso Coffee', 6, 2.00, 'milliliters', 1000, 200),
(13, 'Dark Chocolate', 4, 5.00, 'grams', 500, 100);

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

CREATE TABLE `riders` (
  `rider_id` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `ContactNumber` varchar(20) NOT NULL,
  `VehicleType` enum('Motorcycle','Bike','Car') NOT NULL,
  `LicensePlate` varchar(20) DEFAULT NULL,
  `Status` enum('Active','On Leave','Suspended') DEFAULT 'Active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rider_activity_logs`
--

CREATE TABLE `rider_activity_logs` (
  `log_id` int(11) NOT NULL,
  `rider_id` int(11) DEFAULT NULL,
  `action_type` enum('Picked Up Order','Delivered Order','Failed Delivery') NOT NULL,
  `timestamp` datetime NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stocktransactions`
--

CREATE TABLE `stocktransactions` (
  `transaction_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity_change` int(11) NOT NULL,
  `transaction_type` enum('Restock','Sale','Adjustment') NOT NULL,
  `transaction_date` datetime NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `supplier_id` int(11) NOT NULL,
  `supplier_name` varchar(255) NOT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`supplier_id`, `supplier_name`, `contact_details`, `address`) VALUES
(1, 'Sweet Harvest Ltd.', '+63 900 123 4567', 'Manila, Philippines'),
(2, 'Fresh Dairy Distributors', '+63 920 234 5678', 'Quezon City, PH'),
(3, 'Golden Cocoa Trading', '+63 910 345 6789', 'Makati, PH'),
(4, 'Chocolate Haven Inc.', '+63 930 456 7890', 'Pasay, PH'),
(5, 'Tia Maria Imports', '+63 940 567 8901', 'Cebu City, PH'),
(6, 'Espresso Essence Supply', '+63 950 678 9012', 'Davao, PH'),
(7, 'Mascarpone Masters Inc.', '+63 960 789 0123', 'Taguig, PH'),
(8, 'Flour Power Milling Co.', '+63 970 890 1234', 'Pampanga, PH'),
(9, 'Premium Liquor Suppliers', '+63 980 901 2345', 'Bacolod, PH'),
(10, 'The Egg Farm Distributors', '+63 990 012 3456', 'Cavite, PH');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `transaction_type` enum('Sale','Refund') NOT NULL,
  `transaction_date` datetime NOT NULL,
  `ID` int(11) DEFAULT NULL,
  `menu_item_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `remarks` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `ID` int(11) NOT NULL,
  `FirstName` varchar(100) NOT NULL,
  `LastName` varchar(100) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `TypeOfUser` enum('Admin','Customer','Rider') NOT NULL,
  `Address` text DEFAULT NULL,
  `ContactNumber` varchar(20) DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expiry` datetime DEFAULT NULL,
  `AccountStatus` enum('Active','Suspended','Deleted','Deactivated') DEFAULT 'Active',
  `PasswordStatus` enum('Default','Changed') DEFAULT NULL,
  `DeletionDate` datetime DEFAULT NULL,
  `EmailNotifications` tinyint(1) NOT NULL DEFAULT 0,
  `DataSharing` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`ID`, `FirstName`, `LastName`, `Email`, `Password`, `TypeOfUser`, `Address`, `ContactNumber`, `otp`, `otp_expiry`, `AccountStatus`, `PasswordStatus`, `DeletionDate`, `EmailNotifications`, `DataSharing`) VALUES
(1, 'Amante Jr', 'Amurao', 'ajhae16@outlook.com', '$2y$10$A.B1F4pRk3MoJNI5in91S.U.JMjafSIC/.q8TTMfuk77fqh.rd7LW', 'Admin', 'Blok 6A Lot 8 Ocampo St Terrassa 2 Buhay na Tubig Imus Cavite', '09462366958', NULL, NULL, 'Active', NULL, NULL, 0, 0),
(2, 'Meryl Joy', 'Bazar', 'meryljoy.bazar22@gmail.com', '$2y$10$9YIx5lL/Ny0LmzL9U3V1Gej4X3cSpBgPIPMe3Akba5s5F0gZP6GRe', 'Rider', 'Blk 6A Lot 8 Ocampo St Terrassa 2 Buhay na Tubig Imus Cavite', '09554412231', '436864', '2025-05-03 16:54:14', 'Active', '', NULL, 0, 0),
(3, 'Amante Jr', 'Amurao', 'amante.amurao@cvsu.edu.ph', '$2y$10$Sg8s08TnjCIWtnGUYHlA6u9vMiTk1X9mNpV0W8KVkjXsTwOe3swUq', 'Customer', 'Blk 6A Lot 8 Ocampo St Terrassa 2 Buhay na Tubig Imus Cavite', '09462366958', '244724', '2025-04-24 21:49:52', 'Active', '', NULL, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activitytracking`
--
ALTER TABLE `activitytracking`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `performed_by` (`performed_by`),
  ADD KEY `affected_user_id` (`affected_user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`delivery_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD PRIMARY KEY (`assignment_id`),
  ADD KEY `rider_id` (`rider_id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD PRIMARY KEY (`ingredient_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD PRIMARY KEY (`menu_item_id`),
  ADD KEY `menu_category_id` (`menu_category_id`),
  ADD KEY `ingredients_used` (`ingredients_used`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`menu_category_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_messages_users` (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `ID` (`ID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `riders`
--
ALTER TABLE `riders`
  ADD PRIMARY KEY (`rider_id`);

--
-- Indexes for table `rider_activity_logs`
--
ALTER TABLE `rider_activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `rider_id` (`rider_id`);

--
-- Indexes for table `stocktransactions`
--
ALTER TABLE `stocktransactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `ID` (`ID`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `Email` (`Email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activitytracking`
--
ALTER TABLE `activitytracking`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditlogs`
--
ALTER TABLE `auditlogs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `delivery_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  MODIFY `assignment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ingredients`
--
ALTER TABLE `ingredients`
  MODIFY `ingredient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `menuitems`
--
ALTER TABLE `menuitems`
  MODIFY `menu_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `menu_category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `riders`
--
ALTER TABLE `riders`
  MODIFY `rider_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rider_activity_logs`
--
ALTER TABLE `rider_activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stocktransactions`
--
ALTER TABLE `stocktransactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activitytracking`
--
ALTER TABLE `activitytracking`
  ADD CONSTRAINT `activitytracking_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`ID`);

--
-- Constraints for table `auditlogs`
--
ALTER TABLE `auditlogs`
  ADD CONSTRAINT `auditlogs_ibfk_1` FOREIGN KEY (`performed_by`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `auditlogs_ibfk_2` FOREIGN KEY (`affected_user_id`) REFERENCES `user` (`ID`);

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `deliveries_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD CONSTRAINT `delivery_assignments_ibfk_1` FOREIGN KEY (`rider_id`) REFERENCES `riders` (`rider_id`),
  ADD CONSTRAINT `delivery_assignments_ibfk_2` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `delivery_assignments_ibfk_3` FOREIGN KEY (`rider_id`) REFERENCES `riders` (`rider_id`),
  ADD CONSTRAINT `delivery_assignments_ibfk_4` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`);

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `ingredients`
--
ALTER TABLE `ingredients`
  ADD CONSTRAINT `ingredients_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  ADD CONSTRAINT `ingredients_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`),
  ADD CONSTRAINT `ingredients_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`supplier_id`);

--
-- Constraints for table `menuitems`
--
ALTER TABLE `menuitems`
  ADD CONSTRAINT `menuitems_ibfk_1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_categories` (`menu_category_id`),
  ADD CONSTRAINT `menuitems_ibfk_2` FOREIGN KEY (`ingredients_used`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `menuitems_ibfk_3` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_categories` (`menu_category_id`),
  ADD CONSTRAINT `menuitems_ibfk_4` FOREIGN KEY (`ingredients_used`) REFERENCES `ingredients` (`ingredient_id`),
  ADD CONSTRAINT `menuitems_ibfk_5` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_categories` (`menu_category_id`),
  ADD CONSTRAINT `menuitems_ibfk_6` FOREIGN KEY (`ingredients_used`) REFERENCES `ingredients` (`ingredient_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_messages_users` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `orders_ibfk_3` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`),
  ADD CONSTRAINT `products_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `rider_activity_logs`
--
ALTER TABLE `rider_activity_logs`
  ADD CONSTRAINT `rider_activity_logs_ibfk_1` FOREIGN KEY (`rider_id`) REFERENCES `riders` (`rider_id`),
  ADD CONSTRAINT `rider_activity_logs_ibfk_2` FOREIGN KEY (`rider_id`) REFERENCES `riders` (`rider_id`);

--
-- Constraints for table `stocktransactions`
--
ALTER TABLE `stocktransactions`
  ADD CONSTRAINT `stocktransactions_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`),
  ADD CONSTRAINT `stocktransactions_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menuitems` (`menu_item_id`),
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`ID`) REFERENCES `user` (`ID`),
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`menu_item_id`) REFERENCES `menuitems` (`menu_item_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
