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

class registerModule
{
        public $loaded = false;
        
        public function prepare()
        {
                global $context, $map3, $user, $errors, $txt;
		
		$map3->loadLanguage('register');
                
		// If we are trying to register, do so.
                if (isset($_GET['do']) && isset($_POST['m3_username']))
                {		
			// Build up the array.
			$regOptions = array(
				// Basic stuff.
				'username' => $_POST['m3_username'],
				'password' => $_POST['m3_password'],
				'cpassword' => $_POST['m3_cpassword'],
				'email' => $_POST['m3_email'],
				'email_me' => !empty($_POST['m3_email_me']),
				'email_news' => !empty($_POST['m3_email_news']),
				
				// All the optional stuff.
				'website' => $_POST['m3_website'],
				'website_title' => $_POST['m3_website_title'],
				'birthday' => array(
					'year' => (int) $_POST['m3_birthday_year'],
					'month' => (int) $_POST['m3_birthday_month'],
					'day' => (int) $_POST['m3_birthday_day']
				),
				'gender' => (int) $_POST['m3_gender'],
				'location' => $_POST['m3_location']
			);
                		
                	// Okay now for the real registering.
                	$result = $user->register($regOptions);
                	
                	// Registered? No?
                	if ($result !== true)
                		$errors->fatal($txt[$result]);
                		
                	$context['current_template'] = 'template_success';
                }
                
                // Lets boot up the template.
                $map3->loadTemplate('register');
        }
        
        
}
