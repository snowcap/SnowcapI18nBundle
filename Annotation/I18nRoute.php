<?php

namespace Snowcap\I18nBundle\Annotation;

/**
 * @Annotation
 */
class I18nRoute {
    /**
     * @var array
     */
    static private $validProperties = array('path', 'name', 'requirements', 'options', 'defaults', 'host', 'methods', 'schemes', 'pattern');

    /**
     * @var array
     */
    public $data;

    /**
     * @param array $data
     */
    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * @param $name
     * @param $arguments
     * @return null
     * @throws \Exception
     */
    public function __call($name, $arguments)
    {
        if(0 === strpos($name, 'get')) {
            $propertyName = strtolower(substr($name, 3));

            if(!in_array($propertyName, self::$validProperties)) {
                throw new \Exception('cannot call');
            }

            if(isset($this->data[$propertyName])) {
                return $this->data[$propertyName];
            }

            return null;
        }
        else {
            throw new \Exception('cannot call');
        }
    }
}