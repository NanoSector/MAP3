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

if (!defined('MAP_START'))
        die('Unauthorized');
        
// No extends because this is the first class that's loaded!
class map3
{
        private $loadedClasses = array('Load');
        public $currenttheme = array();
        
        // 'module' => 'dir'
        private $modules = array(
                'home' => 'home',
                
                'forum' => 'forum/home',
                'board' => 'forum/viewBoard',
                'topic' => 'forum/viewTopic',
                
                'search' => 'search',
                
                'admin' => 'admin',
                
                'messages' => 'messages',
                
                'profile' => 'profile',
                
                'login' => 'login',
                'register' => 'register',
                'logout' => 'login',
        );
        
        public final function initiateDB()
        {
                global $settings, $classdir, $errors;
                
                // Check if everything is set.
                if (empty($settings['db_host']))
                        $errors->critical_error('Database host is not set.');
                        
                // No user? Should have one.
                if (empty($settings['db_user']))
                        $errors->critical_error('Database user is not set.');
                        
                // Or no database?
                if (empty($settings['db_name']))
                        $errors->critical_error('No database to select');
                
                // Load the Database class.
                $this->loadClassFile('Database');
                
                // And create a new database class.
                $db = new MAPDatabase($settings['db_host'], $settings['db_user'], $settings['db_password'], $settings['db_name'], $settings['db_port'], $settings['db_prefix']);
                
                // Coolio, we're done.
                return $db;
        }
        
        public final function loadClassFile($class = '')
        {
                global $classdir;
                
                // File name contains '.class.php'? Trim it out, we only want the class name.
                $class = str_ireplace('.class.php', '', $class);
                        
                // Class already loaded?
                if (in_array($class, $this->loadedClasses))
                        return true;
                        
                // Then include it.
                require_once($classdir . '/' . $class . '.class.php');
                
                return true;
        }
        
        public final function determineModule()
        {
                global $compdir, $moddir, $currmoddir, $errors;
                
                // Set the $moddir directory.
                $moddir = $compdir . '/modules';
                
                // No action in URL?
                if (empty($_GET['act']))
                        $action = 'home';
                    
                // We must have a valid action then.    
                else
                        $action = (string) $_GET['act'];
                        
                // No action? To hell with them.
                if (empty($action))
                        $errors->critical_error('Unable to determine action: ERR_ACT_NOT_DEFINED');
                        
                // Lets see if the action exists.
                if (!array_key_exists($action, $this->modules))
                        $action = 'home';
                        
                // Current mod dir.
                $currmoddir = $moddir . '/' . $this->modules[$action];
                
                // Do some preloading. This expects a class to be returned from module_setup.
                require_once($moddir . '/' . $this->modules[$action] . '/module_load.php');
                $module = module_setup();
                
                $this->currentaction = $action;
                
                return $module;
        }
        
        public final function reloadSettings()
        {
                global $db, $modSettings;
                
                $result = $db->query('
                        SELECT setting,value
                        FROM {db_prefix}settings');
                
                $modSettings = array();
                while ($setting = $db->fetch_assoc($result))
                        $modSettings[$setting['setting']] = $setting['value'];
                        
                $db->free_result($result);
                
                return true;
        }
        
        public final function reloadTheme($style = 'default')
        {
                global $styledir, $urls, $errors, $context;
                
                if (empty($style))
                	$errors->critical_error(0, 'Failed to reload the theme settings; no template specified');
                
                require_once($styledir . '/' . $style . '/style_main.php');
                $this->currentthemesettings = template_settings();
                
                // Set up some URLs.
                $urls['theme'] = $urls['styles'] . '/' . $style;
                $urls['images'] = $urls['theme'] . '/img';
                $urls['scripts'] = $urls['theme'] . '/js';
                $urls['css'] = $urls['theme'] . '/css';
                
                // Empty out the HTML headers.
                $context['html_headers'] = '';
                
                // Set the default template layers.
                $context['template_layers'] = array(1 => 'header', 2 => 'body');
                
                return true;
        }
        
        public final function reloadLanguagePack($language = 'english')
        {
                global $modSettings;
                
                $this->language = $language;
                
                // Load the index language.
                $this->loadLanguage('main');
        }
        
        public final function loadTemplate($template = '', $stylesheet = array())
        {
                global $styledir, $map3;
                
                require_once($styledir . '/' . $this->currenttheme . '/' . $template . '.template.php');
        }
        
        public final function loadLanguage($langfile = '')
        {
        	global $modSettings, $compdir, $txt;
        	
        	require_once($compdir . '/languages/' . $this->language . '/' . $langfile . '.php');
        }
        
        public final function menu()
        {
                global $urls, $user, $txt;
                
                $menu = array(
                        'home' => array(
                                'title' => $txt['home'],
                                'url' => $urls['script'],
                                'show' => true,
                        ),
                        'forum' => array(
                                'title' => $txt['forum'],
                                'url' => $urls['script'] . '?act=forum',
                                'show' => true,
                        ),
                        'search' => array(
                        	'title' => $txt['search'],
                        	'url' => $urls['script'] . '?act=search',
                        	'show' => true,
                        ),
                        'admin' => array(
                        	'title' => $txt['admin'],
                        	'url' => $urls['script'] . '?act=admin',
                        	'show' => $user->isAllowed('admin_forum')
                        ),
                        'messages' => array(
                        	'title' => $txt['pms'],
                        	'url' => $urls['script'] . '?act=messages',
                        	'show' => $user->isLoggedIn,
                        ),
                        'profile' => array(
                        	'title' => $txt['profile'],
                        	'url' => $urls['script'] . '?act=profile',
                        	'show' => $user->isLoggedIn,
                        ),
                        'login' => array(
                        	'title' => $txt['login'],
                        	'url' => $urls['script'] . '?act=login',
                        	'show' => !$user->isLoggedIn,
                        ),
                        'register' => array(
                        	'title' => $txt['register'],
                        	'url' => $urls['script'] . '?act=register',
                        	'show' => !$user->isLoggedIn,
                        ),
                        'logout' => array(
                        	'title' => $txt['logout'],
                        	'url' => $urls['script'] . '?act=logout',
                        	'show' => $user->isLoggedIn,
                        )
                );
                
                // Determine the active menu button.
                switch ($this->currentaction)
                {
                        case 'board':
                                $menu['forum']['active'] = true;
                                break;
                        
                        case 'topic':
                                $menu['forum']['active'] = true;
                                break;
                        
                        default:
                                if (!empty($menu[$this->currentaction]))
                                        $menu[$this->currentaction]['active'] = true;
                                break;
                        
                }
                return $menu;
        }
        
        public final function redirect($url = '', $timeout = 0, $prefix = true)
        {
                global $urls, $context;
                        
                // Set up the full URL.
                if ($prefix)
			$url = $urls['script'] . $url;
		
		// Get the timeout.
		$timeout = (int) $timeout;
                
                // Redirect if we don't have a timeout.
                if ($timeout === 0)
		{
			// Clean our output buffer with headers etc.
			ob_end_clean();
			
			// The actual redirect.
			header('Location: ' . $url);
                
			// And exit for safety.
			exit;
		}
		else
			$context['html_headers'] .= '
	<script type="text/javascript">
		//<![CDATA[
		
		setTimeout(function ()
		{
			window.location = \'' . $url . '\';
		}, ' . $timeout * 1000 . ');
		
		//]]>
	</script>';
        }
        
        public final function killOutputBuffer($template)
        {
        	global $context;
                
                // Clean our output buffer.
                ob_end_clean();
                
                // Loop through each of our template layers.
                foreach ($context['template_layers'] as $layer)
                {
                        $funcname = 'template_' . $layer . '_start';
                        if (function_exists($funcname))
                                $funcname();
                }
        	
                // Call our template.
		$template();
                
                // And another round in reverse.
                foreach (array_reverse($context['template_layers']) as $layer)
                {
                        $funcname = 'template_' . $layer . '_end';
                        if (function_exists($funcname))
                                $funcname();
                }
		
		exit;
        }
        
        public final function flushOutputBuffer($template)
        {
                global $context;
                
                // Loop through each of our template layers.
                foreach ($context['template_layers'] as $layer)
                {
                        $funcname = 'template_' . $layer . '_start';
                        if (function_exists($funcname))
                                $funcname();
                }
        	
                // Call our template.
		$template();
                
                // And another round, this time in reverse.
                foreach (array_reverse($context['template_layers']) as $layer)
                {
                        $funcname = 'template_' . $layer . '_end';
                        if (function_exists($funcname))
                                $funcname();
                }
		
		ob_end_flush();
        }
        
        /**
         * Cleans the output buffer, erasing all data in it.
         *
         * @return void
         */
        public final function cleanOutputBuffer()
        {
        	ob_end_clean();
        }
        
        public final function detectBrowser()
        {
		global $context, $modSettings;
		
		// Browsers to check for and the result.
		// '' => 'result',
		$context['browser'] = array(
			'is_ie' => preg_match('/MSIE/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/Opera/i', $_SERVER['HTTP_USER_AGENT']),
			'ie_under_warn' => false,
			'ie_ver' => false,
                        
                        'is_ff' => preg_match('/Firefox/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/Opera/i', $_SERVER['HTTP_USER_AGENT']),
                        'ff_ver' => false,
                        
                        // WebKit based browsers.
                        'is_webkit' => preg_match('/WebKit/i', $_SERVER['HTTP_USER_AGENT']),
                        'is_chrome' => preg_match('/Chrome/i', $_SERVER['HTTP_USER_AGENT']),
                        'is_safari' => preg_match('/Safari/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/Chrome/i', $_SERVER['HTTP_USER_AGENT']),
                        'is_safari_mobile' => preg_match('/(iPod|iPad|iPhone)/i', $_SERVER['HTTP_USER_AGENT']),
                        'chrome_ver' => false,
                        'safari_ver' => false,
                        
                        'is_opera' => preg_match('/(Presto|Opera)/i', $_SERVER['HTTP_USER_AGENT']),
                        'opera_ver' => false,
		);
                
		// Do some more mixing and matching for IE.
		if ($context['browser']['is_ie'])
		{
			preg_match('~MSIE (\d*)~', $_SERVER['HTTP_USER_AGENT'], $ie_ver);
		
			// Final matching...
			$context['browser']['ie_under_warn'] = ($ie_ver[1] <= 7);
			$context['browser']['ie_ver'] = $ie_ver[1];
		}
                elseif ($context['browser']['is_ff'])
                {
                        preg_match('~Firefox/(\d*.\d*.\d*)~', $_SERVER['HTTP_USER_AGENT'], $ff_ver);
                        $context['browser']['ff_ver'] = $ff_ver[1];
                }
                elseif ($context['browser']['is_webkit'])
                {
                        if ($context['browser']['is_chrome'])
                        {
                                preg_match('~Chrome/(\d*.\d*.\d*.\d*)~', $_SERVER['HTTP_USER_AGENT'], $chrome_ver);
                                $context['browser']['chrome_ver'] = $chrome_ver[1];
                        }
                        elseif ($context['browser']['is_safari'])
                        {
                                preg_match('~Version/(\d*.\d*)~', $_SERVER['HTTP_USER_AGENT'], $safari_ver);
                                $context['browser']['safari_ver'] = $safari_ver[1];
                        }
                }
                elseif ($context['browser']['is_opera'])
                {
                        preg_match('~Version/(\d*.\d*)~', $_SERVER['HTTP_USER_AGENT'], $opera_ver);
                        
                        // Second attempt.
                        if (empty($opera_ver))
                                preg_match('~Opera (\d*.\d*)~', $_SERVER['HTTP_USER_AGENT'], $opera_ver);
                        
                        $context['browser']['opera_ver'] = $opera_ver[1];
                }
	}
	
	/**
	 * Sends JSON data, then kills the output buffer and exits.
	 *
	 * @param  array $data The data to send.
	 * @return void
	 */
	function json($data = array())
	{
		if (empty($data))
			return;
			
		$this->cleanOutputBuffer();
		
		// Filter empty data out of $data.
		$data = array_filter($data);
		
		// Output the JSON.
		echo json_encode($data);
		
		// Now exit.
		exit;
	}
	
	/**
	 * Runs htmlspecialchars() in our way.
	 * 
	 * @param string $data The data to sanitise.
	 * @param string $mode The mode to use (leave empty for default)
	 */
	function htmlspecialchars($data = '', $mode = ENT_QUOTES)
	{
	
	}
}
