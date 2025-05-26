# CodeIgniter Event Logger Library

A lightweight, extensible logging library for CodeIgniter (3.x) to capture and track **insert**, **update**, and **delete** operations on database tables with user and request metadata.

## 📦 Features

- Logs **insert**, **update**, and **delete** events.
- Tracks changed fields with before/after values (for update).
- Captures user IP, browser info, and session ID.
- Simple plug-and-play integration.
- Useful for **auditing**, **debugging**, and **change tracking**.

---

## 📁 Installation

1. **Copy the library file** to your application:
   ```bash
   application/libraries/Event_logger.php


Create the database table used for logs:

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

⚙️ Usage
1. Load the library in your controller/model

$this->load->library('event_logger');

2. Log an insert operation
```bash
$data = [
  'name' => 'hello Dear',
  'email' => 'hello@example.com'
];
$this->event_logger->log_event('insert', $data, 'users');

4. Log an update operation
```bash
$updated_data = [
  'email' => 'newemail@example.com'
];
$this->event_logger->log_event('update', $updated_data, 'users', 'id', 5);

5. Log a delete operation
```bash
$this->event_logger->log_event('delete', [], 'users', 'id', 5);


🧠 How It Works
insert: Stores all fields in event_values.

update: Compares new values with existing, stores only changed fields.

delete: Captures full row before deletion.

All operations include:

updated_by: User ID from session (admin_id by default)

ip_address: Client IP

user_agent: Browser/device

added_on: Timestamp

🔧 Customization
Session key for admin_id can be modified in the _get_user_id() function.

Extend this class to add support for soft deletes or table-specific filters.

📌 Requirements
CodeIgniter 3.x (not tested on CI4)

PHP 5.6+



