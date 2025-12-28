<?php
/**
 * Weekly_schedules Model - Handles data operations for weekly schedule records
 * 
 * Demonstrates proper week input handling using ISO 8601 YYYY-W## format.
 * No conversion needed - form submits in same format as database storage.
 */
class Weekly_schedules_model extends Model {
    
    /**
     * Fetch paginated weekly schedule records from database
     * 
     * Retrieves schedules with proper limit and offset for pagination.
     * This is the primary method for listing schedules in manage view.
     * 
     * @param int $limit Maximum number of records to return
     * @param int $offset Number of records to skip (for pagination)
     * @return array Array of schedule record objects
     */
    public function fetch_records(int $limit, int $offset): array {
        return $this->db->get('id', 'weekly_schedules', $limit, $offset);
    }
    
    /**
     * Get form-ready data based on current context
     * 
     * Determines whether to return existing record data (for editing)
     * or POST data/default values (for new forms or validation errors).
     * This is the main method called by controller's create() method.
     * 
     * @param int $update_id Record ID to edit, or 0 for new records
     * @return array Form data ready for view display
     * @example get_form_data(5) returns schedule #5 data for editing
     * @example get_form_data(0) returns POST data or defaults for new schedule
     */
    public function get_form_data(int $update_id = 0): array {
        if ($update_id > 0 && REQUEST_TYPE === 'GET') {
            return $this->get_data_for_edit($update_id);
        } else {
            return $this->get_data_from_post_or_defaults();
        }
    }

    /**
     * Get existing record data for editing
     * 
     * Fetches a single record from database and prepares it for form display.
     * Week is already in YYYY-W## format from database, perfect for form_week().
     * 
     * @param int $update_id The record ID to fetch
     * @return array Record data ready for form
     * @throws No explicit throws, but returns empty array if record not found
     */
    public function get_data_for_edit(int $update_id): array {
        $record = $this->db->get_where($update_id, 'weekly_schedules');
        
        if (empty($record)) {
            return [];
        }
        
        return (array) $record;
    }
    
    /**
     * Get form data from POST or use defaults
     * 
     * Used for new forms or when redisplaying form after validation errors.
     * Returns empty strings as defaults for a clean new form.
     * 
     * @return array Form data with proper types for view
     */
    private function get_data_from_post_or_defaults(): array {
        return [
            'employee_name' => post('employee_name', true) ?? '',
            'work_week' => post('work_week', true) ?? '',
            'tasks' => post('tasks') ?? ''
        ];
    }
    
    /**
     * Prepare POST data for database storage
     * 
     * Converts form submission data to database-ready format.
     * Week comes from form_week() in YYYY-W## format, which is perfect for VARCHAR storage.
     * 
     * @return array Database-ready data with proper types
     */
    public function get_post_data_for_database(): array {
        return [
            'employee_name' => post('employee_name', true),
            'work_week' => post('work_week', true), // Already in YYYY-W## format
            'tasks' => trim(post('tasks'))
        ];
    }
    
    /**
     * Prepare raw database data for display in views
     * 
     * Adds formatted versions of fields while preserving raw data.
     * This is where you add display-friendly versions of data.
     * 
     * @param array $data Raw data from database
     * @return array Enhanced data with formatted fields
     * @example Converts work_week='2025-W52' to work_week_formatted='Week 52, 2025'
     */
    public function prepare_for_display(array $data): array {
        // Format work week for display if present
        if (isset($data['work_week']) && $data['work_week'] !== null && $data['work_week'] !== '') {
            try {
                // Parse YYYY-W## format
                $parts = explode('-W', $data['work_week']);
                if (count($parts) === 2) {
                    $year = $parts[0];
                    $week = ltrim($parts[1], '0'); // Remove leading zero for display
                    
                    // Full format: "Week 52, 2025"
                    $data['work_week_formatted'] = 'Week ' . $week . ', ' . $year;
                    
                    // Short format: "W52 2025"
                    $data['work_week_short'] = 'W' . $parts[1] . ' ' . $year;
                    
                    // Get approximate date range for the week
                    // ISO 8601: Week starts on Monday, Week 1 contains first Thursday
                    try {
                        $date = new DateTime();
                        $date->setISODate((int)$year, (int)$parts[1]);
                        $week_start = $date->format('M j');
                        
                        $date->modify('+6 days');
                        $week_end = $date->format('M j, Y');
                        
                        $data['work_week_range'] = $week_start . ' - ' . $week_end;
                    } catch (Exception $e) {
                        $data['work_week_range'] = '';
                    }
                } else {
                    $data['work_week_formatted'] = 'Invalid Week';
                    $data['work_week_short'] = 'N/A';
                    $data['work_week_range'] = '';
                }
            } catch (Exception $e) {
                $data['work_week_formatted'] = 'Invalid Week';
                $data['work_week_short'] = 'N/A';
                $data['work_week_range'] = '';
            }
        } else {
            $data['work_week_formatted'] = 'Not specified';
            $data['work_week_short'] = 'N/A';
            $data['work_week_range'] = '';
        }
        
        // Truncate tasks for list views
        if (isset($data['tasks']) && strlen($data['tasks']) > 100) {
            $data['tasks_truncated'] = substr($data['tasks'], 0, 100) . '...';
        } else {
            $data['tasks_truncated'] = $data['tasks'] ?? '';
        }
        
        return $data;
    }
    
    /**
     * Prepare multiple records for display in list views
     * 
     * Processes an array of records through prepare_for_display().
     * Maintains object structure for consistency with Trongate patterns.
     * 
     * @param array $rows Array of record objects from database
     * @return array Array of objects with formatted display fields
     */
    public function prepare_records_for_display(array $rows): array {
        $prepared = [];
        foreach ($rows as $row) {
            $row_array = (array) $row;
            $prepared[] = (object) $this->prepare_for_display($row_array);
        }
        return $prepared;
    }
}
