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
 
 class MAPDatabase
 {
        // The connection object.
        private $conn = '';
        private $prefix = '';
        
        public function __construct($host, $user, $password, $database, $port, $prefix)
        {
                // Simple.
                $mysqli = @new MySQLi($host, $user, $password, $database, $port);
                
                if ($mysqli->connect_error)
                {
                        die('An error has occured while connecting to the MySQL server: (' . $mysqli->connect_errno . ') '
                        . $mysqli->connect_error);
                }
                
                $this->conn = $mysqli;
                $this->prefix = $prefix;
        }
        
        public function query($query)
        {
                // Okay... Empty strings, we don't do it for that.
                if (empty($query))
                        return false;
                
                $tquery = str_replace('{db_prefix}', $this->prefix, $query);
                $result = $this->conn->query($tquery);
                
                if ($result == false)
                        die('Query Error (' . $tquery . '): ' . $this->conn->error);
                        
                return $result;
                
        }
        
        public function fetch_assoc($result)
        {
                if (!is_object($result) || ($result instanceof MySQLi_result) == false)
                        return false;
                
                return $result->fetch_assoc();
        }
        
        public function fetch_row($result)
        {
                if (!is_object($result) || ($result instanceof MySQLi_result) == false)
                        return false;
                
                return $result->fetch_row();
        }
        
        public function free_result($result)
        {
                if (!is_object($result) || ($result instanceof MySQLi_result) == false)
                        return false;
                
                $result->free();
                
                return true;
        }
        
        public function num_rows($result)
        {
                if (!is_object($result) || ($result instanceof MySQLi_result) == false)
                        return false;
                
                return $result->num_rows;
        }
        
        public function escape_string($string)
        {
        	return $this->conn->real_escape_string($string);
        }
 }
