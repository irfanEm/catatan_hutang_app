
CREATE DATABASE IF NOT EXISTS hutang_db;
USE hutang_db;

-- Table: agents
CREATE TABLE IF NOT EXISTS agents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_agen VARCHAR(50) NOT NULL,
    nama_agen VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

-- Table: payment_methods
CREATE TABLE IF NOT EXISTS payment_methods (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode_payment_method VARCHAR(50) NOT NULL,
    nama_payment_method VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL
);

-- Table: hutang
CREATE TABLE IF NOT EXISTS hutang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    debt_id VARCHAR(50) NOT NULL,
    agen_id INT NOT NULL,
    payment_method_id INT NOT NULL,
    tanggal_hutang DATE NOT NULL,
    tanggal_jatuh_tempo DATE NOT NULL,
    sisa_hutang DECIMAL(15,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL,
    deleted_at TIMESTAMP NULL,
    FOREIGN KEY (agen_id) REFERENCES agents(id) ON DELETE CASCADE,
    FOREIGN KEY (payment_method_id) REFERENCES payment_methods(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Superadmin', 'user_one@superadmin.com', '$2y$10$a6vS7w.jVnKOZPPnxY/gE.U1ADsehitFJ94zKpks5cfxHNoeFJHSS', '2025-04-29 22:54:48', '2025-04-29 22:54:48', NULL);