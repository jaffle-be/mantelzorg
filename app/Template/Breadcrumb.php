<?php

namespace App\Template;

class Breadcrumb
{
    public function render($items)
    {
        $output = $this->open();

        $teller = 1;

        while ($teller <= count($items)) {
            if ($teller == count($items)) {
                $output .= $this->last($items[$teller - 1]);
            } else {
                $output .= $this->regular($items[$teller - 1]);
            }

            ++$teller;
        }

        $output .= $this->close();

        return $output;
    }

    public function open()
    {
        return '<ol class="breadcrumb">';
    }

    public function regular($item)
    {
        if (isset($item['href'])) {
            return sprintf('<li><a href="%s">%s</a></li>', $item['href'], $item['text']);
        }

        return sprintf('<li>%s</li>', $item['text']);
    }

    public function last($item)
    {
        return sprintf('<li class="active">%s</li>', $item['text']);
    }

    public function close()
    {
        return '</ol>';
    }
}
