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

function template_fatalError()
{
	global $errors;
	echo '
	<div class="centerdiv loginscreen">
       		<div class="cat_bar">
       			An error has occured!
       		</div>
       		<div class="window">
       			<div class="windowcontents">
       				', $errors->lastThrownError, '
       			</div>
       		</div>
       		<div class="centertext"><a href="javascript:history.go(-1)">Go back</a></div>
       	</div>';
}
