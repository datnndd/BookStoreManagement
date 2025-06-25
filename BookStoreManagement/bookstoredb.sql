-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 04:58 AM
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
-- Database: `bookstoredb`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `author_id` int(11) NOT NULL,
  `author_name` varchar(255) NOT NULL,
  `birthday` datetime DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `url_image` varchar(255) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`author_id`, `author_name`, `birthday`, `country`, `bio`, `url_image`, `create_at`) VALUES
(1, 'Nguyễn Nhật Ánh', '1955-05-07 00:00:00', 'Việt Nam', 'Tác giả nổi tiếng với các tác phẩm dành cho thiếu nhi như \"Kính vạn hoa\"', 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Nguy%E1%BB%85n_Nh%E1%BA%ADt_%C3%81nh.jpg/500px-Nguy%E1%BB%85n_Nh%E1%BA%ADt_%C3%81nh.jpg', '2025-05-30 17:08:45'),
(2, 'J.K. Rowling', '1965-07-31 00:00:00', 'United Kingdom', 'Tác giả của loạt truyện Harry Potter nổi tiếng toàn thế giới', 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5d/J._K._Rowling_2010.jpg/500px-J._K._Rowling_2010.jpg', '2025-05-30 17:08:45'),
(3, 'Haruki Murakami', '1949-01-12 00:00:00', 'Japan', 'Tác giả nổi tiếng với phong cách huyền ảo và triết lý sâu sắc', 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Murakami_Haruki_%282009%29.jpg/120px-Murakami_Haruki_%282009%29.jpg', '2025-05-30 17:08:45'),
(4, 'Paulo Coelho', '1947-08-24 00:00:00', 'Brazil', 'Tác giả của Nhà giả kim nổi tiếng', 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c0/Coelhopaulo26012007-1.jpg/500px-Coelhopaulo26012007-1.jpg', '2025-06-01 03:00:00'),
(5, 'George Orwell', '1903-06-25 00:00:00', 'United Kingdom', 'Tác giả của 1984 và Animal Farm', 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7e/George_Orwell_press_photo.jpg/500px-George_Orwell_press_photo.jpg', '2025-06-02 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) DEFAULT 0,
  `description` text DEFAULT NULL,
  `url_image` varchar(255) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `publisher_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `price`, `quantity`, `description`, `url_image`, `create_at`, `publisher_id`) VALUES
(1, 'Tôi thấy hoa vàng trên cỏ xanh', 85000.00, 120, 'Tác phẩm nổi tiếng của Nguyễn Nhật Ánh', 'https://salt.tikicdn.com/cache/w1200/media/catalog/product/t/o/toi_thay_hoa_vang.jpg', '2025-05-30 17:08:45', 1),
(2, 'Harry Potter và Hòn đá phù thủy', 150000.00, 200, 'Tập đầu tiên trong loạt truyện Harry Potter', 'https://salt.tikicdn.com/cache/w1200/ts/product/2b/c6/02/1fcbede5c7e998b88edc0e4f39510743.jpg', '2025-05-30 17:08:45', 2),
(3, 'Rừng Na Uy', 135000.00, 100, 'Một trong những tiểu thuyết nổi bật của Haruki Murakami', 'https://th.bing.com/th/id/OIP.NmFiEASLUO-pKOHNxu0rYwAAAA?rs=1&pid=ImgDetMain', '2025-05-30 17:08:45', 3),
(5, 'Sach hay', 850000.00, 10, '123456', 'https://th.bing.com/th/id/OIP.QVHuq09v2mLB_3xUgEpOlwHaE8?rs=1&pid=ImgDetMain', '2025-06-02 20:22:15', 1),
(6, 'Nhà giả kim', 120000.00, 80, 'Tiểu thuyết nổi tiếng của Paulo Coelho', 'https://th.bing.com/th/id/OIP._yzetDs3gher9vBV-Ax_iAHaL0?rs=1&pid=ImgDetMain', '2025-06-01 09:00:00', 4),
(7, '1984', 110000.00, 60, 'Tác phẩm kinh điển của George Orwell', 'https://th.bing.com/th/id/OIP.4Smz5vsinVH_b7fctyQ6DAHaKX?rs=1&pid=ImgDetMain', '2025-06-02 10:00:00', 5),
(8, 'Đắc nhân tâm', 95000.00, 150, 'Sách self-help kinh điển', 'https://th.bing.com/th/id/OIP.cUYVV92koOJ_3HFiDfTDggHaK1?rs=1&pid=ImgDetMain', '2025-06-03 11:00:00', 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_author`
--

CREATE TABLE `book_author` (
  `book_id` int(11) NOT NULL,
  `author_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_author`
--

INSERT INTO `book_author` (`book_id`, `author_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(6, 4),
(7, 5),
(8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `book_category`
--

CREATE TABLE `book_category` (
  `book_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_category`
--

INSERT INTO `book_category` (`book_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(2, 2),
(3, 2),
(3, 3),
(6, 2),
(6, 3),
(7, 2),
(7, 6),
(8, 3),
(8, 5);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `description`, `create_at`) VALUES
(1, 'Thiếu nhi', 'Sách dành cho lứa tuổi thiếu nhi, truyện tranh, truyện ngắn nhẹ nhàng.', '2025-05-30 17:08:45'),
(2, 'Tiểu thuyết', 'Các tác phẩm văn học hư cấu, truyện dài.', '2025-05-30 17:08:45'),
(3, 'Tâm lý - Xã hội', 'Sách phân tích tâm lý, xã hội, phát triển bản thân.', '2025-05-30 17:08:45'),
(4, 'Khoa học - Kỹ thuật', 'Sách về các lĩnh vực công nghệ, khoa học, kỹ thuật.', '2025-05-30 17:08:45'),
(5, 'Kinh tế', 'Sách về kinh tế, tài chính, đầu tư', '2025-06-01 07:00:00'),
(6, 'Lịch sử', 'Sách về lịch sử, văn hóa các quốc gia', '2025-06-02 08:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `order_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('đang xử lý','đã giao','đã huỷ') NOT NULL DEFAULT 'đang xử lý',
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `order_date`, `status`, `user_id`) VALUES
(1, '2025-06-05 14:20:00', 'đã giao', 2),
(2, '2025-06-06 15:30:00', 'đang xử lý', 3),
(3, '2025-06-07 16:45:00', 'đã huỷ', 4);

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `book_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 85000.00),
(2, 1, 2, 1, 150000.00),
(3, 2, 3, 1, 135000.00),
(4, 3, 1, 1, 85000.00);

-- --------------------------------------------------------

--
-- Table structure for table `publishers`
--

CREATE TABLE `publishers` (
  `publisher_id` int(11) NOT NULL,
  `publisher_name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `create_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `publishers`
--

INSERT INTO `publishers` (`publisher_id`, `publisher_name`, `address`, `email`, `phone_number`, `create_at`) VALUES
(1, 'NXB Trẻ', '161B Lý Chính Thắng, Quận 3, TP.HCM', 'contact@nxbtree.vn', '02839312345', '2025-05-30 17:08:45'),
(2, 'NXB Kim Đồng', '55 Quang Trung, Hà Nội', 'info@kimdong.vn', '02438454545', '2025-05-30 17:08:45'),
(3, 'NXB Giáo Dục', '81 Trần Hưng Đạo, Hà Nội', 'support@giaoduc.vn', '02439425231', '2025-05-30 17:08:45'),
(4, 'NXB Văn Học', '18 Nguyễn Trường Tộ, Hà Nội', 'info@nxbvanhoc.vn', '02438234567', '2025-06-01 05:00:00'),
(5, 'NXB Hội Nhà Văn', '65 Nguyễn Du, Hà Nội', 'contact@hoinhavan.vn', '12345678', '2025-06-02 06:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `create_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `full_name` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `email`, `role`, `create_at`, `full_name`, `phone_number`, `address`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bookstore.com', 'admin', '2025-06-01 01:00:00', NULL, NULL, NULL),
(2, 'banv123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'banv@email.com', 'customer', '2025-06-02 02:15:00', 'Nguyễn Văn Ba', '012345678', '123 Đường Lê Lợi, Quận 1, TP.HCM'),
(3, 'antt123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'antt@email.com', 'customer', '2025-06-03 03:30:00', 'Trần Thị An', '0987654321', '456 Đường Nguyễn Huệ, Quận 1, TP.HCM'),
(4, 'datlv123', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'datlv@email.com', 'customer', '2025-06-04 04:45:00', 'Lê Văn Đạt', '0905123456', '789 Đường Cách Mạng Tháng 8, Quận 3, TP.HCM');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`author_id`);

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indexes for table `book_author`
--
ALTER TABLE `book_author`
  ADD PRIMARY KEY (`book_id`,`author_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `book_category`
--
ALTER TABLE `book_category`
  ADD PRIMARY KEY (`book_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `fk_orders_user` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `publishers`
--
ALTER TABLE `publishers`
  ADD PRIMARY KEY (`publisher_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `publishers`
--
ALTER TABLE `publishers`
  MODIFY `publisher_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`publisher_id`) REFERENCES `publishers` (`publisher_id`) ON DELETE CASCADE;

--
-- Constraints for table `book_author`
--
ALTER TABLE `book_author`
  ADD CONSTRAINT `book_author_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_author_ibfk_2` FOREIGN KEY (`author_id`) REFERENCES `author` (`author_id`) ON DELETE CASCADE;

--
-- Constraints for table `book_category`
--
ALTER TABLE `book_category`
  ADD CONSTRAINT `book_category_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `book_category_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
