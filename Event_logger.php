<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_logger {
    protected $CI;
    private $_log_table_name = 'event_logs';

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /**
     * Log an event (insert, update, delete)
     */
    public function log_event($event, $data, $table, $unique_identifier = "", $unique_identifier_value = "") {
        if (!$this->CI->db->table_exists($table)) {
            log_message('error', "Event Logger: Table '$table' does not exist.");
            return false;
        }

        switch (strtolower($event)) {
            case 'insert':
                return $this->_log_insert($data, $table);
            
            case 'update':
                return $this->_log_update($data, $table, $unique_identifier, $unique_identifier_value);
            
            case 'delete':
                return $this->_log_delete($table, $unique_identifier, $unique_identifier_value);
            
            default:
                log_message('error', "Event Logger: Unknown event '$event'");
                return false;
        }
    }

    private function _log_insert($data, $table) {
        $log_entry = [
            'event_name' => 'insert',
            'event_table' => $table,
            'event_values' => json_encode($data),
            'updated_by' => $this->_get_user_id(),
            'ip_address' => $this->CI->input->ip_address(),
            'user_agent' => $this->CI->input->user_agent(),
            'added_on' => date('Y-m-d H:i:s'),
        ];
        return $this->CI->db->insert($this->_log_table_name, $log_entry);
    }

    private function _log_update($data, $table, $key, $value) {
        if (empty($key) || empty($value)) {
            log_message('error', "Event Logger: Update requires unique identifier and value.");
            return false;
        }

        $changes = [];
        foreach ($data as $column => $new_val) {
            $this->CI->db->select($column);
            $this->CI->db->where($key, $value);
            $row = $this->CI->db->get($table)->row();

            if ($row && strtolower(trim($row->$column)) !== strtolower(trim($new_val))) {
                $changes[$column] = ['old_value' => $row->$column, 'new_value' => $new_val];
            }
        }

        if (!empty($changes)) {
            $log_entry = [
                'event_name' => 'update',
                'event_table' => $table,
                'table_unique_id' => $key,
                'unique_id' => $value,
                'event_values' => json_encode($changes),
                'updated_by' => $this->_get_user_id(),
                'ip_address' => $this->CI->input->ip_address(),
                'user_agent' => $this->CI->input->user_agent(),
                'added_on' => date('Y-m-d H:i:s'),
            ];
            return $this->CI->db->insert($this->_log_table_name, $log_entry);
        }

        return false;
    }

    private function _log_delete($table, $key, $value) {
        if (empty($key) || empty($value)) {
            log_message('error', "Event Logger: Delete requires unique identifier and value.");
            return false;
        }

        $this->CI->db->where($key, $value);
        $row = $this->CI->db->get($table)->row();

        if ($row) {
            $log_entry = [
                'event_name' => 'delete',
                'event_table' => $table,
                'event_values' => json_encode($row),
                'updated_by' => $this->_get_user_id(),
                'ip_address' => $this->CI->input->ip_address(),
                'user_agent' => $this->CI->input->user_agent(),
                'added_on' => date('Y-m-d H:i:s'),
            ];
            return $this->CI->db->insert($this->_log_table_name, $log_entry);
        }

        return false;
    }

    private function _get_user_id() {
        return $this->CI->session->userdata('admin_id') ?? 0;
    }
}
