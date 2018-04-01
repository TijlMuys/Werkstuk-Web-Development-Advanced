<?php
    include_once "data/Blogpost.php";
    include_once "database/DatabaseFactory.php";

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
        
        //Insert a Blogpost
        public static function insert($blogpost)
        {
            $querystring = "INSERT INTO BLOGPOSTS(UserId, CategoryId, Title, Content, ImageUrl) VALUES ('?','?','?','?','?')";
            $parameters = array($blogpost->UserId, $blogpost->CategoryId, $blogpost->Title, $blogpost->Content, $blogpost->ImageUrl);
            return self::getConnection()->executeQuery($querystring, $parameters);
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