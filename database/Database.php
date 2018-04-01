<?php
    //Class that represents the Database connection
    //Add Reference to tutorial series
    class Database {
        
       //Datamembers of class
        protected $url;
        protected $username;
        protected $password;
        protected $databasename;
        protected $connection;
        
        //Constructor with connection-variables
        public function __construct($url, $username, $password, $database)
        {
            $this->url = $url;
            $this->username = $username;
            $this->password = $password;
            $this->database = $database;
                
        }
        
        //destructor to close connection
        public function __destruct()
        {
            if ($this->connection != null)
            {
                $this->closeConnection();
            }
        }
        
        //Method used to connect to database (reference to tutorial)
        protected function makeConnection(){
            //make new connection
            $this->connection = new mysqli($this->url, $this->username, $this->password, $this->database);
            //test if connection failed, if the case print error message
            if ($this->connection->connect_error)
            {
                echo "Fail:" . $this->connection->connect_error;
            }
        }
        
        //Method to close connection
        protected function closeConnection() 
        {
            if ($this->connection != null)
            {
                $this->connection->close();
                $this->connection = null;
            }
        }
        
        //function to clean parameters of statements to prevent SQL injection
        protected function cleanParameters($parameter)
        {
            //prevent SQL injection
            $result = $this->connection->real_escape_string($parameter);
            return $result;
        }
            
        
        //Method to execute Queries
        public function executeQuery($query, $params = null)
        {
            //Check if there is a Database connection
            $this->makeConnection();
            //Adjust query with params if available
            
            if ($params != null)
            {
                //Change the ?-marks in querystring to the corresponding values in the params array
                //split query in parts
                $queryParts = preg_split("/\?/", $query);
                //If the amount of ?-marks is not equal to the amout of params in params-array then we have an error
                if (count($queryParts) != count($params) + 1)
                {
                    return false;
                }
                //Add first part of combined query
                $finalQuery = queryParts[0];

                
                //loop over all the parameters
                for ($i = 0; $i < count($params); $i++)
                {
                    //Add current parameter to its question-mark after cleaning to prevent SQL injection
                    $finalQuery = $finalQuery . $this->cleanParameters($params[$i]) . $queryParts[$i + 1];
                }
                //make final query the querystring
                $query = $finalQuery;
            }
            //execute query
            $results = $this->connection->query($query);
            var_dump($results);
            //return resultset (rows)
            return $results;
        }
        
        
            
       
 
    }
?>