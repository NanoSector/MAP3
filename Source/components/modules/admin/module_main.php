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
        public $current_area = '';
        
        public $categories = array(
                'frontpage_settings',
                'frontpage' => array('news', 'frontpage', 'manage_frontpage'),
                
                'forum_settings',
                'messagesettings' => array('messages', 'messages', 'manage_message_settings'),
                'manageboards' => array('boards', 'boards', 'manage_boards'),
                'membersettings' => array('members', 'members', 'manage_members'),
                
                'features',
                'generalsettings' => array('gensettings', 'general_settings', 'manage_general_settings'),
                'layoutsettings' => array('layout', 'layout_settings', 'manage_layout_settings'),
                'security' => array('security', 'security', 'manage_security'),
                'news' => array('news', 'acp_news', 'manage_news'),
                'emails' => array('email', 'email', 'manage_emails'),
                'development' => array('dev', 'development', 'manage_development_settings'),
                
                'extra_features',
                'packages' => array('pkgmanager', 'package_manager', 'manage_packages'),
                'fbintegration' => array('fb', 'facebook_integration', 'manage_fb'),
                'twintegration' => array('tw', 'twitter_integration', 'manage_twitter'),
                'errlog' => array('errors', 'error_log', 'manage_errors'),
                'updates' => array('updates', 'updates', 'manage_updates'),
        );
        
        public function prepare()
        {
                global $current_template, $map3, $boards, $context, $txt;
                
                // Lets boot up the template.
                $map3->loadTemplate('admin/home');
                $map3->loadLanguage('admin');
                
                // Try to grab the current area.
                $area = '';
                if (!empty($_GET['area']) && array_key_exists((string) $_GET['area'], $this->categories) && is_array($this->categories[(string) $_GET['area']]))
                        $area = (string) $_GET['area'];
                        
                // Grab the function.
                if (!empty($area))
                {
                        $this->current_area = $area;
                        $context['template_layers'][] = 'collapse_adminicons';
                        $context['settings_title'] = $txt[$this->categories[$area][1]];
                        call_user_func(array($this, $this->categories[$area][2]));
                }
                
                $context['template_layers'][] = 'adminwrapper';
        }
        
        private function manage_frontpage()
        {
                global $context, $map3, $urls, $modSettings, $txt;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'post_var', 'text', 'value'/'check')
                        'config_vars' => array(
                                'frontpage_enabled' => array('check', 'frontpage_enabled'),
                                'frontpage_text' => array('textarea', 'frontpage_text')
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=frontpage&save'
                );
                
                if (isset($_GET['save']))
                {
                	$errors = array();
                	if (!empty($_POST['frontpage_enabled']) && empty($_POST['frontpage_text']))
                		$errors[] = $txt['frontpage_enabled_no_message'];
                	
                	if (empty($errors))
	                        $this->save($context['settings']['config_vars']);
	                        
                        $map3->json(array('success' => empty($errors), 'errors' => $errors));
                }
                
                // !!! Fix up this.
                /*$context['html_headers'] .= '
                <!-- SCEditor -->
                <script type="text/javascript" src="' . $urls['components'] . '/sceditor/jquery.sceditor.min.js"></script>
                <link rel="stylesheet" href="' . $urls['components'] . '/sceditor/themes/office-toolbar.min.css" type="text/css" media="all" />
                <script type="text/javascript">
                        //<![CDATA[
                        
                        $(document).ready(function() {
				$("textarea").sceditor({
                                        plugin: "bbcode",
					style: "' . $urls['components'] . '/sceditor/jquery.sceditor.default.min.css",
					toolbar: "bold,italic,underline|font,size,color,removeformat|image,link,unlink,emoticon|source",
					resizeEnabled: false,
                                        emoticonsRoot: "' . $urls['components'] . '/smilies/' . $modSettings['smiley_pack'] . '/",
                                        emoticons:
                                        {
                                                // emoticons to be included in the dropdown
                                                dropdown: {
                                                        ":)": "smile.png",
                                                        ":(": "sad.png",
                                                        ":D": "grin.png",
                                                        ";)": "wink.png",
                                                        ">:D": "twisted.png",
                                                        ":S": "confused.png",
                                                        ":P": "lol.png"
                                                },
                                                // emoticons to be included in the more section
                                                more: {
                                                        ":3": "kitty.png",
                                                        "::)": "rolleyes.png"
                                                },
                                                // emoticons that are not shwon in the dropdown but will be converted ATY
                                                hidden: {
                                                }
                                        }
				});
			});
			$(document).ready(function() {
				$("textarea").sceditorBBCodePlugin({
					style: "' . $urls['components'] . '/sceditor/jquery.sceditor.default.min.css"
				});
			});
                        //]]>
                </script>';*/
        }
        private function manage_message_settings()
        {
                global $context, $map3, $urls, $modSettings;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'post_var', 'text', 'value'/'check')
                        'config_vars' => array(
                                /*'name' => array('text', 'name'),
                                'test' => array('check', 'test')*/
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=messagesettings&save'
                );
                
                if (isset($_GET['save']))
                {
                        $this->save($context['settings']['config_vars']);
                        $map3->json(array('success' => true, 'errors' => array()));
                }
        }
        
        private function manage_news()
        {
                global $context, $map3, $urls, $modSettings, $txt;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'text', 'value'/'check')
                        'config_vars' => array(
                                'news_text' => $txt['news_desc'],
                                'news' => array('text', 'news_edit', 80)
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=news&save'
                );
                
                if (isset($_GET['save']))
                {
                        $this->save($context['settings']['config_vars']);
                        $map3->json(array('success' => true, 'errors' => array()));
                }
        }
        
        private function manage_general_settings()
        {
		global $context, $map3, $urls, $modSettings, $txt;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'text', 'value'/'check')
                        'config_vars' => array(
                                'forum_name' => array('text', 'forum_name'),
                                'time_format' => array('text', 'time_format'),
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=generalsettings&save'
                );
                
                if (isset($_GET['save']))
                {
                        $this->save($context['settings']['config_vars']);
                        $map3->json(array('success' => true, 'errors' => array()));
                }
        }
        
        private function manage_layout_settings()
        {
		global $context, $map3, $urls, $modSettings, $txt;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'text', 'value'/'check')
                        'config_vars' => array(
                                'ie_warning' => array('check', 'ie_warn'),
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=layoutsettings&save'
                );
                
                if (isset($_GET['save']))
                {
                        $this->save($context['settings']['config_vars']);
                        $map3->json(array('success' => true, 'errors' => array()));
                }
	}
        
        private function manage_development_settings()
        {
                global $context, $map3, $urls, $modSettings, $txt;
                
                // Load the settings template.
                $map3->loadTemplate('admin/settings');
                $context['current_template'] = 'template_admin_settings';
                
                $context['settings'] = array(
                        // 'post/modsettings_var' => 'type', 'text', 'value'/'check')
                        'config_vars' => array(
                                'display_errors' => array('check', 'display_errors'),
                        ),
                        'post_url' => $urls['script'] . '?act=admin&area=development&save'
                );
                
                if (isset($_GET['save']))
                {
                        $this->save($context['settings']['config_vars']);
                        $map3->json(array('success' => true, 'errors' => array()));
                }
        }
        
        private function manage_errors()
        {
                global $context, $db, $errors;
                
                $result = $db->query('
                        SELECT id, type, file, line, time, user, message
                        FROM {db_prefix}error_log');
                
                $context['errors'] = array();
                while ($row = $db->fetch_assoc($result))
                {
                        $context['errors'][] = $row;
                }
                
                //echo var_dump($context['errors']);
                
                $db->free_result($result);
                
                $context['current_template'] = 'template_manage_errors';
        }
        
        // The only purpose this function has is converting setting data
        // for the settings template to data for updateSettings();
        private function save($settings)
        {
                global $db, $modSettings;
                
                // No $_POST contents? ._.
                if (empty($_POST))
                        return;
                
                $update_settings = array();
                foreach ($settings as $post => $p)
                {
                        if (!is_array($p))
                                continue;
                        
                        switch ($p[0])
                        {
                                case 'check':
                                        $update_settings[$post] = !empty($_POST[$post]);
                                        break;
                                default:
                                        if (isset($_POST[$post]) && $_POST[$post] != $modSettings[$post])
                                                $update_settings[$post] = $_POST[$post];
                                        break;
                        }
                }
                
                $this->updateSettings($update_settings);
        }
        
        public function updateSettings($settings)
        {
                global $db, $modSettings, $map3;
                
                // No settings? We're done.
                if (!is_array($settings))
                        return true;
                
                // Else loop through each.
                $insert_settings = array();
                foreach ($settings as $key => $value)
                {
                        // Does it exist yet? No? Create it.
                        if (!isset($modSettings[$key]))
                                $insert_settings[$key] = $value;
                                
                        // Else we can just update it.
                        else
                                $db->query('
                                        UPDATE {db_prefix}settings
                                        SET value="' . (string) $db->escape_string($value) . '"
                                        WHERE setting="' . (string) $db->escape_string($key) . '"');
                }
                
                // Any settings to insert?
                if (!empty($insert_settings))
                {
                        $pairs = array();
                        foreach ($insert_settings as $key => $value)
                        {
                                $pairs[] = '("' . $db->escape_string($key) . '", "' . $db->escape_string($value) . '")';
                        }
                        
                        $db->query('
                                INSERT INTO {db_prefix}settings (setting, value)
                                VALUES ' . implode(', ', $pairs) . ';');
                }
                
                // Reload the settings.
                $map3->reloadSettings();
        }
}
