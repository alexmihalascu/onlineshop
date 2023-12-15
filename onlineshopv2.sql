-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Host: sql203.infinityfree.com
-- Generation Time: Dec 15, 2023 at 06:00 AM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 7.2.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `epiz_29785977_shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_status` varchar(255) NOT NULL DEFAULT 'In procesare',
  `phone` varchar(15) DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `number` varchar(10) DEFAULT NULL,
  `block` varchar(10) DEFAULT NULL,
  `apartment` varchar(10) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `order_date`, `order_status`, `phone`, `street`, `number`, `block`, `apartment`, `city`) VALUES
(1, 3, '2023-12-15 10:23:26', 'In procesare', '0782228288', 'Tesla', '3', '2', '24', 'Musk');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `name`, `description`, `price`, `stock`) VALUES
(2, 'Laptop Dell XPS 13', ' Laptop ultra-subÈ›ire cu ecran OLED de 13 inch È™i procesor Intel Core i7.', '6499.00', 10),
(3, 'Smartphone iPhone 13 Pro', 'Telefon mobil de ultimÄƒ generaÈ›ie cu camerÄƒ triplÄƒ È™i ecran Super Retina XDR.', '5799.00', 15),
(4, 'Televizor Samsung QLED 4K 55 inch', ' Televizor cu rezoluÈ›ie Ultra HD 4K È™i tehnologie QLED pentru culori vibrante.', '3999.00', 5),
(5, 'MaÈ™inÄƒ de spÄƒlat rufe Bosch Serie 6', 'MaÈ™inÄƒ de spÄƒlat cu Ã®ncÄƒrcare frontalÄƒ È™i capacitate de 8 kg.', '1999.00', 12),
(6, 'CafetierÄƒ Nespresso Essenza Mini', 'CafetierÄƒ compactÄƒ cu capsule pentru cafea espresso de Ã®naltÄƒ calitate.', '499.00', 20),
(7, 'BicicletÄƒ de munte Trek X-Caliber 9', 'BicicletÄƒ de munte de Ã®naltÄƒ performanÈ›Äƒ cu cadru din aluminiu È™i transmisie Shimano.', '4299.00', 8),
(8, 'ConsolÄƒ de jocuri PlayStation 5', 'ConsolÄƒ de gaming de ultimÄƒ generaÈ›ie cu suport pentru jocuri 4K È™i tehnologie de urmÄƒrire a miÈ™cÄƒrii.', '2999.00', 6),
(9, 'Aparat foto DSLR Canon EOS 80D', 'Aparat foto digital cu senzor APS-C, ecran tactil È™i capacitate de Ã®nregistrare video Full HD.', '3599.00', 7),
(10, 'MasÄƒ de bucÄƒtÄƒrie extensibilÄƒ', 'MasÄƒ de bucÄƒtÄƒrie cu blat din lemn È™i posibilitate de extindere pentru acomodarea mai multor persoane.', '1199.00', 10),
(11, 'Set de cÄƒÈ™ti wireless Sony WH-1000XM4', 'CÄƒÈ™ti over-ear cu tehnologia de anulare a zgomotului È™i calitate audio excepÈ›ionalÄƒ.', '1299.00', 15),
(12, 'BormaÈ™inÄƒ cu percuÈ›ie Bosch GBH 2-26 DRE', 'BormaÈ™inÄƒ cu percuÈ›ie puternicÄƒ È™i design compact, idealÄƒ pentru lucrÄƒrile de gÄƒurire È™i dÄƒrÃ¢mÄƒri.', '799.00', 9),
(13, 'Set de ustensile de bucÄƒtÄƒrie din oÈ›el inoxidabil', 'Set complet de ustensile de bucÄƒtÄƒrie, inclusiv cuÈ›ite, tÄƒvÄƒlii È™i ustensile pentru gÄƒtit.', '299.00', 20),
(14, 'Tricou Adidas Originals', 'Tricou confortabil È™i elegant de la Adidas, disponibil Ã®n mai multe culori È™i mÄƒrimi.', '129.00', 30),
(15, 'Joc de masÄƒ Catan', 'Joc de societate strategic È™i distractiv potrivit pentru Ã®ntreaga familie.', '149.00', 25),
(16, 'MaÈ™inÄƒ de tuns gazon Honda HRX217HZA', 'MaÈ™inÄƒ de tuns gazon autopropulsatÄƒ cu motor puternic È™i sistem de tÄƒiere precis.', '2499.00', 5),
(17, 'Parfum Chanel Coco Mademoiselle', 'Parfum iconic cu note florale È™i orientale, potrivit pentru orice ocazie.', '549.00', 12),
(18, 'Set de canapele pentru living', 'Set elegant de canapele cu 3 locuri È™i 2 locuri, cu tapiÈ›erie din piele ecologicÄƒ.', '3999.00', 8),
(19, 'Aparat de fitness bicicletÄƒ elipticÄƒ NordicTrack C 9.5', 'BicicletÄƒ elipticÄƒ de Ã®naltÄƒ calitate cu ecran tactil È™i program de antrenament personalizat', '6799.00', 4),
(20, ' Rucsac de cÄƒlÄƒtorie North Face Terra 65', 'Rucsac spaÈ›ios È™i durabil, perfect pentru excursii lungi È™i cÄƒlÄƒtorii.', '899.00', 15),
(21, 'MaÈ™inÄƒ electricÄƒ pentru copii Audi Q7', ' MaÈ™inÄƒ electricÄƒ pentru copii cu telecomandÄƒ parentalÄƒ È™i funcÈ›ii realiste', '1299.00', 7);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `is_admin`) VALUES
(2, 'tesla', '$2y$10$2hoF/38f57UDMQB0B0cLSOvz42d0ZC8aMxY3T8MyuIkwm3.sb44ia', 'tesla@tesla.com', 1),
(3, 'mafia', '$2y$10$/syz.qIJF0ESaeD/Qhc3JeYW/uicbIYWcXzZXT6dc1bioxJo3KYc6', 'mafia@mafia.ro', 0),
(4, 'claudia', '$2y$10$A0hF0IPlhZVQJK2UnwkiNeDh.MujQFQnh9ZrcCwEFj9JeR5KhiM.G', 'claudia@nicolas.ro', 1),
(5, 'nicolas', '$2y$10$opCvIXottRr9oT.hyW0tseWdsmX.VbVdsy6iGQ1QA1uo/zP5DctKe', 'nicolas@claudia.ro', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `user_id` int(11) NOT NULL,
  `birth_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `user_profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
