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
    public $embedded;

    /**
     * If true, Embedded model formName look like this: Company['address']
     * @var bool
     */
    public $setFormName = true;

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

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'validate',
            ActiveRecord::EVENT_BEFORE_INSERT => 'proxy',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'proxy'
        ];
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        if ($this->checkName($name)) {
            return $this->storage;
        } else {
            return parent::__get($name);
        }
    }

    public function __set($name, $value)
    {
        if ($this->checkName($name)) {
            $this->setAttributes($value);
        } else {
            parent::__set($name, $value);
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
        $this->storage->setScenario($this->owner->scenario);
        $this->storage->trigger($event->name, $event);
        $this->owner->{$this->attribute} = $this->storage->attributes;
    }

    /**
     * Validate storage
     */
    public function validate()
    {
        if ($this->owner->isAttributeSafe($this->fakeAttribute)) {
            $this->storage->setScenario($this->owner->scenario);
            if (!$this->storage->validate()) {
                $this->owner->addError($this->attribute, \Yii::t('yii', 'Embedded document in {attribute} must be valid.'));
            }
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
        return $this->getFakeAttribute() == $name;
    }

    protected function createEmbedded($attributes, $safeOnly = true, $config = [])
    {
        if (is_array($this->embedded)) {
            $embeddedConfig = $this->embedded;
        } else {
            $embeddedConfig = ['class' => $this->embedded];
        }
        $embeddedConfig = array_merge($embeddedConfig, $config);
        /** @var EmbeddedDocument $model */
        $model = \Yii::createObject($embeddedConfig);
        $model->scenario = $this->owner->scenario;
        $model->setAttributes($attributes, $safeOnly);
        return $model;
    }
}