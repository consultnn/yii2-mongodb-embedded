<?php

namespace consultnn\embedded;

use yii\base\Behavior;

class AbstractEmbeddedBehavior extends Behavior
{
    /**
     * Attribute for store embedded document
     * @var string
     */
    public $attribute;

    /**
     * Embedded document class name
     * @var string
     */
    public $embeddedClass;


    public function canSetProperty($name, $checkVars = true)
    {
        return $this->checkName($name) || parent::canSetProperty($name, $checkVars);
    }

    public function canGetProperty($name, $checkVars = true)
    {
        return $this->checkName($name) || parent::canGetProperty($name, $checkVars);
    }

    protected function checkName($name)
    {
        return substr($this->attribute, 1) == $name;
    }
}