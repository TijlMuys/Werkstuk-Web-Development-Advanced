<?php
    include_once "../data/Category.php";
    include_once("../database/DatabaseFactory.php");

    //Class that contains CRUD methods for Categories, does not contain any data, only static methods
    class CategoryDB
    {
        //retrieves connection form DatabaseFactory
        private static function getConnection()
        {
            return DatabaseFactory::getDatabase();
        }
        
        //Retrieve all categories
        public static function getAll()
        {
            //Prepare query string
            $query = "SELECT * FROM CATEGORIES ORDER BY CategoryName";
            
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
                //Convert row to category object
                $category = CategoryDB::convertRow($row);
                //Add blogpost object to resultsArray
                $resultsArray[$i] = $category;
            }
            
            //Return the resultsArray
            return $resultsArray;
            
        }
        
        //Retrieve single category
        public static function getById($id)
        {
            //Prepare query string
            $query = "SELECT * FROM CATEGORIES WHERE Id = '?'";
            $parameters = array($id);
            
            //Execute query
            $conn = self::getConnection();
            $result = $conn->executeQuery($query, $parameters);
             
            //Check if only one row is retrieved
            if (count($result) > 1) return false;
           
            //Request current selected row from resultset as array
            $row = $result->fetch_array();
            
            //Convert row to category object
            $category = CategoryDB::convertRow($row);
            
            //Return the object
            return $category;
            
        }
        
        //Insert a category
        public static function insert($category)
        {
            $querystring = "INSERT INTO CATEGORIES(CategoryName) VALUES ('?')";
            $parameters = array($category->CategoryName);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
         //Update a category
        public static function update($category)
        {
            $querystring = "UPDATE CATEGORIES SET CategoryName = '?' WHERE Id = '?'";
            $parameters = array($category->CategoryName, $category->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Delete a category
        public static function delete($category)
        {
            $querystring = "DELETE FROM CATEGORIES WHERE Id = '?'";
            $parameters = array($category->Id);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        public static function deleteById($categoryId)
        {
            $querystring = "DELETE FROM CATEGORIES WHERE Id = '?'";
            $parameters = array($categoryId);
            return self::getConnection()->executeQuery($querystring, $parameters);
        }
        
        //Convert function to convert row to Category
        protected static function convertRow($row)
        {
            return new Category(
                $row['Id'],
                $row['CategoryName']
            );
        }
    }

/*
Derycke, M. PHP & MySql tutorial, https://ehb.instructure.com/courses/690/pages/php-and-mysql-tutorial?module_item_id=20891.
Geraadpleegd op 2 april 2018
*/
?>