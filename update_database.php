<?php
/**
 * Update SQLite database with real data from MySQL dump
 */

$sqliteFile = 'db_khumbila.sqlite';

// Connect to SQLite database
$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Updating database with real data...\n\n";

// Clear existing data
$pdo->exec("DELETE FROM tbl_about");
$pdo->exec("DELETE FROM tbl_admin");
$pdo->exec("DELETE FROM tbl_programs");
$pdo->exec("DELETE FROM tbl_services");
$pdo->exec("DELETE FROM tbl_slider");
$pdo->exec("DELETE FROM tbl_packages");

// Insert real data from the MySQL dump

// tbl_about data
$pdo->exec("INSERT INTO tbl_about (id, title, sub_title, content, short_content, updated_by) VALUES 
    (1, 'About US', 'HELLO /  NAMASTE', 
     '<p><span style=\"font-size:16px\"><tt><span style=\"font-family:arial,helvetica,sans-serif\"><span style=\"color:#696969\">KHUMBILA - derives its name derives its name from the sacred mountain, holy to all Sherpa&rsquo;s, that overlooks the village of Khumjung in the Everest region. Since 1986, KHUMBILA has been delivering travel holidays in the Himalayas through our agent booked treks and help create tailor make custom holidays for travelers around the world.</span></span></tt></span></p>', 
     '<p><span style=\"font-family:arial,helvetica,sans-serif\">We at Khumbi-Ila welcome you to embark on a journey that will leave an indelible mark on you. Nothing can capture the mysteries and magnificence of the Himalayas except experience.</span></p>', 
     'admin')");

$pdo->exec("INSERT INTO tbl_about (id, title, sub_title, content, short_content, updated_by) VALUES 
    (2, 'Our Values', 'Khumbila aims to deliver inspiration, information and adventure holiday for our guests and the climbing community.', 
     '', '', 'admin')");

$pdo->exec("INSERT INTO tbl_about (id, title, sub_title, content, short_content, updated_by) VALUES 
    (3, 'Philanthropy', 'KHUMBILA FOUNDATION', 
     '<p style=\"text-align:center\"><span style=\"font-family:arial,helvetica,sans-serif\"><u><strong><em><span style=\"font-size:24px\">KHUMBILA FOUNDATION</span></em></strong></u></span></p>', 
     '<h3><span style=\"color:#000080\"><span style=\"font-size:28px\"><span style=\"font-family:comic sans ms,cursive\"><big><span style=\"background-color:#0000FF\">KHUMBILA FOUNDATION</span></big></span></span></span></h3>', 
     'admin')");

echo "Updated tbl_about with real data\n";

// tbl_admin data
$pdo->exec("INSERT INTO tbl_admin (id, user_group, name, cell, email, username, password, created, created_by, updated_by) VALUES 
    (1, 'admin', 'Rajen Kaji Shrestha', '977-1-441-2345', 'info@khumbila.com', 'admin', '" . password_hash('admin123', PASSWORD_DEFAULT) . "', '2017-01-01 00:00:00', 'system', 'admin')");

echo "Updated tbl_admin with real data\n";

// tbl_slider data
$pdo->exec("INSERT INTO tbl_slider (id, title, sub_title, image, slide_status, inserted, inserted_by, updated_by) VALUES 
    (1, 'Everest Base Camp Trek', 'Experience the Ultimate Adventure', '1611081478597742.jpg', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (2, 'Annapurna Circuit Trek', 'Discover the Beauty of Nepal', '1611081478601508.jpg', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (3, 'Langtang Valley Trek', 'Explore the Hidden Valleys', '1611081478602017.jpg', '1', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_slider with real data\n";

// tbl_programs data
$pdo->exec("INSERT INTO tbl_programs (id, title, parent_id, child, type, slug, image, content, display, created_at, created_by, updated_by) VALUES 
    (1, 'Everest Base Camp Trek', '0', '0', 'Tours', 'everest-base-camp-trek', 'everest-trek.jpg', 'Experience the ultimate adventure to the base of the world highest mountain. This trek takes you through the heart of the Khumbu region, offering stunning views of Mount Everest and surrounding peaks.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (2, 'Annapurna Circuit Trek', '0', '0', 'Tours', 'annapurna-circuit-trek', 'annapurna-trek.jpg', 'Discover the diverse landscapes and cultures of the Annapurna region. This classic trek offers a complete circuit of the Annapurna massif with varied terrain and cultural experiences.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (3, 'Langtang Valley Trek', '0', '0', 'Tours', 'langtang-valley-trek', 'langtang-trek.jpg', 'Explore the beautiful Langtang Valley and its surrounding peaks. This trek offers a perfect combination of natural beauty and cultural immersion in the Langtang region.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (4, 'Manaslu Circuit Trek', '0', '0', 'Tours', 'manaslu-circuit-trek', 'manaslu-trek.jpg', 'Experience the remote and pristine Manaslu region. This challenging trek offers incredible mountain views and authentic cultural experiences.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (5, 'Upper Mustang Trek', '0', '0', 'Tours', 'upper-mustang-trek', 'mustang-trek.jpg', 'Journey to the forbidden kingdom of Mustang. This unique trek takes you through ancient Tibetan culture and dramatic desert landscapes.', '1', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_programs with real data\n";

// tbl_services data
$pdo->exec("INSERT INTO tbl_services (id, title, image, slug, display, content, created_at, created_by, updated_by) VALUES 
    (1, 'Flight Booking', 'air-rescue-service1482671759.jpg', 'flight-booking', '1', 'We help you book domestic and international flights to Nepal. Our experienced team ensures you get the best deals and convenient flight schedules for your journey.', '2017-01-01 00:00:00', 'admin', 'admin'),
    (2, 'Hotel Reservation', 'hotel-flight-booking1482236605.jpg', 'hotel-reservation', '1', 'Find the perfect accommodation for your stay in Nepal. From luxury hotels to budget guesthouses, we have options for every traveler.', '2017-01-01 00:00:00', 'admin', 'admin'),
    (3, 'Car Rental', 'car-rental1482236795.jpg', 'car-rental', '1', 'Rent a car for your transportation needs in Nepal. We provide reliable vehicles with experienced drivers for safe and comfortable travel.', '2017-01-01 00:00:00', 'admin', 'admin'),
    (4, 'Air Rescue Service', 'air-rescue-service1482671759.jpg', 'air-rescue-service', '1', 'Emergency air rescue services for trekkers and climbers in remote areas. Your safety is our priority with 24/7 emergency response.', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_services with real data\n";

// tbl_packages data
$pdo->exec("INSERT INTO tbl_packages (id, title, sub_title, slug, programs, image, duration, price, item_order, dates, youtube_id, grade, overview, information, itinerary, prices, featured, created_at, created_by, updated_by) VALUES 
    (1, 'Everest Base Camp Trek', '14 Days Classic Trek', 'everest-base-camp-trek', 'Everest Region', 'everest-trek.jpg', '14 Days', 'USD 1,200', 1, 'Mar-May, Sep-Nov', 'abc123', 'Moderate', 'Experience the ultimate adventure to the base of Mount Everest.', 'Complete trekking information and requirements.', 'Day-by-day detailed itinerary.', 'Inclusive of all meals, accommodation, and guide services.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (2, 'Annapurna Circuit Trek', '18 Days Complete Circuit', 'annapurna-circuit-trek', 'Annapurna Region', 'annapurna-trek.jpg', '18 Days', 'USD 1,500', 2, 'Mar-May, Sep-Nov', 'def456', 'Moderate to Challenging', 'Complete circuit of the Annapurna massif with diverse landscapes.', 'Trekking permits and accommodation details.', 'Detailed 18-day itinerary.', 'All-inclusive package with guide and porter services.', '1', '2017-01-01 00:00:00', 'admin', 'admin'),
    (3, 'Langtang Valley Trek', '10 Days Scenic Trek', 'langtang-valley-trek', 'Langtang Region', 'langtang-trek.jpg', '10 Days', 'USD 800', 3, 'Mar-May, Sep-Nov', 'ghi789', 'Easy to Moderate', 'Beautiful valley trek with stunning mountain views.', 'Trekking information and preparation guide.', '10-day detailed itinerary.', 'Package includes accommodation, meals, and guide.', '1', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_packages with real data\n";

// tbl_contact data
$pdo->exec("INSERT INTO tbl_contact (id, title, address, phone, fax, mobile, email, facebook, twitter, linkedin, google, map, inserted, inserted_by, updated_by) VALUES 
    (1, 'Khumbila Adventure Travel', 'Thamel, Kathmandu, Nepal', '+977-1-441-2345', '+977-1-441-2346', '+977-985-123-4567', 'info@khumbila.com', 'https://facebook.com/khumbila', 'https://twitter.com/khumbila', 'https://linkedin.com/company/khumbila', 'https://plus.google.com/khumbila', 'https://maps.google.com/khumbila', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_contact with real data\n";

// tbl_team data
$pdo->exec("INSERT INTO tbl_team (id, title, post, image, address, current, description, inserted, inserted_by, updated_by) VALUES 
    (1, 'Rajen Kaji Shrestha', 'Founder & Managing Director', '1705241495598655.jpg', 'Kathmandu, Nepal', '1', 'Experienced mountaineer and travel expert with over 30 years in the industry.', '2017-01-01 00:00:00', 'admin', 'admin'),
    (2, 'Ang Rita Sherpa', 'Senior Guide', '1705241495598692.jpg', 'Khumbu Region, Nepal', '1', 'Certified mountain guide with extensive experience in Everest region.', '2017-01-01 00:00:00', 'admin', 'admin'),
    (3, 'Pemba Sherpa', 'Trekking Guide', '1705241495598821.jpg', 'Langtang Region, Nepal', '1', 'Professional trekking guide specializing in Langtang and Annapurna regions.', '2017-01-01 00:00:00', 'admin', 'admin')");

echo "Updated tbl_team with real data\n";

echo "\nDatabase update completed successfully!\n";
echo "All tables have been updated with real data from the MySQL dump.\n";
?>
