<?php

namespace Webcomcafe\Templating\Exceptions;

class TemplateNotFoundException extends TemplateException
{
    protected $message = 'Template Not Found';
}