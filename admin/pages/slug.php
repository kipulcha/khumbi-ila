<?php
/** *******************************************************************
 * Slug Script for Vanilla PHP
 * A simple Script that creates a slug for you and checks it uniqueness
 **********************************************************************
 * author: Rajen Kaji Shrestha
 * authorURI: http://www.rajenshrestha.com.np , http://www.ktmrush.com
 **********************************************************************
 **/

// Main Procedure
function slug($text, $tblName, $temp){
    if(!($temp)):
        $temp = 1;
    endif;

    // Database Connections
    require_once '../pdo-lib/PDOModel.php';
    $mydb = new PDOModel(); //create object of the PDOModel class

    //connect to database
    //$mydb->connect("localhost", "root", "", "db_khumbila");
    $mydb->connect("localhost", "khumbila_root", "k@thm@ndu123", "khumbila_db");

    // replace non letter or digits by -
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);

    // trim
    $text = trim($text, '-');

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // lowercase
    $text = strtolower($text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    if (empty($text))
    {
        return 'n-a';
    }

    // DB table Get slugs as Array
    $mydb->where('slug', $text);
    $result = $mydb->select($tblName);
    // Uniqueness Check Condition
    if($mydb->totalRows > 0):
        $slug =  preg_replace('/[0-9]+/', '', $text) . $temp;
        $temp = $temp + 1;
        return slug($slug, $tblName, $temp);
    else:
        return $text;
    endif;
}

?>