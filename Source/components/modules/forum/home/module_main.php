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
                global $current_template, $map3, $boards, $user, $db;
                
                // Load the Boards class.
                $map3->loadClassFile('Boards');
                $boards = new forumBoards;
                
                if (isset($_GET['saveorder']) && !empty($_POST['category_order']) && $user->isLoggedIn)
                {
                	$sql = 'UPDATE {db_prefix}users
        			SET board_order = "' . $db->escape_string($_POST['category_order']) . '"
        			WHERE user_id = ' . $db->escape_string($user->details['user_id']);
                	$db->query($sql);
                	$map3->json(array('success' => true, 'sql' => $sql));
                }
                
                // Then grab the boards.
                $boards->grabBoards();
                
                // And last but not least lets boot up the template.
                $map3->loadTemplate('boardIndex');
        }
        
        
}
