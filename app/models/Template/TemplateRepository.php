<?php

namespace Template;

class TemplateRepository {

    /**
     * @var Sidebar
     */
    protected $sidebar;

    /**
     * @var Breadcrumb
     */
    protected $crumb;

    public function __construct(Sidebar $sidebar, Breadcrumb $crumb)
    {
        $this->sidebar = $sidebar;

        $this->crumb = $crumb;
    }

    public function sidebar()
    {
        return $this->sidebar->render();
    }

    public function crumb($items)
    {
        return $this->crumb->render($items);
    }

} 