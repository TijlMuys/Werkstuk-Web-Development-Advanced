<?php
    //User class with info of a single blogpost category from the database
    class Category
    {
        public $Id;
        public $CategoryName;
        
        //Constructor of Category
        public function __construct($Id, $CategoryName) 
        {
            if($Id != -1)
            {
                $this->Id = $Id;
            }
            $this->CategoryName = $CategoryName;
        }
    }
/*
Derycke, M. PHP & MySql tutorial, https://ehb.instructure.com/courses/690/pages/php-and-mysql-tutorial?module_item_id=20891.
Geraadpleegd op 2 april 2018
*/
?>