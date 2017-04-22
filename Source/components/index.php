<?php

// This file handles a redirection for, err, intruders.
if (file_exists(dirname(dirname(__FILE__)) . '/Settings.php'))
{
        require(dirname(dirname(__FILE__)) . '/Settings.php');
        header('Location: ' . $urls['script']);
}
else
        exit('Please go back to the site.');
