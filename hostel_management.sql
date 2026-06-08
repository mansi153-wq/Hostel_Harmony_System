-- Hostel Harmony System — Database Schema
-- Import this file into your hosting provider's MySQL/MariaDB via phpMyAdmin or CLI:
--   mysql -u your_user -p hostel_management < hostel_management.sql

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Database: `hostel_management`
-- --------------------------------------------------------

-- Table: admins
CREATE TABLE IF NOT EXISTS `admins` (
  `id`       int(11)      NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Default admin: username=admin  password=Admin@1234  (CHANGE THIS after first login)
-- Password hash for "Admin@1234"
INSERT INTO `admins` (`username`, `password`) VALUES
('admin', '$2y$10$HPhq8jJiLmC/PG1MJqr1lOVSz5.WcYV8/kNh61NNradAPhepKyLc6');

-- --------------------------------------------------------

-- Table: rooms
CREATE TABLE IF NOT EXISTS `rooms` (
  `id`          int(11)                          NOT NULL AUTO_INCREMENT,
  `room_number` varchar(10)                      NOT NULL,
  `type`        enum('single','double','dormitory') NOT NULL,
  `fee`         decimal(10,2)                    NOT NULL,
  `status`      enum('available','booked')       DEFAULT 'available',
  PRIMARY KEY (`id`),
  UNIQUE KEY `room_number` (`room_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `rooms` (`room_number`, `type`, `fee`, `status`) VALUES
('101', 'single', 5000.00, 'available'),
('102', 'single', 5000.00, 'available'),
('201', 'double', 8000.00, 'available'),
('202', 'double', 8000.00, 'available');

-- --------------------------------------------------------

-- Table: students
CREATE TABLE IF NOT EXISTS `students` (
  `id`             int(11)      NOT NULL AUTO_INCREMENT,
  `name`           varchar(100) NOT NULL,
  `email`          varchar(100) NOT NULL,
  `password`       varchar(255) NOT NULL,
  `room_number`    varchar(10)  DEFAULT NULL,
  `payment_status` enum('paid','unpaid') DEFAULT 'unpaid',
  `created_at`     timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table: payments
CREATE TABLE IF NOT EXISTS `payments` (
  `id`             int(11)      NOT NULL AUTO_INCREMENT,
  `student_id`     int(11)      NOT NULL,
  `amount`         decimal(10,2) NOT NULL,
  `payment_method` enum('cash','credit_card','bank_transfer') NOT NULL,
  `status`         enum('completed','pending','failed') DEFAULT 'pending',
  `created_at`     timestamp    NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

-- Table: complaints
CREATE TABLE IF NOT EXISTS `complaints` (
  `id`         int(11) NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `complaint`  text    NOT NULL,
  `status`     enum('pending','resolved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `student_id` (`student_id`),
  CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

COMMIT;
