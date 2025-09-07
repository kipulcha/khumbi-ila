<?php
/**
 * Convert MySQL dump to SQLite format
 */

$mysqlFile = 'db/lavilhda__2025-08-20_11-47-02.sql';
$sqliteFile = 'db_khumbila.sqlite';

// Read the MySQL dump file
$content = file_get_contents($mysqlFile);

// Convert MySQL-specific syntax to SQLite
$content = str_replace('ENGINE=InnoDB', '', $content);
$content = str_replace('AUTO_INCREMENT', 'AUTOINCREMENT', $content);
$content = str_replace('DEFAULT CHARSET=utf8', '', $content);
$content = str_replace('COLLATE=utf8_general_ci', '', $content);
$content = str_replace('int(', 'INTEGER(', $content);
$content = str_replace('varchar(', 'TEXT(', $content);
$content = str_replace('text', 'TEXT', $content);
$content = str_replace('longtext', 'TEXT', $content);
$content = str_replace('mediumtext', 'TEXT', $content);
$content = str_replace('tinyint(', 'INTEGER(', $content);
$content = str_replace('datetime', 'TEXT', $content);
$content = str_replace('timestamp', 'TEXT', $content);
$content = str_replace('enum(', 'TEXT(', $content);

// Remove MySQL-specific comments and commands
$content = preg_replace('/\/\*.*?\*\//s', '', $content);
$content = preg_replace('/--.*$/m', '', $content);
$content = preg_replace('/\/\*M!.*?\*\//', '', $content);
$content = preg_replace('/SET.*?;/', '', $content);
$content = preg_replace('/LOCK TABLES.*?;/', '', $content);
$content = preg_replace('/UNLOCK TABLES;/', '', $content);

// Split into individual statements
$statements = explode(';', $content);
$sqliteStatements = [];

foreach ($statements as $statement) {
    $statement = trim($statement);
    if (empty($statement) || strpos($statement, 'CREATE TABLE') === false) {
        continue;
    }
    
    // Clean up the statement
    $statement = preg_replace('/\s+/', ' ', $statement);
    $sqliteStatements[] = $statement . ';';
}

// Create SQLite database
$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Execute the statements
foreach ($sqliteStatements as $statement) {
    try {
        $pdo->exec($statement);
        echo "Executed: " . substr($statement, 0, 50) . "...\n";
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
        echo "Statement: " . $statement . "\n";
    }
}

echo "Database conversion completed!\n";
echo "SQLite database created: " . $sqliteFile . "\n";
?>
