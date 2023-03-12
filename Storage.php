<?php

namespace consultnn\embedded;

use yii\base\Component;
use yii\base\Event;

/**
 * Storage for Embedded Documents
 * @property integer $nextIndex
 * @property array $attributes
 * @package consultnn\embedded
 */
class Storage extends Component implements StorageInterface, \Countable, \Iterator
{
    private $_container = [];

    private $_cursor = 0;

    #[\ReturnTypeWillChange]
    public function removeAll()
    {
        $this->_container = [];
        $this->rewind();
    }

    /**
     * @param string $name
     * @param Event $event
     */
    #[\ReturnTypeWillChange]
    public function trigger($name, Event $event = null)
    {
        parent::trigger($name, $event);
        foreach ($this->_container as $model) {
            /** @var EmbeddedDocument $model */
            $model->trigger($name, $event);
        }
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function validate()
    {
        $hasError = false;
        foreach ($this->_container as $model) {
            /** @var EmbeddedDocument $model */
            if (!$model->validate()) {
                $hasError = true;
            }
        }
        return !$hasError;
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    public function getAttributes()
    {
        $attributes = [];
        foreach ($this->_container as $model) {
            /** @var EmbeddedDocument $model */
            $attributes[] = $model->getAttributes();
        }
        return $attributes;
    }

    /**
     * Get embedded model by condition
     * @param $condition
     * @return null| EmbeddedDocument
     */
    #[\ReturnTypeWillChange]
    public function get($condition)
    {
        list($attribute, $value) = $condition;

        foreach ($this->_container as $object) {
            if ($object->$attribute == $value) {
                return $object;
            }
        }
        return null;
    }

    /**
     * Set embedded model by condition
     * @param $condition
     * @param $object
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function set($condition, $object)
    {
        list($attribute, $value) = $condition;

        foreach ($this->_container as $key => $needleObject) {
            if ($needleObject->$attribute == $value) {
                $this->offsetSet($key, $object);
                return true;
            }
        }
        return false;
    }

    #[\ReturnTypeWillChange]
    public function sort($field)
    {
        $attributes = $this->attributes;
        if (empty($attributes) || !in_array($field, array_keys(reset($attributes)))) {
            return;
        }
        usort($this->_container, function ($item1, $item2) use ($field) {
            return $item1[$field] <=> $item2[$field];
        });
    }

    #[\ReturnTypeWillChange]
    public function offsetSet($offset, $model)
    {
        if (is_null($offset)) {
            $offset = $this->getNextIndex();
        }

        $this->_container[$offset] = $model;
    }

    #[\ReturnTypeWillChange]
    public function current()
    {
        if ($this->valid($this->_cursor)) {
            return $this->_container[$this->_cursor];
        } else {
            return null;
        }
    }

    #[\ReturnTypeWillChange]
    public function key()
    {
        return $this->_cursor;
    }

    #[\ReturnTypeWillChange]
    public function next()
    {
        ++$this->_cursor;
    }

    #[\ReturnTypeWillChange]
    public function rewind()
    {
        $this->_cursor = 0;
    }

    #[\ReturnTypeWillChange]
    public function valid()
    {
        return $this->offsetExists($this->_cursor);
    }

    #[\ReturnTypeWillChange]
    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }

    #[\ReturnTypeWillChange]
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
        $this->_container = array_values($this->_container);
    }

    #[\ReturnTypeWillChange]
    public function offsetGet($offset)
    {
        return isset($this->_container[$offset]) ? $this->_container[$offset] : null;
    }

    #[\ReturnTypeWillChange]
    public function count()
    {
        return count($this->_container);
    }

    #[\ReturnTypeWillChange]
    public function getNextIndex()
    {
        $count = count($this->_container);
        return $count ? $count : 0;
    }

    /**
     * Set scenario to embedded model
     * @param string $scenario
     * @return void
     */
    #[\ReturnTypeWillChange]
    public function setScenario($scenario)
    {
        foreach ($this->_container as $model) {
            /** @var EmbeddedDocument $model */
            $model->setScenario($scenario);
        }
    }

    #[\ReturnTypeWillChange]
    public function __toString()
    {
        return '';
    }
}