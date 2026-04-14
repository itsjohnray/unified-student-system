-- =============================================
-- UNIFIED STUDENT SYSTEM - Database Schema
-- Import this into FreedB (freedb.tech)
-- =============================================

CREATE TABLE IF NOT EXISTS `courses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `course_name` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `courses` (`course_name`) VALUES
('BSIT'),
('BSBA'),
('BSED'),
('BEED'),
('BSHM');

CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(20) NOT NULL DEFAULT 'student',
  `course_id` INT DEFAULT NULL,
  `year_level` VARCHAR(20) DEFAULT NULL,
  `section` VARCHAR(20) DEFAULT NULL,
  `last_active` DATETIME DEFAULT NULL,
  FOREIGN KEY (`course_id`) REFERENCES `courses`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Default admin account (username: admin, password: admin)
INSERT INTO `users` (`username`, `password`, `role`) VALUES
('admin', 'admin', 'admin');

CREATE TABLE IF NOT EXISTS `subjects` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `subject_name` VARCHAR(100) NOT NULL,
  `teacher` VARCHAR(100) DEFAULT NULL,
  `student_username` VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `grades` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `subject` VARCHAR(100) NOT NULL,
  `grade` DECIMAL(5,2) NOT NULL,
  `semester` VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `announcements` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `title` VARCHAR(200) NOT NULL,
  `message` TEXT,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `messages` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender` VARCHAR(100) NOT NULL,
  `receiver` VARCHAR(100) NOT NULL,
  `message` TEXT,
  `status` VARCHAR(20) DEFAULT 'unread'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(100) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `status` VARCHAR(20) DEFAULT 'pending',
  `course` VARCHAR(100) DEFAULT NULL,
  `year_level` VARCHAR(20) DEFAULT NULL,
  `section` VARCHAR(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Sessions table for serverless session handling
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` VARCHAR(128) NOT NULL PRIMARY KEY,
  `data` TEXT NOT NULL,
  `timestamp` INT UNSIGNED NOT NULL,
  INDEX `idx_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
