<?php

// Use the upper index.php, if it exists.
if (file_exists(dirname(dirname(__FILE__)) . '/index.php'))
        require_once(dirname(dirname(__FILE__)) . '/index.php');
else
        exit('Please go back to the site.');