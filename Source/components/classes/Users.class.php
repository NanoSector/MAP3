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

class generalUser
{
        public $isLoggedIn = false;
        public $details = array();
	private $permissions = array();

        // Grab the data of a user.
        public function grabUserData($user = 0)
        {
                global $db;
                
                // If we are logged in and the user is 0... Return the current data. No need to waste an extra query.
                if ($this->isLoggedIn && $user == 0)
                	return $this->details;
                
                // Not logged in and $user equals to 0 or the user doesn't exist? Crap in your pants.
                elseif (!$this->checkUserExists('user_id', $user))
                        return false;
                
                // Then lets grab the actual data.
                $result = $db->query('
                	SELECT user_id, username, displayname,  date_registered, email, avatar, membergroup, timeoffset, board_order
                	FROM {db_prefix}users
                	WHERE user_id = ' . (int) $user);
                	
                // No user? False.
                if ($db->num_rows($result) == 0)
                	return false;
                	
                $tdata = $db->fetch_assoc($result);
                
                $db->free_result($result);
                
                return $tdata;
        }
        
        // Check if the user exists.
        public function checkUserExists($what = 'user_id', $equals = '')
        {
                global $db;
                
                // If we are logged in and user is 0... We are done!
                if ($this->isLoggedIn && $what == 'user_id' && $equals == 0)
                        return true;
		
		if (is_array($what))
		{
			$queryparts = array();
			foreach ($what as $search => $find)
			{
				$queryparts[] = $search . ' = "' . $db->escape_string($find) . '"';
			}
			
			$query = implode(' AND ', $queryparts);
		}
		else
			$query = $what . ' = "' . $db->escape_string($equals) . '"';
                
                // Not either... We need to actually check it.
                $result = $db->query('
                        SELECT count(user_id)
                        FROM {db_prefix}users
                        WHERE ' . $query . '
                        LIMIT 1');
                
                list ($count) = $db->fetch_row($result);
                $db->free_result($result);

                // Simple.
                if ($count == 1)
                        return true;
                else
                        return false;
        }

	public function register($userData)
	{
		global $db, $modSettings;
	
		// Are we logged in?
		if ($this->isLoggedIn)
			return 'register_logout';
		
		// Set a clean array for the errors
		$errors = array();
		
		// The basic fields.
		$fields = array(
			'username' => '',
			'displayname' => '',
			'password' => '',
			'passhash' => '',
			'email' => '',
			''
		);
		
		// Is the username empty or in use?
		if (empty($userData['username']))
			return 'register_no_username';
			
		elseif ($this->checkUserExists('username', htmlspecialchars($userData['username'])))
			return 'register_username_in_use';
			
		// Fill in this parts.
		$fields['username'] = strtolower(htmlspecialchars($userData['username']));
		$fields['displayname'] = htmlspecialchars($userData['username']);
		
		// Does the e-mail match the regex?
		if(!preg_match('^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$^', $userData['email']))
			return 'no_valid_email';
			
		// Fill in the e-mail address.
		$fields['email'] = $userData['email'];
		
		// Do we have a duplicate e-mail address?
		if ($this->checkUserExists('email', htmlspecialchars($userData['email'])))
			return 'register_email_in_use';
		
		// For the password... If it's empty or doesn't fit the puzzle, quit.
		if (empty($userData['password']) || empty($userData['cpassword']))
			return 'register_no_password';
		
		elseif ($userData['password'] !== $userData['cpassword'])
			return 'register_no_match';
			
		// Fill in the password.
		$fields['passhash'] = sha1(sha1($userData['password']) . strtolower($userData['username']) . $modSettings['m3_rkey']);
		
		// Set our default membergroup.
		$fields['membergroup'] = (!empty($userData['membergroup']) ? (int) $userData['membergroup'] : 2);
		
		// Next up, optional fields.
		
		// Website URL and name.
		$fields['website_url'] = !empty($userData['website']) ? htmlspecialchars($userData['website']) : '';
		$fields['website_title'] = !empty($userData['website_title']) ? htmlspecialchars($userData['website_title']) : '';
		
		// Assemble the birthdate.
		$fields['birthdate'] = '';
		if (!empty($userData['birthday']) && !empty($userData['birthday']['year']) && !empty($userData['birthday']['month']) && !empty($userData['birthday']['day']))
		{
			$year = (int) $userData['birthday']['year'];
			$month = (int) $userData['birthday']['month'];
			$day = (int) $userData['birthday']['day'];
			
			if (!empty($year) && !empty($month) && !empty($day))
				$fields['birthdate'] = $year . '/' . $month . '/' . $day;
		}
			
		// Gender.
		$fields['gender'] = !empty($userData['gender']) ? (int) $userData['gender'] : 0;
		
		// And location.
		$fields['location'] = !empty($userData['location']) ? htmlspecialchars($userData['location']) : '';
		
		// Time to insert the data. First loop over all of the fields.
		foreach ($fields as $key => $field)
			$fields[$key] = $db->escape_string($field);
			
		// Then insert it. Monstrous query FTW.
		$db->query('
			INSERT INTO {db_prefix}users
				(
					username, 
					displayname, 
					password_hash, 
					date_registered, 
					email,
					membergroup,
					email_me,
					email_news,
					website_url,
					website_title,
					birthdate,
					gender,
					location
				)
				VALUES
				(
					"' . (string) $fields['username'] . '",
					"' . (string) $fields['displayname'] . '",
					"' . (string) $fields['passhash'] . '",
					"' . time() . '",
					"' . (string) $fields['email'] . '",
					"' . (int) $fields['membergroup'] . '",
					"' . (int) !empty($userData['email_me']) . '",
					"' . (int) !empty($userData['email_news']) . '",
					"' . (string) $fields['website_url'] . '",
					"' . (string) $fields['website_title'] . '",
					"' . (string) $fields['birthdate'] . '",
					"' . (int) $fields['gender'] . '",
					"' . (string) $fields['location'] . '"
				)');
			
		return true;
	}
	
	public function login($username, $password, $passwordIsHash = false)
	{
		global $db, $modSettings;
		
		// Already logged in? Screw you.
		if ($this->isLoggedIn)
			die('You are already logged in. You can\'t log in twice!');
		
		// Anything not set?	
		if (empty($username) || empty($password))
			return false;
			
		// Okay... Try to find the user's password.
		$result = $db->query('
			SELECT
				user_id, password_hash
			FROM {db_prefix}users
			WHERE username = "' . (string) strtolower($username) . '"
			LIMIT 1');
		
		// Do we have any?
		if ($db->num_rows($result) == 0)
			return false;
			
		$pass_hash = $db->fetch_assoc($result);
		
		$db->free_result($result);
			
		// Do the passwords match, then?
		if ($passwordIsHash == false)
			$match_with = sha1(sha1($password) . strtolower($username) . $modSettings['m3_rkey']);
		else
			$match_with = $password;
			
		if ($pass_hash['password_hash'] === $match_with)
		{
			// Set the session.
			$_SESSION['m3_session_' . $modSettings['m3_rkey']] = array(
				'username' => $username,
				'password' => $pass_hash['password_hash']
			);
			
			// We're logged in.
			$this->isLoggedIn = true;
			
			// Also, load the data.
			$this->details = $this->grabUserData($pass_hash['user_id']);
			
			// We're done.
			return true;
		}
		else
			return false;
	}
	
	public function loginFromSession()
	{
		global $db, $modSettings;
		
		// Do we have a session set?
		if (!empty($_SESSION['m3_session_' . $modSettings['m3_rkey']]))
		{
			$login = $_SESSION['m3_session_' . $modSettings['m3_rkey']];
			
			// Try a login.
			$result = $this->login($login['username'], $login['password'], true);
			
			// Either true or false.
			return $result;
		}
		else
		{
			// We are guest.
			$this->details = array(
				'id' => 0,
				'membergroup' => -1,
			);
			return false;
		}
	}
	
	public function loginFromCookie()
	{
		global $db, $modSettings;
		
		if ($this->isLoggedIn)
			return true;
		
		if (!empty($_COOKIE['m3_user_' . sha1($modSettings['m3_rkey'])]))
		{
			// Try to grab the session from the DB.
			$result = $db->query('
				SELECT userid, passhash, expires
				FROM {db_prefix}log_online
				WHERE session = "' . $db->escape_string($_COOKIE['m3_user_' . sha1($modSettings['m3_rkey'])]) . '"');
			
			// No session? Delete this cookie.
			if ($db->num_rows($result) == 0)
			{
				setcookie('m3_user_' . sha1($modSettings['m3_rkey']), '', time() - 3600);
				return false;
			}
			
			// Grab the session data, and login with it.
			list ($username, $pass, $expires) = $db->fetch_row($result);
			
			$db->free_result($result);
			
			// Has the session expired? :(
			if ($expires < time())
			{
				// Damn. Delete the session and forget about us ever trying this.
				$db->query('
					DELETE FROM {db_prefix}log_online
					WHERE session = "' . $db->escape_string($_COOKIE['m3_user_' . sha1($modSettings['m3_rkey'])]) . '"');
				
				setcookie('m3_user_' . sha1($modSettings['m3_rkey']), '', time() - 3600);
				
				return false;
			}
			
			// Do the actual login.
			$result = $this->login(strtolower($username), $pass, true);
			
			// If we have an error, maybe the password has changed or something? Can't tell. Just return the result and be done with it.
			return $result;
		}
		else
			return false;
	}
	
	/**
	 * Logs the user out. No parameters accepted.
	 */
	public function logout()
	{
		global $modSettings, $db;
		
		// Can't log someone out who isn't logged in.
		if (!$this->isLoggedIn)
			return false;
		
		// Get the cookie to delete itself.
		setcookie('m3_user_' . sha1($modSettings['m3_rkey']), '', time() - 3600);
		
		// Then delete any sessions associated.
		$db->query(
			'DELETE FROM {db_prefix}log_online
			WHERE userid = "' . $db->escape_string(strtolower($this->details['username'])) . '"');
		
		// And kill the session.
		unset($_SESSION['m3_session_' . $modSettings['m3_rkey']]);
		
		// We're done!
		return true;
	}
	
	public function isAllowed($permission)
	{
		global $db;
		
		// First check if it's already checked.
		if (array_key_exists($permission, $this->permissions))
			return $this->permissions[$permission];
		
		// Else grab it from the database.
		else
		{
			// Grab the permissions with this name.
			$result = $db->query('
				SELECT id_membergroup
				FROM {db_prefix}permissions
				WHERE permission_name = "' . $db->escape_string($permission) . '"');
			
			// Uhh... No rows? Not a valid permission.
			if ($db->num_rows($result) == 0)
				return false;
			
			// Grab the row.
			list($grouplist) = $db->fetch_row($result);
			
			// Clean the result
			$db->free_result($result);
			
			// Explode and check if the current membergroup is in.
			if (strpos(',', $grouplist))
				$groups = explode($grouplist, ',');
			else
				$groups = array($grouplist);
			
			// Guests have group 0.
			$result = false;
			if ((!$this->isLoggedIn && in_array(0, $groups)) || (in_array($this->details['membergroup'], $groups)))
				$result = true;
			
			// Not allowed.
			$this->permissions[$db->escape_string($permission)] = $result;
			return $result;
		}
	}
	
	public function time($stamp = 0)
	{
		global $modSettings;
		
		$stamp = (int) $stamp;
		if (empty($stamp))
			$stamp = time();
			
		$offset = $this->details['timeoffset'];
		if (empty($offset))
			$offset = 0;
			
		return date($modSettings['time_format'], $stamp);
	}
	
	public function checkPassword($userid, $password)
	{
		global $db;
		
		if (!$this->checkUserExists('user_id'))
		
		$result = $db->query('
			SELECT username
			FROM {db_prefix}users
			WHERE user_id = "' . $db->escape($userid) . '"
			AND password_hash = "' . $db->escape(sha1(sha1($password) . strtolower($username) . $modSettings['m3_rkey'])));
	}
}
