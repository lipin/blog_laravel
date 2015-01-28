<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface
{
    use UserTrait;

    protected $table = 'users';
    protected $hidden = array('password', 'remember_token');
    protected $fillable = array('email','nickname','password');
 	protected $guarded = array('id', 'password');
}
