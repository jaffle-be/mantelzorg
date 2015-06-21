<?php namespace App\System;

use Jenssegers\Agent\Agent;

class UI {

    /**
     * @var bool
     */
    protected $tablet;

    /**
     * @var bool
     */
    protected $mobile;

    public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }

    public function isMobile()
    {
        if(!is_bool($this->mobile))
        {
            $this->mobile = $this->agent->isMobile();
        }

        return $this->mobile;
    }

    public function isTablet()
    {
        if(!is_bool($this->tablet))
        {
            $this->tablet = $this->agent->isTablet();
        }

        return $this->tablet;
    }

}