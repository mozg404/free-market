<?php

namespace App\Exceptions\Feedback;

class FeedbackAlreadyExistsException extends \DomainException
{
    protected $message = 'Отзыв уже существует';
    protected $code = 409;
}