<?php

namespace UrbanDataAnalytics;

use Exception;
use ReflectionClass;
use ReflectionProperty;

class Model
{
    /* @var string[] */
    protected $mandatory_fields = [];

    /**
     * @throws Exception
     */
    public function validates()
    {
        if (!empty($this->mandatory_fields)) {
            foreach ($this->mandatory_fields as $mandatory_field) {
                if (is_null($this->$mandatory_field)) {
                    throw new Exception('Required ' . $mandatory_field . ' for ' . get_called_class());
                }
            }
        }
    }

    /**
     * Retrieve the object as a JSON serialized string
     *
     * @return string
     */
    public function __toString()
    {
        $ReflectionClass = new ReflectionClass($this);

        $properties = [];

        foreach ($ReflectionClass->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
            $properties[$property->getName()] = $property->getValue($this);
        }

        $properties = array_filter($properties, function ($value) {
            return !is_null($value);
        });

        return json_encode($properties);
    }
}