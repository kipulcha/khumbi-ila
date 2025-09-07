<?php
/**
 * Mock Database Class for Development
 * This provides sample data when the real database is not available
 */

class MockDatabase {
    public $totalRows = 0;
    public $orderByCols;
    public $andOrOperator = "AND";
    
    public function connect($host, $username, $password, $database) {
        // Mock connection - always return true
        return true;
    }
    
    public function select_field($field, $table, $condition) {
        // Return mock data based on table and field
        switch($table) {
            case 'tbl_slider':
                if($field == 'image') {
                    return 'sample-slider-1.jpg';
                }
                break;
            case 'tbl_about':
                if($field == 'short_content') {
                    return 'Welcome to Khumbila Adventure Travel - Your Gateway to Nepal Adventures';
                }
                break;
        }
        return '';
    }
    
    public function select($table, $fields = '*') {
        // Return mock data based on table
        switch($table) {
            case 'tbl_programs':
                return [
                    [
                        'id' => 1,
                        'title' => 'Everest Base Camp Trek',
                        'slug' => 'everest-base-camp-trek',
                        'type' => 'Tours',
                        'parent_id' => 0,
                        'child' => '0',
                        'display' => '1'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Annapurna Circuit Trek',
                        'slug' => 'annapurna-circuit-trek',
                        'type' => 'Tours',
                        'parent_id' => 0,
                        'child' => '0',
                        'display' => '1'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Langtang Valley Trek',
                        'slug' => 'langtang-valley-trek',
                        'type' => 'Tours',
                        'parent_id' => 0,
                        'child' => '0',
                        'display' => '1'
                    ]
                ];
            case 'tbl_services':
                return [
                    [
                        'id' => 1,
                        'title' => 'Flight Booking',
                        'slug' => 'flight-booking',
                        'display' => '1'
                    ],
                    [
                        'id' => 2,
                        'title' => 'Hotel Reservation',
                        'slug' => 'hotel-reservation',
                        'display' => '1'
                    ],
                    [
                        'id' => 3,
                        'title' => 'Car Rental',
                        'slug' => 'car-rental',
                        'display' => '1'
                    ]
                ];
            case 'tbl_admin':
                return [
                    [
                        'id' => 1,
                        'username' => 'admin',
                        'password' => password_hash('admin123', PASSWORD_DEFAULT),
                        'name' => 'Administrator',
                        'user_group' => 'admin'
                    ]
                ];
        }
        return [];
    }
    
    public function where($field, $value, $operator = '=') {
        // Mock where clause - just return this object for chaining
        return $this;
    }
    
    public function orderByCols($columns) {
        // Mock order by - just return this object for chaining
        return $this;
    }
    
    public function redirect($url) {
        // Mock redirect - just return true
        return true;
    }
}
?>
