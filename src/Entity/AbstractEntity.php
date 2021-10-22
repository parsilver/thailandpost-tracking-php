<?php namespace Farzai\ThaiPost\Entity;

use JsonSerializable;
use Psr\Http\Message\ServerRequestInterface;

abstract class AbstractEntity implements JsonSerializable
{

    /**
     * @var array|null
     */
    protected $data;

    /**
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }

    /**
     * @param ServerRequestInterface $request
     * @return static
     */
    public static function fromRequest(ServerRequestInterface $request)
    {
        return static::fromArray(@json_decode((string)$request->getBody(), true) ?: []);
    }

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function asJson()
    {
        return json_encode($this);
    }

    /**
     * @return array
     */
    public function asArray()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
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

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->asJson();
    }
}