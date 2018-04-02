<?php
    //Blogpost class with info of a single blogpost from the database
    class Blogpost
    {
        public $Id;
        public $UserId;
        public $CategoryId;
        public $Title;
        public $Date;
        public $Content;
        public $ImageUrl;
        
        //Constructor of Blogpost
        public function __construct($Id, $UserId, $CategoryId, $Title, $Date, $Content, $ImageUrl) 
        {
            if($Id != -1)
            {
                $this->Id = $Id;
            }
            $this->UserId = $UserId;
            $this->CategoryId = $CategoryId;
            $this->Title = $Title;
            if($Date != -1)
            {
                $this->Date = $Date;
            }
            $this->Content = $Content;
            $this->ImageUrl = $ImageUrl;
        }
    }
?>