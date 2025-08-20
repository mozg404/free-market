<?php

namespace App\Exceptions\Category;

class CategoryFullPathConflictException extends \Exception
{
    protected $message = 'Category path conflict';
    protected $code = 422;
}