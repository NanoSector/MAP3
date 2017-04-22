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

function template_adminwrapper_start()
{
	global $txt, $context;
	
	echo '
	<div class="cat_bar">
		<div class="centertext">', empty($context['settings_title']) ? $txt['admin_home'] : $context['settings_title'], '</div>
	</div>
	<div class="window">
		<div class="windowcontents">';
}

function template_adminwrapper_end()
{
	echo '
		</div>
	</div>';
}

function template_main()
{
	global $urls, $txt, $module;
	
	foreach ($module->categories as $act => $sub)
	{
		if (!is_array($sub))
		{
			if ($act != 0)
				echo '
			<br class="clear" /><br />';
			
			echo '
			<h3>', $txt[$sub], '</h3>';
		}
		else
		{
			echo '
			<div class="floatleft admin_setting">
				<div class="centertext">
					<a href="', $urls['script'], '?act=admin&amp;area=', $act, '">
						<img src="', $urls['images'], '/admin/', $sub[0], '.png" alt="" /><br />
						', $txt[$sub[1]], '
					</a>
				</div>
			</div>';
		}
	}
	echo '
			<br class="clear" /><br />';
}

function template_collapse_adminicons_start()
{
	global $txt, $urls, $module;
	
	echo '
		<div id="admin_sidebar">
			<div id="admin_icons_toggle" class="cat_bar">
				<div class="centertext">', $txt['coll_settings'], '</div>
			</div>
			<div id="admin_icons" class="window">
				<div class="windowcontents">';
	foreach ($module->categories as $act => $sub)
	{
		if (!is_array($sub))
		{
			if ($act != 0)
				echo '<br />';
			echo '
					<h3>', $txt[$sub], '</h3>';
		}
		else
		{
			echo '
					<a href="', $urls['script'], '?act=admin&amp;area=', $act, '"', $act == $module->current_area ? ' id="admin_selected_icon"' : '', '>
						<img src="', $urls['images'], '/admin/', $sub[0], '_s.png" alt="" class="icon" /> ', $txt[$sub[1]], '
					</a><br />';
		}
	}
	echo '
				</div>
			</div>
		</div>
		<div id="admin_content">';
}

function template_collapse_adminicons_end()
{
	echo '
		</div>
		<br class="clear" />
	</div>';
}

function template_manage_errors()
{
	global $context, $txt, $user;
	
	foreach ($context['errors'] as $error)
	{
		echo '
			<div class="bar">
				', $error['file'], ' on line ', $error['line'], '
			</div>
			<div class="softwindow">
				<div class="padding">
					File: ', $error['file'], '<br />
					Line: ', $error['line'], '<br />
					Type: ', $error['type'], '<br />
					Time: ', $user->time($error['time']), '
					<hr />
					', $error['message'], '
				</div>
			</div>
			<br />';
	}
}
