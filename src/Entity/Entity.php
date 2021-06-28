<?php namespace Farzai\ThaiPost\Entity;

abstract class Entity
{

    /**
     * @var array|mixed|null
     */
    protected $data;

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        $instance = new static;
        $instance->data = $data;

        return $instance;
    }

    /**
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->{$name};
        }

        return $this->data[$name] ?? null;
    }
}