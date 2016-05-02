<?php namespace Integrated;

use ReflectionClass;

class AnnotationReader
{

    /**
     * The object to reflect into.
     *
     * @var object
     */
    protected $reference;

    /**
     * Create a new AnnotationReader instance.
     *
     * @param mixed $reference
     */
    public function __construct($reference)
    {
        $this->reference = $reference;
    }

    /**
     * Reflect into the given object.
     *
     * @param  object $object
     * @return ReflectionClass
     */
    protected function reflectInto($object)
    {
        return (new ReflectionClass($object))->getMethods();
    }

    /**
     * Search the docblock for the given annotation.
     *
     * @param  string            $annotation
     * @param  \ReflectionMethod $method
     * @return boolean
     */
    protected function hasAnnotation($annotation, \ReflectionMethod $method)
    {
        return str_contains($method->getDocComment(), "@{$annotation}");
    }


    public function having($annotation)
    {
        $methods = [];

        foreach ($this->reflectInto($this->reference) as $method) {
            if ($this->hasAnnotation($annotation, $method)) {

                $adding = ['name' => $method->getName()];

                $priority = 0;

                if($this->hasAnnotation('priority', $method))
                {
                    $priority = $this->priority($method);
                }

                $adding['priority'] = $priority;

                $methods[] = $adding;

            }
        }

        //now we'll sort them using the priority annotation
        $sort = array_sort($methods, function ($item){
            return $item['priority'];
        });

        return array_column($sort, 'name');

    }

    protected function priority(\ReflectionMethod $method)
    {
        $matches = [];

        preg_match('/@priority\s(\d+)/', $method->getDocComment(), $matches);

        return (int) $matches[1];
    }

}