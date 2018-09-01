<?php


namespace Dymantic\MultilingualPosts\Tests;


use Illuminate\Foundation\Auth\User as Authenticable;

class TestUser extends Authenticable
{
    protected $fillable = ['name', 'email', 'password'];

    public $timestamps = false;
}