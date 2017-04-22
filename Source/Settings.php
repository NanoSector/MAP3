<?php

/** 
 * MAP3 Software
 * Open source forum software written in PHP 
 *  
 * Version: 1.0 Alpha 
 * Developed by: Rick "Yoshi2889" and Robert "Stijllozekip" 
 * Website: http://map3cms.co.cc 
 *  
 * A list of all contributors can be found on the credits page in the admin panel 
 * 
 * License: BSD
 *
*/

$settings = array(
        'db_host' => 'localhost',
        'db_user' => 'root',
        'db_password' => '',
        'db_name' => 'map3',
        'db_prefix' => 'm3_',
        'db_port' => '3306',
        
        // Below this line are advanced settings. If you love tweaking you can flip these but be careful.
        'use_map_error_handler' => true,
        'theme_override' => '',
);

// Home URL.
$urls['home'] = 'http://188.142.89.101/map3';

// Base directory. No trailing slash.
$basedir = '/var/www/map3';

// More URLs. Usually no need to change these.
$urls['script'] = $urls['home'] . '/index.php';
$urls['components'] = $urls['home'] . '/components';
$urls['styles'] = $urls['home'] . '/components/styles';

// Uncomment and set the following lines if you want to use custom paths to those. Usually no need to do so.
//$classdir = '';
//$compdir = '';
//$styledir = '';

// *------------ DO NOT EDIT BELOW THIS LINE! ------------* //
// Editing below here is like voiding your warranty. We won't be able to give support for a modified MAP3.

// Fiddle out the other paths. Less writing to do ;)
if (!isset($compdir))
        $compdir = $basedir . '/components';
        
if (!isset($classdir))
        $classdir = $compdir . '/classes';
        
if (!isset($styledir))
        $styledir = $compdir . '/styles';
        
$map_ver = '1.0 Alpha';

?>
