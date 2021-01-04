<?php

namespace Webcomcafe\Templating\Exceptions;

class ViewNotFoundException extends TemplateException
{
    protected $code = 500;
    protected $message = 'View file not found';
}