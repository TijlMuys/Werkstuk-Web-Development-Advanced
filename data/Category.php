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
?>