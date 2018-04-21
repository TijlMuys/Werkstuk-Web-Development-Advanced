<?php
    //include
    if (file_exists("../database/Database.php")) 
    {
        include_once("../database/Database.php");
    }
    else 
    {
        include_once("database/Database.php");
    }

    //Keeps track of the database connection throughout the application, froms a single entrypoint for the database connection
    class DatabaseFactory 
    {
        //singleton
        private static $connection;
        
        //retrieve instance of databaseconnection
        public static function getDatabase(){
            if (self::$connection == null) {
                //Retrieve variables from global scope
                global $DBurl, $DBuser, $DBpass, $DBdatabase;
                //If connection no longer exists, make a new one
                self::$connection = new Database($DBurl, $DBuser, $DBpass, $DBdatabase);
            }
            return self::$connection;
        }
        
    }
/*
Derycke, M. PHP & MySql tutorial, https://ehb.instructure.com/courses/690/pages/php-and-mysql-tutorial?module_item_id=20891.
Geraadpleegd op 2 april 2018
*/    

?>