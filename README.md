# 🚀 CodeIgniter Event Logger Library

A lightweight, extensible logging library for CodeIgniter (3.x) to track **insert**, **update**, and **delete** operations on database tables with rich metadata including user and request context.

## 📦 Features

- Logs **Insert**, **Update**, and **Delete** events  
- Tracks changed fields (for updates) with **before/after** values  
- Captures:
  - User IP  
  - Browser info (User Agent)  
  - User ID from session  
- Easy plug-and-play integration  
- Ideal for **auditing**, **debugging**, and **change tracking**

## 📁 Installation

### 1. Add Library

Copy the library file into your CodeIgniter project:

```
application/libraries/Event_logger.php
```

### 2. Create `event_logs` Table

Run this SQL to create the table used for logging:

```sql
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
```

## ⚙️ Usage

### 1. Load the Logger

In your controller or model:

```php
$this->load->library('event_logger');
```

### 2. Log an Insert Event

```php
$data = [
  'name' => 'Hello Dear',
  'email' => 'hello@example.com'
];
$this->event_logger->log_event('insert', $data, 'users');
```

### 3. Log an Update Event

```php
$updated_data = [
  'email' => 'newemail@example.com'
];
$this->event_logger->log_event('update', $updated_data, 'users', 'id', 5);
```

### 4. Log a Delete Event

```php
$this->event_logger->log_event('delete', [], 'users', 'id', 5);
```

## 🧠 How It Works

- **Insert**: Saves all fields as `event_values`  
- **Update**: Compares new vs existing values, logs only changed fields  
- **Delete**: Logs the full row before deletion

Each event stores:

- `updated_by`: from session (default: `admin_id`)  
- `ip_address`: user's IP  
- `user_agent`: browser/device info  
- `added_on`: timestamp  

## 🔧 Customization

- Modify session key (`admin_id`) from:

```php
$this->CI->session->userdata('admin_id');
```

- Extend the class to support:
  - Soft deletes  
  - Field exclusion/inclusion rules  
  - Logging only specific tables  

## 📌 Requirements

- ✅ CodeIgniter 3.x  
- ✅ PHP 5.6+

> ⚠️ Not tested on CodeIgniter 4.x — for CI4 support, refactor to CI4 services.

## 📃 License

MIT License

## 🙌 Contributions Welcome

Feel free to fork and submit pull requests for improvements or CI4 compatibility!
