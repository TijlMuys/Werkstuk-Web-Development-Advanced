<?php
    //Class that contains all the info of a blogpost that needs to presented in the final view, does not reflect a single database table
    class BlogContext
    {
        public $Id;
        public $UserId;
        public $Username;
        public $CategoryId;
        public $CategoryName;
        public $Title;
        public $Date;
        public $Content;
        public $ImageUrl;
        public $Comments;
        
        //Constructor of Blogpost
        public function __construct($Id, $UserId, $Username, $CategoryId, $CategoryName, $Title, $Date, $Content, $ImageUrl, $Comments=null) 
        {
            if($Id != -1)
            {
                $this->Id = $Id;
            }
            $this->UserId = $UserId;
            $this->Username = $Username;
            $this->CategoryId = $CategoryId;
            $this->CategoryName = $CategoryName;
            $this->Title = $Title;
            if($Date != -1)
            {
                $this->Date = $Date;
            }
            $this->Content = $Content;
            $this->ImageUrl = $ImageUrl;
            $this->Comments = $Comments;
        }
    }
?>