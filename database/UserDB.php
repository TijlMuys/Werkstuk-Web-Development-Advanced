<?php
    include_once "data/User.php";
    include_once "database/DatabaseFactory.php";

    //Class that contains CRUD methods for Users, does not contain any data, only static methods
    class UserDB
    {
        //retrieves connection form DatabaseFactory
        private static function getConnection()
        {
            return DatabaseFactory::getDatabase();
        }
        
        //Retrieve all Users
        public static function getAll()
        {
            //Prepare query string
            $query = "SELECT * FROM USERS";
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query);
             
             
            //initialize empty resultsArray to return to frontend
            $resultsArray = array();
            //iterate over resultset
            for($i = 0; $i < $result->num_rows; $i++)
            {
                //Request current selected row from resultset as array
                $row = $result->fetch_array();
                //Convert row to User object
                $user = UserDB::convertRow($row);
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $user;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        //Retrieve single User
        public static function getById($id)
        {
            //Prepare query string
            $query = "SELECT * FROM USERS WHERE Id = '?'";
            $parameters = array($id);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
            //Check if only one row is retrieved
            if (count($result) > 1) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
            
            //Convert row to user object
            $user = UserDB::convertRow($row);
            
            //Return the object
            return $user;
            
        }
        
        //Insert a User
        public static function insert($user)
        {
            $querystring = "INSERT INTO USERS(Username, Password, Email, IsAdmin) VALUES ('?','?','?','?')";
            $parameters = array($user->Username, $user->Password, $user->Email, $user->IsAdmin);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
         //Update a User
        public static function update($user)
        {
            $querystring = "UPDATE USERS SET Username = '?', Password = '?', Email ='?', IsAdmin = '?' WHERE Id = '?'";
            $parameters = array($user->Username, $user->Password, $user->Email, $user->IsAdmin, $user->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Delete a User
        public static function delete($user)
        {
            $querystring = "DELETE FROM USERS WHERE Id = '?'";
            $parameters = array($user->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Convert function to convert row to User
        protected static function convertRow($row)
        {
            return new User(
                $row['Id'],
                $row['Username'],
                $row['Password'],
                $row['Email'],
                $row['IsAdmin']
            );
        }
    }

?>