<?php namespace Integrated;

class AnnotationReader extends \Laracasts\Integrated\AnnotationReader
{

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