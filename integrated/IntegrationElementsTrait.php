<?php namespace Integrated;

trait IntegrationElementsTrait
{
    public function select($select, $option)
    {
        if ($element = $this->session->element('xpath', "//select[@name='$select']/option[@value='$option']")) {
            $element->click();
        }

        return $this;
    }

    public function submitFormWrapped($wrapper, $submitter, $data = [])
    {
        $submitter = $this->findWrapped($wrapper, $submitter);

        if ($submitter) {
            foreach ($data as $name => $value) {
                $element = $this->findWrapped($wrapper, $name);

                if ($element->name() == 'input' && ($element->attribute('type') == 'checkbox' || $element->attribute('type') == 'radio')) {
                    $element->click();
                } else {
                    $element->postValue($this->post($value));
                }
            }
        }

        $submitter->click();

        $this->updateCurrentUrl();

        return $this;
    }

    public function fillWrapped($wrapper, $name, $value)
    {
        return $this->typeWrapped($wrapper, $name, $value);
    }

    public function typeWrapped($wrapper, $name, $value)
    {
        $element = $this->findWrapped($wrapper, $name);

        $element->clear();

        $element->postValue($this->post($value));

        return $this;
    }

    /**
     * @param $wrapper
     * @param $name
     *
     * @return \WebDriver\Element
     * @throws \Exception
     */
    protected function findWrapped($wrapper, $name)
    {
        $class = '.' . $name;
        $id = '#' . $name;

        //first do a find as if the wrapper is an actual css selector
        //we also allow the name to be an actual selector, this is the only place where we allow this.
        //since the other finders are more meant for forms, we should most likely be able to find
        //every input element using those methods instead of using a specific selector.
        //its much easier to just type 'something' and it autolooks for '#something' or '.something' or '[name=something]'
        //within the provided wrapper
        //you should always be able to identify a field by one of those 3 methods.


        //first try finding elements using specific selectors
        try {

            try{
                //find using both being specific
                $selector = "$wrapper $name";

                return $this->session->element('css selector', $selector);
            }
            catch(NoSuchElement $e)
            {
                try{
                    //find using specific $wrapper
                    $selector = "$wrapper $class, $wrapper $id, $wrapper [name='$name']";

                    return $this->session->element('css selector', $selector);
                }
                catch(NoSuchElement $e)
                {
                    //find using specific $name
                    $selector = "#$wrapper $name, .$wrapper $name";

                    return $this->session->element('css selector', $selector);
                }
            }

        }
        catch (NoSuchElement $e) {
            //next we run through finders more specific to forms
            try {
                $selector = "#$wrapper $id, #$wrapper $class, #$wrapper [name='$name']";

                return $this->session->element('css selector', $selector);
            }
            catch (InvalidSelector $e)
            {
                //if this one fails due to invalid selector, we assume we tried selecting by a specific selector,
                //therefor you end up with stuff like #[name='somename'] which is invalid
                //if this happens, we throw a manual exception
                throw new \Exception("we couldn't find an element using a specific selector: $selector");
            }
            catch (NoSuchElement $e) {
                //now try to find it with a wrapper class
                $selector = ".$wrapper $id, .$wrapper $class, .$wrapper [name='$name']";

                return $this->session->element('css selector', $selector);
            }
        }
    }

    public function find($name)
    {
        $class = '.' . $name;
        $id = '#' . $name;

        try {
            return $this->session->element('css selector', $name);
        }
        catch (NoSuchElement $e) {
            $selector = "$id, $class, [name='$name']";

            return $this->session->element('css selector', $selector);
        }
    }

    public function findCss($selector)
    {
        return $this->session->element('css selector', $selector);
    }
}