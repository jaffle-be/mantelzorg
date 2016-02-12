<?php

namespace App\Template;

class TemplateRepository
{
    /**
     * @var Breadcrumb
     */
    protected $crumb;

    public function __construct(Breadcrumb $crumb)
    {
        $this->crumb = $crumb;
    }

    public function crumb($items)
    {
        return $this->crumb->render($items);
    }
}
