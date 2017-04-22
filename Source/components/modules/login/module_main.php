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

class loginModule
{
        public $loaded = false;
        
        public function prepare()
        {
                global $map3, $user, $errors, $txt, $context;
                
                // Are we logging out instead?
                if ($map3->currentaction == 'logout')
                {
                	$user->logout();
                	$context['current_template'] = 'template_logout_success';
                	$map3->redirect(null, 3);
                }
                
                // Logging in?
                elseif (isset($_GET['do']) && isset($_POST['m3_username']))
                {
                	// Test to see if everything exists.
                	if (empty($_POST['m3_username']))
                		$errors->fatal($txt['login_no_username']);
                		
                	if (empty($_POST['m3_password']))
                		$errors->fatal($txt['login_no_password']);
                		
                	$result = $user->login((string) $_POST['m3_username'], (string) $_POST['m3_password']);
                	
                	if (!$result)
                		$errors->fatal($txt['login_error_occured']);
                	else
                	{
				$result = $this->setLoginCookie(3600, $_POST['m3_username'], $_POST['m3_password']);
	                	$context['current_template'] = 'template_login_success';
	                	
	                	// Redirect to the index in 3 seconds.
	                	$map3->redirect(null, 3);
	                }
                }
		
		// Load some language strings.
		$map3->loadLanguage('login');
                
                // Lets boot up the template.
                $map3->loadTemplate('login');
        }
	
	private function setLoginCookie($length, $username, $pass)
	{
		global $db, $modSettings, $user;
		
		$length = (int) $length;
		$username = (string) $username;
		$pass = (string) $pass;
		
		// If the length is empty, go for a single day.
		if (empty($length))
			$length = 3600;
			
		// If the ID or the password are empty, screw 'em.
		if (empty($username) || empty($pass))
			return false;
		
		// Are we able to login with this details?
		$password_hash = sha1(sha1($pass) . strtolower($username) . $modSettings['m3_rkey']);
		if (!$user->checkUserExists(array(
				'username' => $username,
				'password_hash' => $password_hash,
			)))
			return false;
		
		// Are we logged in? No?
		if (!$user->isLoggedIn)
			return false;
		
		// Generate a random session ID, and check if it does exist yet.
		$session_id = sha1(md5(time()) . sha1($modSettings['m3_rkey']) . $username . time() . md5($length));
		
		$rnum = 0;
		while ($this->checkSessionExists($session_id))
		{
			$rnum += 1234;
			$session_id = sha1($session_id . md5($rnum));
		}
		
		// Now set up the cookie.
		$expires = time() + $length;
		setcookie('m3_user_' . sha1($modSettings['m3_rkey']), $session_id, $expires);
		
		// Finally, insert some data into the database.
		$db->query(
			'INSERT INTO {db_prefix}log_online (session, userid, passhash, expires)
			VALUES ("' . $db->escape_string($session_id) . '", "' . $db->escape_string(strtolower($username)) . '", "' . $db->escape_string($password_hash) . '", "' . $expires . '")');
		
		return true;
	}
        
        function checkSessionExists($session_id)
	{
		global $db;
		
		$result = $db->query('
			SELECT userid
			FROM {db_prefix}log_online
			WHERE session = "' . $session_id . '"');
		
		$exists = $db->num_rows($result) == 1;
		
		$db->free_result($result);
		
		return $exists;
	}
}
