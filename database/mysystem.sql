-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 19, 2024 at 09:21 PM
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
-- Database: `mysystem`
--

-- --------------------------------------------------------

--
-- Table structure for table `cash`
--

CREATE TABLE `cash` (
  `id` int(11) NOT NULL,
  `cash` int(10) NOT NULL,
  `cash_pull_out` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `name` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cash`
--

INSERT INTO `cash` (`id`, `cash`, `cash_pull_out`, `date`, `name`) VALUES
(5, 1500, 0, '2024-05-19 15:51:33', ''),
(6, 3500, 0, '2024-05-19 16:25:00', ''),
(7, 3500, 0, '2024-05-19 16:38:57', ''),
(8, -100, 100, '2024-05-19 17:25:28', 'user'),
(9, -8400, 8400, '2024-05-19 18:18:58', 'user'),
(11, 1500, 0, '2024-05-19 18:23:47', ''),
(12, 1500, 0, '2024-05-19 18:23:56', ''),
(13, 200, 0, '2024-05-19 18:26:11', ''),
(14, 200, 0, '2024-05-19 18:26:18', ''),
(15, -3400, 3400, '2024-05-19 18:37:24', 'user'),
(16, 1500, 0, '2024-05-19 18:37:38', ''),
(17, -1500, 1500, '2024-05-19 18:37:42', 'user'),
(18, 1500, 0, '2024-05-19 18:38:20', ''),
(19, -1500, 1500, '2024-05-19 18:38:22', 'user'),
(20, 0, 0, '2024-05-19 18:45:18', 'user'),
(21, 200, 0, '2024-05-19 18:59:17', ''),
(22, 200, 0, '2024-05-19 19:00:31', ''),
(23, 1500, 0, '2024-05-19 19:04:29', ''),
(24, 200, 0, '2024-05-19 19:07:54', ''),
(25, 3500, 0, '2024-05-19 19:07:54', ''),
(26, -5600, 5600, '2024-05-19 19:08:07', 'user'),
(27, 1500, 0, '2024-05-19 19:10:33', ''),
(28, -1500, 1500, '2024-05-19 19:13:57', 'cashier'),
(29, 3500, 0, '2024-05-19 19:14:18', ''),
(30, 200, 0, '2024-05-19 19:14:18', ''),
(31, 1500, 0, '2024-05-19 19:14:18', '');

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `code` varchar(6) NOT NULL,
  `image` varchar(50) NOT NULL,
  `name` varchar(50) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` int(10) NOT NULL,
  `current_stock` int(10) NOT NULL,
  `physical_count` int(10) NOT NULL,
  `delivery` int(10) NOT NULL,
  `transfer` int(10) NOT NULL,
  `wasteges` int(10) NOT NULL,
  `pull_out` int(10) NOT NULL,
  `returns` int(10) NOT NULL,
  `variance` int(10) NOT NULL,
  `description` varchar(50) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`code`, `image`, `name`, `category`, `price`, `current_stock`, `physical_count`, `delivery`, `transfer`, `wasteges`, `pull_out`, `returns`, `variance`, `description`, `date_added`) VALUES
('32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 12, 25, 25, 0, 0, 1, 0, -1, 'Shoes', '2024-05-19 10:49:59'),
('af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 41, 50, 25, 25, 0, 1, 0, -1, 'Etc', '2024-05-19 10:49:15'),
('b3bb92', 'product2.jpg', 'DesignersBag', 'Bags', 3500, 7, 12, 12, 0, 0, 1, 0, -1, 'Bag', '2024-05-19 10:51:19');

-- --------------------------------------------------------

--
-- Table structure for table `request`
--

CREATE TABLE `request` (
  `code` varchar(10) NOT NULL,
  `supplier` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `quantity` int(10) NOT NULL,
  `price` int(10) NOT NULL,
  `status` varchar(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `delivery_status` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `request`
--

INSERT INTO `request` (`code`, `supplier`, `name`, `quantity`, `price`, `status`, `date`, `delivery_status`) VALUES
('1fb1fc', 'Demo Supplier', 'Demo Product', 50, 25, 'request', '2024-05-19 12:17:24', '');

-- --------------------------------------------------------

--
-- Table structure for table `sale`
--

CREATE TABLE `sale` (
  `purchase_code` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `code` varchar(10) NOT NULL,
  `image` varchar(50) NOT NULL,
  `name` varchar(20) NOT NULL,
  `category` varchar(20) NOT NULL,
  `price` int(10) NOT NULL,
  `sold` int(10) NOT NULL,
  `method` varchar(20) NOT NULL,
  `cash_received` int(10) NOT NULL,
  `cashier_name` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sale`
--

INSERT INTO `sale` (`purchase_code`, `date`, `code`, `image`, `name`, `category`, `price`, `sold`, `method`, `cash_received`, `cashier_name`) VALUES
(27, '2024-05-19 15:51:33', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(28, '2024-05-19 16:25:00', 'b3bb92', 'product2.jpg', 'DesignersBag', 'Bags', 3500, 1, 'cash', 3500, 'User'),
(29, '2024-05-19 16:38:57', 'b3bb92', 'product2.jpg', 'DesignersBag', 'Bags', 3500, 1, 'cash', 3500, 'User'),
(30, '2024-05-19 18:23:47', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(31, '2024-05-19 18:23:56', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(32, '2024-05-19 18:26:11', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'User'),
(33, '2024-05-19 18:26:18', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'User'),
(34, '2024-05-19 18:37:38', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(35, '2024-05-19 18:38:20', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(36, '2024-05-19 18:59:17', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'User'),
(37, '2024-05-19 19:00:31', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'User'),
(38, '2024-05-19 19:04:29', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(39, '2024-05-19 19:07:54', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'User'),
(40, '2024-05-19 19:07:54', 'b3bb92', 'product2.jpg', 'DesignersBag', 'Bags', 3500, 1, 'cash', 3500, 'User'),
(41, '2024-05-19 19:10:33', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'User'),
(42, '2024-05-19 19:14:18', 'b3bb92', 'product2.jpg', 'DesignersBag', 'Bags', 3500, 1, 'cash', 3500, 'Cashier'),
(43, '2024-05-19 19:14:18', 'af9158', 'product3.jpg', 'Headphones', 'Electronics', 200, 1, 'cash', 200, 'Cashier'),
(44, '2024-05-19 19:14:18', '32ab9d', 'product.jpg', 'NikeShoes', 'Shoes', 1500, 1, 'cash', 1500, 'Cashier');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(15) NOT NULL,
  `email` varchar(30) NOT NULL,
  `phone_number` bigint(11) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `phone_number`, `date_created`, `is_admin`) VALUES
(5, 'admin', '12345', 'admin@gmail.com', 12345678910, '2024-05-18 10:38:15', 'admin'),
(6, 'user', '12345', 'user@gmail.com', 12345678910, '2024-05-18 10:38:15', 'user'),
(8, 'purchase', '12345', 'purchase@gmail.com', 12345678910, '2024-05-18 13:47:52', 'purchase'),
(12, 'cashier', '12345', 'cashier@gmail.com', 1234567890, '2024-05-19 19:13:28', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cash`
--
ALTER TABLE `cash`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `request`
--
ALTER TABLE `request`
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `sale`
--
ALTER TABLE `sale`
  ADD PRIMARY KEY (`purchase_code`),
  ADD UNIQUE KEY `purchase_code` (`purchase_code`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD UNIQUE KEY `id` (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cash`
--
ALTER TABLE `cash`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `sale`
--
ALTER TABLE `sale`
  MODIFY `purchase_code` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
