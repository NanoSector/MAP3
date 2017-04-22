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

class forumBoards
{
        public $categories = array();
        public $boards = array();
        
        // Grab the boards and sort them into their own categories.
        public final function grabBoards()
        {
                global $db;
                
                $result = $db->query('
                        SELECT cat_id, name
                        FROM {db_prefix}categories
                        ORDER BY cat_order ASC');
                
                $categories = array();
                while ($row = $db->fetch_assoc($result))
                {
                        $categories[$row['cat_id']] = array(
                        	'id' => $row['cat_id'],
                                'name' => $row['name'],
                                'boards' => array()
                        );
                }
                
                $db->free_result($result);
                
                // And for the boards.
                $result = $db->query('
                        SELECT board_id, name, description, num_posts, num_topics, category_id, board_order, permissions, last_post
                        FROM {db_prefix}boards
                        ORDER BY board_order ASC');
                
                $boards = array();
                while ($row = $db->fetch_assoc($result))
                {
                        if (!isset($categories[$row['category_id']]))
                                continue;
                        
                        $this->boards[$row['board_id']] = $row;
                        
                        // If we don't have permissions to view this board, skip it.
                        if (!$this->checkBoardPermissions($row['board_id']))
                        {
                        	unset($this->boards[$row['board_id']]);
                        	continue;
                        }
			
			// If there is a last post, grab it.
			$data = $this->grabBoardLastPost($row['last_post'], $row['board_id']);
                        
                        $categories[$row['category_id']]['boards'][$row['board_order']] = $row;
			$categories[$row['category_id']]['boards'][$row['board_order']]['last_post'] = $data;
                }
                
                $db->free_result($result);
                
                $this->categories = array_filter($categories);
                
                return true;
        }
        
        public final function grabBoardMessages($boardid)
        {
                global $db;
                
                // Check if the board exists.
                $result = $db->query('
                        SELECT board_id, name, description
                        FROM {db_prefix}boards
                        WHERE board_id = ' . (int) $boardid . '
                        LIMIT 1');
                
                // No board? No data.
                if ($db->num_rows($result) == 0)
                        return false;
                
                // Fetch it, it's only one row.
                $bdata = $db->fetch_assoc($result);
                
                // Then grab topics and messages.
                $result = $db->query('
                        SELECT
                                subject, id_msg, id_topic, id_board, time_posted,
                                member, member_name, member_email, time_updated,
                                updated_by, body, is_topic, views
                        FROM {db_prefix}messages
                        WHERE id_board = ' . (int) $boardid);
                
                $topics = array();
                while ($row = $db->fetch_assoc($result))
                {
                        if ($row['is_topic'] == 1)
                        {
                                $topics[$row['id_topic']] = array_merge($row, array('posts' => array()));
                                continue;
                        }
                        
                        $topics[$row['id_topic']]['posts'][] = $row;
                }
                
                // Then return the topics.
                return $topics;
        }
        
        public function grabBoardData($boardid)
        {
                global $db;
                
                // Grab the data.
                $result = $db->query('
                        SELECT board_id, name, description, num_posts, num_topics, permissions, last_post
                        FROM {db_prefix}boards
                        WHERE board_id = ' . (int) $boardid . '
                        LIMIT 1');
                
                // No board? No data.
                if ($db->num_rows($result) == 0)
                        return false;
                
                // Fetch it, it's only one row.
                $bdata = $db->fetch_assoc($result);
                
                $this->boards[$boardid] = $bdata;
                
                return true;
        }
        
        public function checkBoardPermissions($boardid)
        {
        	global $user;
        	// Does the board exist?
        	if (!array_key_exists($boardid, $this->boards))
        		return false;
        	        		
        	// Are we an admin?
        	if ($user->details['membergroup'] == 1)
        		return true;
        		
        	if (empty($this->boards[$boardid]['permissions']))
        		return false;
        		
        	// Explode the permissions. Into pieces.
        	$permissions = explode(';', $this->boards[$boardid]['permissions']);
        	
        	// Loop through each.
        	$hasAccess = false;
        	if (in_array($user->details['membergroup'], $permissions))
        		$hasAccess = true;
        	
        	// Return our result.
        	return $hasAccess;
        }
	
	public function grabBoardLastPost($postid, $boardid)
	{
		global $db, $user;
		
		if (!array_key_exists($boardid, $this->boards) || empty($postid))
			return false;
		
		$result = $db->query('
			SELECT subject, member, id_topic, time_posted
			FROM {db_prefix}messages
			WHERE id_msg = ' . $db->escape_string($postid) . '
			AND id_board = ' . $db->escape_string($boardid));
		
		// Does not exist? ._.
		if ($db->num_rows($result) == 0)
			return false;
		
		list ($subject, $memberid, $topic, $tposted) = $db->fetch_row($result);
		
		if (!$user->checkUserExists('user_id', $memberid))
			return false;
		
		$memberData = $user->grabUserData($memberid);
		
		// Set up the array.
		$data = array(
			'user' => $memberData,
			'subject' => $subject,
			'topic' => $topic,
			'message' => $postid,
			'time' => $tposted
		);
		
		return $data;
	}
}
