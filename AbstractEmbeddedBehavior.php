<?php

namespace consultnn\embedded;

use yii\base\Behavior;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class AbstractEmbeddedBehavior
 * @property string $fakeAttribute {@link getFakeAttribute()}
 * @property \yii\mongodb\ActiveRecord $owner
 * @property mixed $storage
 * @package consultnn\embedded
 */
abstract class AbstractEmbeddedBehavior extends Behavior
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

    /**
     * @var mixed
     */
    protected $_storage;

    /**
     * set $_storage property and return it
     * @return mixed
     */
    abstract function getStorage();

    /**
     * Set attributes to storage
     * @param array $attributes
     * @param bool $safeOnly
     */
    abstract protected function setAttributes(array $attributes, $safeOnly = true);

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->checkName($name)) {
            if (empty($this->_storage)) {
                $this->setAttributes((array)$this->owner->{$this->attribute}, false);
            }
            return $this->storage;
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if ($this->checkName($name)) {
            $this->setAttributes($value);
            $this->attachEvents();
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * return form name for every model
     * @param null $index
     * @return string
     */
    public function getFormName($index = null)
    {
        if ($index !== null) {
            return Html::getInputName($this->owner, $this->fakeAttribute."[{$index}]");
        } else {
            return Html::getInputName($this->owner, $this->fakeAttribute);
        }
    }

    /**
     * @return string {@link $fakeAttribute}
     */
    public function getFakeAttribute()
    {
        return substr($this->attribute, 1);
    }

    /**
     * Trigger owner event in storage
     * @param \yii\base\Event $event
     */
    public function proxy($event)
    {
        $this->storage->trigger($event->name, $event);
        $this->owner->{$this->attribute} = $this->storage->attributes;
    }

    /**
     * Validate storage
     */
    public function validate()
    {
        if (!$this->storage->validate()) {
            $this->owner->addError($this->attribute, \Yii::t('yii', 'Embedded document in {attribute} must be valid.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true)
    {
        return $this->checkName($name) || parent::canSetProperty($name, $checkVars);
    }

    /**
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true)
    {
        return $this->checkName($name) || parent::canGetProperty($name, $checkVars);
    }

    /**
     * Check attribute name for getter
     * @param $name
     * @return bool
     */
    protected function checkName($name)
    {
        return $this->fakeAttribute == $name;
    }

    /**
     * Attache owner events to storage
     */
    protected function attachEvents()
    {
        $this->owner->on(ActiveRecord::EVENT_BEFORE_VALIDATE, [$this, 'validate']);
        $this->owner->on(ActiveRecord::EVENT_BEFORE_INSERT, [$this, 'proxy']);
        $this->owner->on(ActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'proxy']);
    }
}