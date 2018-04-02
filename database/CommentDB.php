<?php
    include_once "../data/Comment.php";
    include_once "DatabaseFactory.php";

    //Class that contains CRUD methods for Comments, does not contain any data, only static methods
    class CommentDB
    {
        //retrieves connection form DatabaseFactory
        private static function getConnection()
        {
            return DatabaseFactory::getDatabase();
        }
        
        //Retrieve all Comments
        public static function getAll()
        {
            //Prepare query string
            $query = "SELECT * FROM COMMENTS";
            
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
                //Convert row to Comment object
                $comment = CommentDB::convertRow($row);
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $comment;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        //Retrieve single Comment
        public static function getById($id)
        {
            //Prepare query string
            $query = "SELECT * FROM COMMENT WHERE Id = '?'";
            $parameters = array($id);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
            //Check if only one row is retrieved
            if (count($result) > 1) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
            
            //Convert row to comment object
            $comment = CommentDB::convertRow($row);
            
            //Return the object
            return $comment;
            
        }
        
        //Retrieve Comments by blogpostID
        public static function getByBlogpostId($blogpostId)
        {
            //Prepare query string
            $query = "SELECT * FROM COMMENTS WHERE BlogpostId ='?'";
            $parameters = array($blogpostId);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
             
            //initialize empty resultsArray to return to frontend
            $resultsArray = array();
            //iterate over resultset
            for($i = 0; $i < $result->num_rows; $i++)
            {
                //Request current selected row from resultset as array
                $row = $result->fetch_array();
                //Convert row to Comment object
                $comment = CommentDB::convertRow($row);
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $comment;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        
        //Insert a Comment
        public static function insert($comment)
        {
            $querystring = "INSERT INTO COMMENTS(BlogpostId, UserId, Content) VALUES ('?','?','?')";
            $parameters = array($comment->BlogpostId, $comment->UserId, $comment->Content);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
         //Update a Comment
        public static function update($comment)
        {
            $querystring = "UPDATE COMMENTS SET Content = '?' WHERE Id = '?'";
            $parameters = array($comment->Content, $comment->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Delete a Comment
        public static function delete($comment)
        {
            $querystring = "DELETE FROM COMMENTS WHERE Id = '?'";
            $parameters = array($comment->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Convert function to convert row to Comment
        public static function convertRow($row)
        {
            return new Comment(
                $row['Id'],
                $row['BlogpostId'],
                $row['UserId'],
                $row['Date'],
                $row['Content']
            );
        }
    }

?>