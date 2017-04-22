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

// No two instances are allowed.
if (defined('MAP_START'))
        die('You cannot run two instances of MAP3 at a time.');
        
// Check the PHP version.
if (version_compare(PHP_VERSION, '5.2.9', '<'))
        die('You need version 5.2.9 or higher of PHP for MAP3 to function correctly.');

// Mark it as started. Since we have, err, started.
define('MAP_START', 1);

// Start the output buffer.
ob_start();

// And the sessions.
session_start();

// Turn Magic Quotes off. They just suck.
if (function_exists('set_magic_quotes_runtime'))
	@set_magic_quotes_runtime(0);

// Then set the default timezone, else PHP'll crap out.
date_default_timezone_set('UTC');

// Set error reporting to the setting we like.
error_reporting(defined('E_STRICT') ? E_ALL | E_STRICT : E_ALL);

// We'd like some settings, to get us started.
require_once(dirname(__FILE__) . '/Settings.php');

// Then include our bootstrapper, aka Load.
require_once($classdir . '/Load.class.php');

// Initiate our function class. Can be expanded later.
$map3 = new map3;

$map3->loadClassFile('Errors');
$errors = new Errors;

// Set the error handler.
set_error_handler(array($errors, 'handle_error'));

// Initiate the database connction.
$db = $map3->initiateDB();

// Now determine the module.
$module = $map3->determineModule();

// Load the Users class.
$map3->loadClassFile('Users');
$user = new generalUser;

// Now reload the settings.
$map3->reloadSettings();
global $modSettings, $context, $urls;

// Try a login.
$user->loginFromSession();
//echo var_dump($_COOKIE);
$user->loginFromCookie();

// And the theme and language.
$map3->reloadTheme($modSettings['ctheme']);
$map3->currenttheme = $modSettings['ctheme'];
$map3->reloadLanguagePack();

// Detect the browser we are running.
$map3->detectBrowser();

// Load jBBCode.
require_once($classdir . '/jbbcode/Parser.php');
$parser = new JBBCode\Parser();
$parser->loadDefaultCodes();
$parser->addBBCode('window', '<div class="cat_bar">{option}</div><div class="window"><div class="windowcontents">{param}</div></div>', true);

// If the module needs preparing, do it now.
if (method_exists($module, 'prepare'))
    $module->prepare();

// No template set?
if (empty($context['current_template']))
	$context['current_template'] = 'template_main';

$map3->flushOutputBuffer($context['current_template']);
