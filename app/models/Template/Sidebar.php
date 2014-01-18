<?php

namespace Template;

class Sidebar {

    /**
     * @var array
     */
    protected $config;

    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function render()
    {
        $output = $this->open();

        foreach($this->config['items'] as $item)
        {
            $condition = isset($item['condition']) ? $item['condition'] : false;

            if($condition instanceof \Closure)
            {
                if($condition())
                {
                    $output .= $this->item($item);
                }
            }
            else
            {
                $output .= $this->item($item);
            }

        }

        $output .= $this->close();

        return $output;
    }

    /**
     * @return string
     */
    protected function open($sublinks = false)
    {
        if($sublinks)
        {
            return '<div><ul class="subnav">';
        }

        return '<ul>';
    }

    /**
     * @return string
     */
    protected function close($sublinks = false)
    {
        if($sublinks)
        {
            return '</ul></div>';
        }
        return '</ul>';
    }

    /**
     * @param array $item
     * @return string
     */
    protected function item(array $item)
    {
        if(isset($item['sublinks']) && !empty($item['sublinks']))
        {
            $output = $this->open(true);

            foreach($item['sublinks'] as $sublink)
            {
                $output .= $this->item($sublink);
            }

            $output .= $this->close(true);

            return sprintf('<li><a href="%s"><span class="%s"></span>%s</a>%s', $item['href'], isset($item['class']) ? $item['class'] : null, $item['text'], $output);
        }

        return sprintf('<li><a href="%s"><span class="%s"></span>%s</a>', $item['href'], isset($item['class']) ? $item['class'] : null, $item['text']);
    }

} 