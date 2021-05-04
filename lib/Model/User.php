<?php

class User extends Model
{

    public $tablename = "users";
    public function connection()
    {
        return 'conf';
    }

    public function attributes()
    {
        return array(
            'id' => 'UserEmail',
            'UserPassword',
            'UserFullname',
            'UserRole'
        );
    }
}
