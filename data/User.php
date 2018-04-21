<?php
    //User class with info of a single user from the database
    class User
    {
        public $Id;
        public $Username;
        public $Password;
        public $Email;
        public $IsAdmin;
        
        //Constructor of Blogpost
        public function __construct($Id, $Username, $Password, $Email, $IsAdmin=0) 
        {
            if($Id != -1)
            {
                $this->Id = $Id;
            }
            $this->Username = $Username;
            $this->Password = $Password;
            $this->Email = $Email;
            $this->IsAdmin = $IsAdmin;
        }
    }
/*
Derycke, M. PHP & MySql tutorial, https://ehb.instructure.com/courses/690/pages/php-and-mysql-tutorial?module_item_id=20891.
Geraadpleegd op 2 april 2018
*/
?>