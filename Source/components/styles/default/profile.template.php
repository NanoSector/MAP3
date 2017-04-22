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
	global $udata, $txt, $urls;
	
	//echo var_dump($udata);
		
	echo '
		<div class="cat_bar">
       			', sprintf($txt['profile_of'], $udata['displayname']), '
       		</div>
       		<div class="floatleft quarterwidth">
       			<div class="window">
       				<div class="windowcontents">
       					<div class="centertext">
       						<h3>', $udata['displayname'], '</h3>
       					</div>
       					<div class="centerdiv halfwidth">';
       					
       	if (!empty($udata['avatar']))
       		echo '
	       					<img src="', $udata['avatar'], '" class="profileavatar" alt="" /><br />';
	       				
	echo '
						<img src="', $urls['images'], '/message.png" valign="absbottom" /> <a href="', $urls['script'], '?act=messages;send=', $udata['user_id'], '">', $txt['send_pm'], '</a>
					</div>
       				</div>
       			</div>
       		</div>
      		<div class="floatleft tquarterwidth">
       			<div class="window">
       			
       			</div>
       		</div>
       		<br class="clear" />';
}
