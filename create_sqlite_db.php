<?php
/**
 * Create SQLite database with essential tables and data
 */

$sqliteFile = 'db_khumbila.sqlite';

// Remove existing database
if (file_exists($sqliteFile)) {
    unlink($sqliteFile);
}

// Create SQLite database
$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create tables
$tables = [
    "CREATE TABLE tbl_about (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        sub_title TEXT NOT NULL,
        content TEXT NOT NULL,
        short_content TEXT NOT NULL,
        updated TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_admin (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_group TEXT NOT NULL,
        name TEXT NOT NULL,
        cell TEXT NOT NULL,
        email TEXT NOT NULL,
        username TEXT NOT NULL,
        password TEXT NOT NULL,
        created TEXT NOT NULL,
        created_by TEXT NOT NULL,
        updated_by TEXT NOT NULL,
        updated TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )",
    
    "CREATE TABLE tbl_programs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        parent_id TEXT NOT NULL,
        child INTEGER NOT NULL,
        type TEXT NOT NULL,
        slug TEXT NOT NULL,
        image TEXT NOT NULL,
        content TEXT NOT NULL,
        display TEXT NOT NULL,
        created_at TEXT NOT NULL,
        created_by TEXT NOT NULL,
        updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_services (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        image TEXT NOT NULL,
        slug TEXT NOT NULL,
        display TEXT NOT NULL,
        content TEXT NOT NULL,
        created_at TEXT NOT NULL,
        created_by TEXT NOT NULL,
        updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_slider (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        sub_title TEXT NOT NULL,
        image TEXT NOT NULL,
        slide_status TEXT NOT NULL,
        inserted TEXT NOT NULL,
        inserted_by TEXT NOT NULL,
        updated TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_packages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        sub_title TEXT NOT NULL,
        slug TEXT NOT NULL,
        programs TEXT NOT NULL,
        image TEXT NOT NULL,
        duration TEXT NOT NULL,
        price TEXT NOT NULL,
        item_order INTEGER NOT NULL,
        dates TEXT NOT NULL,
        youtube_id TEXT NOT NULL,
        grade TEXT NOT NULL,
        overview TEXT NOT NULL,
        information TEXT NOT NULL,
        itinerary TEXT NOT NULL,
        prices TEXT NOT NULL,
        featured TEXT NOT NULL,
        created_at TEXT NOT NULL,
        created_by TEXT NOT NULL,
        updated_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_contact (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        address TEXT NOT NULL,
        phone TEXT NOT NULL,
        fax TEXT NOT NULL,
        mobile TEXT NOT NULL,
        email TEXT NOT NULL,
        facebook TEXT NOT NULL,
        twitter TEXT NOT NULL,
        linkedin TEXT NOT NULL,
        google TEXT NOT NULL,
        map TEXT NOT NULL,
        inserted TEXT NOT NULL,
        inserted_by TEXT NOT NULL,
        updated TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )",
    
    "CREATE TABLE tbl_team (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        post TEXT NOT NULL,
        image TEXT NOT NULL,
        address TEXT NOT NULL,
        current TEXT NOT NULL,
        description TEXT NOT NULL,
        inserted TEXT NOT NULL,
        inserted_by TEXT NOT NULL,
        updated TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_by TEXT NOT NULL
    )"
];

// Create tables
foreach ($tables as $sql) {
    $pdo->exec($sql);
    echo "Created table: " . substr($sql, 13, 20) . "...\n";
}

// Insert sample data
$pdo->exec("INSERT INTO tbl_about (title, sub_title, content, short_content, updated_by) VALUES 
    ('About Khumbila Adventure Travel', 'Your Gateway to Nepal Adventures', 
     'We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience.', 
     'Welcome to Khumbila Adventure Travel - Your Gateway to Nepal Adventures', 'admin')");

$pdo->exec("INSERT INTO tbl_admin (user_group, name, cell, email, username, password, created, created_by, updated_by) VALUES 
    ('admin', 'Administrator', '1234567890', 'admin@khumbila.com', 'admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', 
     '" . date('Y-m-d H:i:s') . "', 'system', 'admin')");

$pdo->exec("INSERT INTO tbl_slider (title, sub_title, image, slide_status, inserted, inserted_by, updated_by) VALUES 
    ('Everest Base Camp Trek', 'Experience the Ultimate Adventure', 'sample-slider-1.jpg', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Annapurna Circuit', 'Discover the Beauty of Nepal', 'sample-slider-2.jpg', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Langtang Valley Trek', 'Explore the Hidden Valleys', 'sample-slider-3.jpg', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin')");

$pdo->exec("INSERT INTO tbl_programs (title, parent_id, child, type, slug, image, content, display, created_at, created_by, updated_by) VALUES 
    ('Everest Base Camp Trek', '0', '0', 'Tours', 'everest-base-camp-trek', 'everest-trek.jpg', 'Experience the ultimate adventure to the base of the world highest mountain.', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Annapurna Circuit Trek', '0', '0', 'Tours', 'annapurna-circuit-trek', 'annapurna-trek.jpg', 'Discover the diverse landscapes and cultures of the Annapurna region.', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Langtang Valley Trek', '0', '0', 'Tours', 'langtang-valley-trek', 'langtang-trek.jpg', 'Explore the beautiful Langtang Valley and its surrounding peaks.', '1', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin')");

$pdo->exec("INSERT INTO tbl_services (title, image, slug, display, content, created_at, created_by, updated_by) VALUES 
    ('Flight Booking', 'flight-booking.jpg', 'flight-booking', '1', 'We help you book domestic and international flights to Nepal.', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Hotel Reservation', 'hotel-reservation.jpg', 'hotel-reservation', '1', 'Find the perfect accommodation for your stay in Nepal.', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin'),
    ('Car Rental', 'car-rental.jpg', 'car-rental', '1', 'Rent a car for your transportation needs in Nepal.', '" . date('Y-m-d H:i:s') . "', 'admin', 'admin')");

echo "\nDatabase created successfully!\n";
echo "SQLite database: " . $sqliteFile . "\n";
echo "Tables created: " . count($tables) . "\n";
echo "Sample data inserted successfully!\n";
?>
