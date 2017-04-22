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

class Errors
{
	public final function fatal($message)
	{
		global $map3;
		
		$map3->loadTemplate('errors');
		
		$map3->killOutputBuffer('template_fatalError');
	}
	
	public final function log($message, $file = '', $line = 0, $type = 'warning', $time = 0)
	{
		global $db, $user;
		
		if (empty($time))
			$time = time();
		
		$message = $db->escape_string($message);
		$type = $db->escape_string($type);
		$file = $db->escape_string($file);
		$line = (int) $line;
		$time = (int) $time;
		
		// Log it. NAO.
		$db->query('
			INSERT INTO {db_prefix}error_log
				(
					type,
					file,
					line,
					time,
					user,
					message
				)
				VALUES
				(
					"' . $type . '",
					"' . $file . '",
					' . $line . ',
					' . $time . ',
					' . (!empty($user->details['id']) ? $user->details['id'] : 0) . ',
					"' . $message . '"
				)');
		
		return true;
	}
	
	public final function handle_error($errno, $errstr, $errfile, $errline)
        {
		global $modSettings;
		
		// Figure out the type in human readable characters.
		$type = '';
                switch ($errno)
                {
                        case 1:
				$type = 'error';
                                break;

                        case 2:
				$type = 'warning';
                                break;

                        case 3:
				$type = 'notice';
                                break;

                        default:
				$type = 'error';
                                break;
                }
		
		// Just log it.
		$this->log($errstr, $errfile, $errline, $type);
		
		// If we have to deal with a fatal error here, override any displaying of errors.
		if ($type == 'error')
			$this->critical_error($errno, $errstr, $errfile, $errline);
		
		// If we are told not to display errors, we have handled the error.
		if (empty($modSettings['display_errors']))
			return true;
		
		// Else we'll execute PHPs handler to show errors.
		return false;
        }
        
        private final function critical_error($errno = '', $errstr = '', $errfile = '', $errline = '')
        {
                ob_end_clean();
                echo '<!DOCTYPE html>
<html>
<head>
        <title>An error has occured!</title>
        <style type="text/css">
body, html
{
        padding: 0;
        margin: 0;
        background-color: #EEE;
        font-family:"Helvetica Neue",Helvetica,Arial,sans-serif;
        font-size: 14px;
}

.wrapperbox
{
        margin: 0;
        margin-left: auto;
        margin-right: auto;
        
        border: 1px solid #CCC;
        border-radius: 5px;
        
        margin-top: 50px;
        
        width: 40%;
        
        padding: 5px;
        
        background-color: #FFF;
}

.wrapperbox h2
{
        margin: 0;
        padding: 0;
        font-size: 24px;
}

.wrapperbox textarea
{
        width: 99%;
        height: 92px;
}

.floatright
{
        float: right;
}

.floatleft
{
        float: left;
}

.clear
{
        clear: both;
}

.logo
{
        font-size: 18px;
}
        </style>
</head>
<body>
        <div class="wrapperbox">
                <div class="floatleft"><h2>An error has occured!</h2></div>
                <span class="floatright logo">MAP3</span>
                <br class="clear" /><br />
                A critical error has occured in the core while executing. MAP3 has been closed to prevent any damage being done to your data.
                Additional details for this error are below:<br />
                
                <textarea readonly="readonly">Error report generated on ', time(), ' (UNIX timestamp)
Errors could have been logged before this error screen. Please check your error log in the Administration Panel.

A critical error has occured.
The application reported following details:
', !empty($errno) ? 'Error no.: ' . $errno . "\n" : '', !empty($errstr) ? 'Error string: ' . $errstr . "\n" : '', !empty($errfile) ? 'Error file: ' . $errfile . "\n" : '', !empty($errline) ? 'On line: ' . $errline . "\n" : '', '</textarea>
        </div>
</body>
</html>';
                exit;
        }
}
