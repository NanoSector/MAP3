<?php

function template_admin_settings()
{
	global $context, $txt, $modSettings, $urls;
	
	// Show a message if we have saved.
	echo '
	<div class="success_box" id="profile_success" style="display:none">
		', $txt['settings_saved'], '
	</div>';
	
	// Any errors, doc?
	echo '
	<div class="errorbox" style="display:none" id="errors_occured_box">
		<strong>', $txt['errors_occured_saving'], '</strong>
		<ul class="reset" id="errors_occured">
		</ul>
	</div>';
		
	// Now enter the configuration stuff.
	if (!empty($context['settings']['config_vars']))
	{
		// !!! Move to separate JS file.
		echo '
			<script type="text/javascript" src="', $urls['scripts'], '/admin.js"></script>
			<form action="', str_replace('&', '&amp;', $context['settings']['post_url']), '" method="post" id="settings_form">
				<table style="width:100%">';
		
		foreach ($context['settings']['config_vars'] as $post => $setting)
		{
			// Show the label.
			echo '
					<tr>';
					
			// Show a label?
			if (is_array($setting) && !empty($setting[0]) && !in_array($setting[0], array('link')))
				echo '
						<td style="width: 35%">
							<strong><label for="', $post, '">', $txt[$setting[1]], '</label></strong>
							', !empty($txt[$setting[1] . '_desc']) ? '<br />' . $txt[$setting[1] . '_desc'] : '', '
						</td>
						<td style="width: 65%">';
			else
				echo '
						<td></td>
						<td>';
				
			// Figure out the setting type.
			$switch = !is_array($setting) ? '' : $setting[0];
			switch ($switch)
			{
				// A checkbox?
				case 'check':
					echo '
							<input type="checkbox" id="', $post, '" name="', $post, '"', !empty($modSettings[$post]) ? ' checked="checked"' : '', ' />';
					break;
				case 'text':
					echo '
							<input type="text" id="', $post, '" name="', $post, '" value="', $modSettings[$post], '"', (!empty($setting[2]) ? ' size="' . $setting[2] . '"' : ''), (!empty($setting[3]) ? ' maxlength="' . $setting[3] . '"' : ''), ' />';
					break;
				case 'textarea':
					echo '
							<textarea id="', $post, '" name="', $post, '" style="', !empty($setting[2]) ? ' height: ' . $setting[2] . ';' : 'height:250px;', !empty($setting[3]) ? ' width:' . $setting[3] . ';' : 'width:100%;', '">', $modSettings[$post], '</textarea>';
					break;
				case 'select':
					echo '
							<select name="', $post, '" id="', $post, '">';
							
					foreach ($setting[2] as $option => $text)
					{
						echo '
								<option value="', $option, '"', $modSettings[$post] == $option ? ' selected="selected"' : '', '>', $text, '</option>';
					}
					
					echo '
							</select>';
					break;
				case 'link':
					echo '
							<a href="', $setting[1], '">', $setting[2], '</a>';
					break;
				default:
					echo $setting;
					break;
			}
			
			echo '
						</td>
					</tr>';
		}
				

		
		echo '
					<td></td><td><input type="submit" value="', $txt['submit'], '" class="button_submit" /></td>
				</table>';
				
		// Any hidden settings.
		if (!empty($context['settings']['hidden_config_vars']))
		{
			foreach ($context['settings']['hidden_config_vars'] as $hcv)
			{
				echo '
				<input type="hidden" name="', $hcv[0], '" value="', $hcv[1], '" />';
			}
		}
		
		echo '
			</form>';
	}
}
