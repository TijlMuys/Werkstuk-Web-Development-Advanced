<?php
    include_once 'database/Database.php';

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
        

?>