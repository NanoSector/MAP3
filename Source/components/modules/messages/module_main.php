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

class homeModule
{
        public $loaded = false;
        
        public function prepare()
        {
                global $current_template, $map3, $boards;
                
                // Lets boot up the template.
                $map3->loadTemplate('home');
        }
        
        
}
