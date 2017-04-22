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

class messageIndexModule
{
        public $loaded = false;
        
        public function prepare()
        {
                global $map3, $context, $boards;
                
                // Get the Boards class started.
                $map3->loadClassFile('Boards');
                $boards = new forumBoards;
                
                // Does this board exist?
                $board = (int) $_GET['boardid'];
                
                if (empty($board))
                        die('Board does not exist.');
                        
                $boards->grabBoardData($board);
                        
                // Can we view this board?
                if (!$boards->checkBoardPermissions($board))
                	die('You do not have access to this board.');
                
                $context['topics'] = $boards->grabBoardMessages($board);
               	$context['bid'] = $board;
                
                $map3->loadTemplate('viewBoard');
        }
}
