<?php
    //User class with info of a single blogpost comment from the database
    class Comment
    {
        public $Id;
        public $BlogpostId;
        public $UserId;
        public $Date;
        public $Content;
        
        //Constructor of Blogpost
        public function __construct($Id, $BlogpostId, $UserId, $Date, $Content) 
        {
            if($Id != -1)
            {
                $this->Id = $Id;
            }
            $this->BlogpostId = $BlogpostId;
            $this->UserId = $UserId;
            if($Date != -1)
            {
               $this->Date = $Date;
            }
            $this->Content = $Content;
        }
    }
?>