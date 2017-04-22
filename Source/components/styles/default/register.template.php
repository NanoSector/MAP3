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
			', $txt['register'], '
		</div>
		<div class="window">
			<div class="bmargin">
				<form action="', $urls['script'], '?act=register&amp;do" method="post">
					<div class="ahalfwidth floatleft"><strong><label for="m3_username">', $txt['username'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="text" name="m3_username" id="m3_username" /></div>
					<br class="clear"/><br />
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_password">', $txt['password'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="password" name="m3_password" id="m3_password" /></div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_cpassword">', $txt['password'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="password" name="m3_cpassword" id="m3_cpassword" /> (', $txt['confirm'], ')</div>
					<br class="clear"/><br />
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_email">', $txt['email'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="text" name="m3_email" id="m3_email" /></div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_email_me">', $txt['email_me'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="checkbox" name="m3_email_me" id="m3_email_me" /></div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_email_news">', $txt['email_news'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="checkbox" name="m3_email_news" id="m3_email_news" /></div>
					<br class="clear"/>
					
					<hr />
					
					<strong>', $txt['optional_fields'], '</strong><br />
					', $txt['optional_fields_desc'], '<br /><br />
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_website">', $txt['website'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="text" name="m3_website" id="m3_website" /></div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_website_title">', $txt['website_title'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="text" name="m3_website_title" id="m3_website_title" /></div>
					<br class="clear"/><br />
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_birthday_year">', $txt['birthdate'], ':</label></strong></div>
					<div class="ahalfwidth floatright">
						<input type="text" name="m3_birthday_year" id="m3_birthday_year" maxlength="4" size="4" />
						<input type="text" name="m3_birthday_month" id="m3_birthday_month" maxlength="2" size="2" />
						<input type="text" name="m3_birthday_day" id="m3_birthday_day" maxlength="2" size="2" />
						(', $txt['birthdate_format'], ')
					</div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_gender">', $txt['gender'], ':</label></strong></div>
					<div class="ahalfwidth floatright">
						<select name="m3_gender" id="m3_gender">
							<option value="0" selected="selected">', $txt['na'], '</option>
							<option value="1">', $txt['male'], '</option>
							<option value="2">', $txt['female'], '</option>
						</select>
					</div>
					<br class="clear"/>
					
					<div class="ahalfwidth floatleft"><strong><label for="m3_location">', $txt['location'], ':</label></strong></div>
					<div class="ahalfwidth floatright"><input type="text" name="m3_location" id="m3_location" /></div>
					
					<hr />
					
					<div class="ahalfwidth floatright"><input type="submit" value="', $txt['submit'], '" /></div>
					<br class="clear" />
				</form>
			</div>
		</div>
	</div>';
}

function template_success()
{
	global $txt;
	
	echo '
	<div class="centerdiv loginscreen">
		<div class="cat_bar">
			', $txt['register_success'], '
		</div>
		<div class="window">
			', $txt['register_success_desc_normal'], '
		</div>
	</div>';
}
