<?php

/**
* Model for Tasks on the todo list
*/
class Tasks extends CSV_Model
{
    /**
    * ctor
    */
    public function __construct()
    {
        parent::__construct(APPPATH . '../data/tasks.csv', 'id');
    }
}
