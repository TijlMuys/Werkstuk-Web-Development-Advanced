<?php
    include_once("../data/Blogpost.php");
    include_once("DatabaseFactory.php");

    //Class that contains CRUD methods for Blogposts, does not contain any data, only static methods
    class BlogpostDB
    {
        //retrieves connection form DatabaseFactory
        private static function getConnection()
        {
            return DatabaseFactory::getDatabase();
        }
        
        //Retrieve all Blogposts
        public static function getAll()
        {
            //Prepare query string
            $query = "SELECT * FROM BLOGPOSTS";
            
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
                //Convert row to Blogpost object
                $blogpost = BlogpostDB::convertRow($row);
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $blogpost;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        //Retrieve single Blogposts
        public static function getById($id)
        {
            //Prepare query string
            $query = "SELECT * FROM BLOGPOSTS WHERE Id = '?'";
            $parameters = array($id);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
            //Check if only one row is retrieved
            if (count($result) > 1) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
            
            //Convert row to Blogpost object
            $blogpost = BlogpostDB::convertRow($row);
            
            //Return the object
            return $blogpost;
            
        }
        
        //Insert a Blogpost
        public static function insert($blogpost)
        {
            $querystring = "INSERT INTO BLOGPOSTS(UserId, CategoryId, Title, Content, ImageUrl) VALUES ('?','?','?','?','?')";
            $parameters = array($blogpost->UserId, $blogpost->CategoryId, $blogpost->Title, $blogpost->Content, $blogpost->ImageUrl);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
         //Update a Blogpost
        public static function update($blogpost)
        {
            $querystring = "UPDATE BLOGPOSTS SET UserId = '?', CategoryId = '?', Title ='?', Content = '?', ImageUrl = '?' WHERE Id = '?'";
            $parameters = array($blogpost->UserId, $blogpost->CategoryId, $blogpost->Title, $blogpost->Content, $blogpost->ImageUrl, $blogpost->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Delete a Blogpost
        public static function delete($blogpost)
        {
            $querystring = "DELETE FROM BLOGPOSTS WHERE Id = '?'";
            $parameters = array($blogpost->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        public static function deleteById($blogpostId)
        {
            $querystring = "DELETE FROM BLOGPOSTS WHERE Id = '?'";
            $parameters = array($blogpostId);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Get last inserted id
        public static function lastInsertedId()
        {
            //Since persistent connections are off on the school's server, we can't use the LAST_INSERT_ID() function instead we will get the record with the highest id
            
            //Prepare query string
            $query = "SELECT Id FROM BLOGPOSTS ORDER BY Id DESC";
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query);
             
            //Check if at least one row is retrieved
            if (count($result) == 0) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
    
            //Return isertId
            return $row['Id'];
        }
        
        //Convert function to convert row to Blogpost
        protected static function convertRow($row)
        {
            return new BlogPost(
                $row['Id'],
                $row['UserId'],
                $row['CategoryId'],
                $row['Title'],
                $row['Date'],
                $row['Content'],
                $row['ImageUrl']
            );
        }
    }

?>