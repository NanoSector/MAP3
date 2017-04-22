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

function template_main()
{
        global $urls, $boards, $parser, $modSettings;
        $parser->parse($modSettings['frontpage_text']);
        echo nl2br($parser->getAsHTML());
}