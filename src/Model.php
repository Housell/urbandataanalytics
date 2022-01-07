<?php

namespace UrbanDataAnalytics;

use Exception;

class Model
{
    /**
     * @var string[]
     */
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
}