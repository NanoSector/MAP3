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

class permissions
{
	private $permissions = array();
	
	public function __construct()
	{
		global $db;
		
		// Load all the permissions. ALL OF THEM DAMMIT!
		$result = $db->query('
			SELECT id_permission, id_membergroup, permission_name
			FROM {db_prefix}permissions');
			
		while ($row = $db->fetch_assoc($result))
		{
			// If the membergroup ID doesn't exist yet, create it.
			if (!isset($this->permissions[$row['id_membergroup']]))
				$this->permissions[$row['id_membergroup']] = array();
				
			// Insert the permission.
			$this->permissions[$row['id_membergroup']][$row['id_permission']] = $row['permission_name'];
		}
		
		$db->free_result($result);
		
		// All set.
	}
	
	public function check($permissionname = '')
	{
		global $user;
		// No name? Screw you.
		if (empty($permissionname))
			return false;
			
		// Are you a guest?
		if (!$user->isLoggedIn)
			$permissiongroup = 0;
		
		// No...
		else
			$permissiongroup = $user->details['membergroup'];
			
		// Now do we have permission to do this? If we are an admin we do.
		if ($permissiongroup == 1)
			return true;
			
		// Well we have a normal user going on. Do the normal check.
		if (in_array($permissionname, $this->permissions[$permissiongroup]))
			return true;
		else
			return false;
	}
}
