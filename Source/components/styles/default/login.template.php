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
        global $urls, $txt;
        echo '
       	<div class="centerdiv loginscreen">
       		<div class="cat_bar">
       			', $txt['login'], '
       		</div>
       		<div class="window">
       			<div class="windowcontents">
       				<div class="centertext"><strong>Login</strong></div>
       				<form action="', $urls['script'], '?act=login&amp;do" method="post">
       					<table class="fullwidth">
       						<tr>
       							<td class="halfwidth righttext"><strong>', $txt['username'], ':</strong></td>
       							<td class="halfwidth"><input type="text" name="m3_username" /></td>
       						</tr>
       						<tr>
       							<td class="halfwidth righttext"><strong>', $txt['password'], ':</strong></td>
       							<td class="halfwidth"><input type="password" name="m3_password" /></td>
       						</tr>
       					</table>
       					<div class="centertext">
       						<input type="submit" value="Submit" />
       					</div>
       				</form>
       			</div>
       		</div>
       	</div>';
}

function template_login_success()
{
	global $urls, $txt;
	echo '
	<div class="halfwidth centerdiv">
		<div class="cat_bar">
			', $txt['login_success'], '
		</div>
		<div class="window">
			<div class="windowcontents">
				', sprintf($txt['login_success_desc'], $urls['script'] . '?act=forum'), '
			</div>
		</div>
	</div>';
}

function template_logout_success()
{
	global $urls, $txt;
	echo '
	<div class="halfwidth centerdiv">
		<div class="cat_bar">
			', $txt['logout_success'], '
		</div>
		<div class="window">
			<div class="windowcontents">
				', sprintf($txt['logout_success_desc'], $urls['script'] . '?act=forum'), '
			</div>
		</div>
	</div>';
}
