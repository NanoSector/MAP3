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

class profileModule
{
        public $loaded = false;
        
        public function prepare()
        {
                global $current_template, $map3, $user, $txt, $errors, $udata;
                
                $map3->loadLanguage('Profile');
                
                // User set?
                if (!empty($_GET['u']))
                	$u = (int) $u;
                	
                // No? Are we logged in then?
                elseif ($user->isLoggedIn)
                	$u = 0;
                	
                // Neither? Error time.
                else
                	$errors->fatal('Unable to determine user. User not found.');
                	
                $udata = $user->grabUserData($u);
                
                // Lets boot up the template.
                $map3->loadTemplate('profile');
        }
        
        
}
