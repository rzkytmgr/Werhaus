<?php

class Categories extends Model
{

    public $tablename = "categories";
    public function connection()
    {
        return 'conf';
    }

    public function attributes()
    {
        return array(
            'id' => 'CategoryId',
            'CategoryName',
            'CategoryCode'
        );
    }
}
