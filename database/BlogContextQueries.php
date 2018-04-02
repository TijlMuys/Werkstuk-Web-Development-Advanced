<?php
    include_once "../data/BlogContext.php";
    include_once "CommentDB.php";
    include_once "DatabaseFactory.php";

    //This class contains queries to retrieve Blogcontext-objects from the database, since Blogcontext does not correspond with a Database Table, direct DML statements are impossible
    class BlogContextQueries
    {
        //retrieves connection form DatabaseFactory
        private static function getConnection()
        {
            return DatabaseFactory::getDatabase();
        }
        
        //Retrieve all BlogContexts
        public static function getAll()
        {
            //Prepare outer query string
            $query = "SELECT b.Id, b.UserId, u.Username, b.CategoryId, c.CategoryName, b.Title, b.Date, b.Content, b.ImageUrl
            FROM BLOGPOSTS b 
            JOIN USERS u ON(b.UserId = u.Id)
            JOIN CATEGORIES c ON(b.CategoryId = c.Id);";
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query);
             
             
            //initialize empty resultsArray to return to frontend
            $resultsArray = array();
            //iterate over outer resultset
            for($i = 0; $i < $result->num_rows; $i++)
            {
                //Request current selected row from resultset as array
                $row = $result->fetch_array();
                //Convert row to Blogpost object
                $blogcontext = BlogContextQueries::convertOuterRow($row);
                
                    //Get correspônding comments of (partial) blogcontext
                        //retreive comments that match current blogpost
                        $innerqueryresult = CommentDB::getByBlogpostId($blogcontext->Id);
                        //add innerresults to our outer blogcontext
                        $blogcontext->Comments =  $innerqueryresult;
                
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $blogcontext;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        //Retrieve single Blogcontext by BlogpostId
        public static function getById($id)
        {
            //Prepare query string
            $query = "SELECT b.Id, b.UserId, u.Username, b.CategoryId, c.CategoryName, b.Title, b.Date, b.Content, b.ImageUrl
                      FROM BLOGPOSTS b 
                      JOIN USERS u ON(b.UserId = u.Id)
                      JOIN CATEGORIES c ON(b.CategoryId = c.Id) 
                      WHERE Id = '?'";
            $parameters = array($id);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
            //Check if only one row is retrieved
            if (count($result) > 1) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
            
            //Convert row to Blogpost object
            $blogcontext = BlogContextQueries::convertOuterRow($row);
            
            //Get correspônding comments of (partial) blogcontext
                        //Prepare inner query string
                        $innerquery = CommentDB::getByBlogpostId($blogcontext->Id);
                        //initiate inner result array
                         $innerresultsArray = array();
                        //preform inner query
                        $innerresult = $conn->executeQuery($query);
                        //iterate over inner resultset
                        for($j = 0; $j < $innerresult->num_rows; $j++)
                        {
                            //Request current selected row from inner resultset as array
                            $innerrow = $innerresult->fetch_array();
                            //Convert inner row to inner comment object
                            $innercomment = CommentDB::convertRow($innerrow);
                            //Add inner comment to our inner resultsarray
                            $innerresultsArray[$j] =  $innercomment;

                        }
                        //add innerresultsarray to our outer blogcontext
                        $blogcontext->Comments =  $innerresultsArray;
            
            //Return the object
            return $blogcontext;
            
        }
        
        //Convert function to convert row to Blogcontext
        protected static function convertOuterRow($row)
        {
            return new BlogContext(
                $row['Id'],
                $row['UserId'],
                $row['Username'],
                $row['CategoryId'],
                $row['CategoryName'],
                $row['Title'],
                $row['Date'],
                $row['Content'],
                $row['ImageUrl']
            );
        }
    }

?>