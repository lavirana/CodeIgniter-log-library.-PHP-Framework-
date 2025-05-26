CREATE TABLE `event_logs` (
  `id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `event_name` VARCHAR(50),
  `event_table` VARCHAR(100),
  `table_unique_id` VARCHAR(50) DEFAULT NULL,
  `unique_id` VARCHAR(50) DEFAULT NULL,
  `event_values` TEXT,
  `updated_by` INT DEFAULT NULL,
  `ip_address` VARCHAR(45),
  `user_agent` VARCHAR(255),
  `added_on` DATETIME
);
