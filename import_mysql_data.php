<?php
/**
 * Import data from MySQL dump to SQLite database
 */

$mysqlFile = 'db/lavilhda__2025-08-20_11-47-02.sql';
$sqliteFile = 'db_khumbila.sqlite';

// Read the MySQL dump file
$content = file_get_contents($mysqlFile);

// Connect to SQLite database
$pdo = new PDO('sqlite:' . $sqliteFile);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Clear existing data
$tables = ['tbl_about', 'tbl_admin', 'tbl_programs', 'tbl_services', 'tbl_slider', 'tbl_packages', 'tbl_contact', 'tbl_team', 'tbl_cust_contact', 'tbl_enquire', 'tbl_gallery', 'tbl_seo', 'tbl_subscribers', 'tbl_trip_gallery', 'tbl_values'];

foreach ($tables as $table) {
    try {
        $pdo->exec("DELETE FROM $table");
        echo "Cleared table: $table\n";
    } catch (Exception $e) {
        // Table might not exist, continue
    }
}

// Extract INSERT statements
$insertPattern = '/INSERT INTO `([^`]+)` VALUES\s*\((.*?)\);/s';
preg_match_all($insertPattern, $content, $matches, PREG_SET_ORDER);

$importedCount = 0;

foreach ($matches as $match) {
    $tableName = $match[1];
    $valuesString = $match[2];
    
    // Parse the values
    $values = parseValues($valuesString);
    
    if (empty($values)) continue;
    
    // Get column names for the table
    $columns = getTableColumns($pdo, $tableName);
    if (empty($columns)) continue;
    
    // Prepare INSERT statement
    $placeholders = str_repeat('?,', count($columns) - 1) . '?';
    $sql = "INSERT INTO $tableName (" . implode(',', $columns) . ") VALUES ($placeholders)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($values);
        $importedCount++;
        echo "Imported $importedCount records into $tableName\n";
    } catch (Exception $e) {
        echo "Error importing into $tableName: " . $e->getMessage() . "\n";
    }
}

echo "\nImport completed! Total records imported: $importedCount\n";

function parseValues($valuesString) {
    $values = [];
    $inQuotes = false;
    $currentValue = '';
    $quoteChar = '';
    $parenCount = 0;
    
    for ($i = 0; $i < strlen($valuesString); $i++) {
        $char = $valuesString[$i];
        
        if ($char === '(' && !$inQuotes) {
            $parenCount++;
            if ($parenCount === 1) {
                $currentValue = '';
                continue;
            }
        } elseif ($char === ')' && !$inQuotes) {
            $parenCount--;
            if ($parenCount === 0) {
                $values[] = trim($currentValue);
                $currentValue = '';
                continue;
            }
        } elseif (($char === '"' || $char === "'") && !$inQuotes) {
            $inQuotes = true;
            $quoteChar = $char;
        } elseif ($char === $quoteChar && $inQuotes) {
            $inQuotes = false;
            $quoteChar = '';
        } elseif ($char === ',' && !$inQuotes && $parenCount === 1) {
            $values[] = trim($currentValue);
            $currentValue = '';
            continue;
        }
        
        $currentValue .= $char;
    }
    
    // Clean up values
    $cleanedValues = [];
    foreach ($values as $value) {
        $value = trim($value);
        if ($value === 'NULL') {
            $cleanedValues[] = null;
        } else {
            // Remove quotes and unescape
            $value = trim($value, "'\"");
            $value = str_replace("\\'", "'", $value);
            $value = str_replace('\\"', '"', $value);
            $value = str_replace('\\n', "\n", $value);
            $value = str_replace('\\r', "\r", $value);
            $value = str_replace('\\t', "\t", $value);
            $cleanedValues[] = $value;
        }
    }
    
    return $cleanedValues;
}

function getTableColumns($pdo, $tableName) {
    try {
        $stmt = $pdo->query("PRAGMA table_info($tableName)");
        $columns = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $columns[] = $row['name'];
        }
        return $columns;
    } catch (Exception $e) {
        return [];
    }
}
?>
