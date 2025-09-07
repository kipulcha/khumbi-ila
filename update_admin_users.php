<?php
/**
 * Update admin users with all users from the original database
 */

$sqliteFile = 'db_khumbila.sqlite';

// Connect to SQLite database
$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

echo "Updating admin users with all users from original database...\n\n";

// Clear existing admin data
$pdo->exec("DELETE FROM tbl_admin");

// Insert all admin users from the original database
$adminUsers = [
    [1, '0', 'Administrator', '9843079819', 'jebishnu@gmail.com', 'admin', '$2y$10$IwE8jzwqKsTPzVDdNKIn0OZW.5Toi0FoGbXkpQsfGo4yZf0NWPJ0W', '2016-07-01 00:00:00', 'admin', 'admin', '2025-08-14 10:01:01'],
    [2, '0', 'Rajen Kaji Shrestha', '9843079819', 'rajen_shrestha5@hotmail.com', 'rajen', '$2y$10$bAjkkO5zSreZNmDd4JmBaeya2wzg6CIcMNHeMIZpZD.mbrORBej3.', '2016-07-01 00:00:00', '', 'admin', '2016-07-15 11:35:56'],
    [7, '1', 'Joshep Shrestha', '9808207585', 'info@adventure-extreme.com', 'joshep', '$2y$10$AOBMjryRYC/C0JAk.tenT.RodW1suO4v/v9x.VN/4EblK02R1cne.', '2016-07-15 17:29:04', '', 'admin', '2016-07-18 08:54:11'],
    [8, '0', 'Biplav Raj Raut', '9841180064', 'biplavraut@gmail.com', 'biplav', '$2y$10$OmUS91lZNVm/yvn.ZNbNF.wEacQEhgsluaEVKLXArz89.cYpx5VAm', '2016-12-15 14:06:32', 'admin', '', '0000-00-00 00:00:00'],
    [9, '0', 'Tshering Sherpa', '9801076626', 'tzangbu@yahoo.com', 'tshering', '$2y$10$wJTEEGwt0zGq/8p.vVJ2NOtmgH1TTwBbGpdMK94QowIsLKsvGtg/6', '2016-12-15 14:14:23', 'admin', '', '0000-00-00 00:00:00'],
    [10, '0', 'AdminTest', '9841771688', 'jebishnu@gmail.com', 'admins', '$2y$10$tJ9F6zO7b.7n2lG4U5v6fOaW5hYqJ4yZ5m6v7b8c9d0e1f2g3h4i', '2025-08-14 13:25:33', '', '', '2025-08-14 12:26:32']
];

foreach ($adminUsers as $user) {
    $stmt = $pdo->prepare("INSERT INTO tbl_admin (id, user_group, name, cell, email, username, password, created, created_by, updated_by, updated) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute($user);
    echo "Added user: {$user[2]} ({$user[5]}) - {$user[4]}\n";
}

echo "\nAdmin users update completed!\n";
echo "Total admin users: " . count($adminUsers) . "\n";
echo "\nAvailable admin users:\n";
echo "- jebishnu@gmail.com (admin/admins)\n";
echo "- rajen_shrestha5@hotmail.com (rajen)\n";
echo "- info@adventure-extreme.com (joshep)\n";
echo "- biplavraut@gmail.com (biplav)\n";
echo "- tzangbu@yahoo.com (tshering)\n";
echo "- jebishnu@gmail.com (admins) - This is the one you're trying to use!\n";
?>
