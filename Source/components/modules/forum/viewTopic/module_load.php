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

function module_setup()
{
        global $currmoddir;
        
        // Require this file.
        require_once($currmoddir . '/module_main.php');
        
        // Set up the class.
        $thismodule = new messageIndexModule;
        
        // Then return it.
        return $thismodule;
}