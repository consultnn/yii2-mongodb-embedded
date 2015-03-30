<?php
/**
 * Created by PhpStorm.
 * User: sokrat
 * Date: 28.03.15
 * Time: 18:49
 */

namespace consultnn\embedded;

use yii\base\Model;
use yii\mongodb\ActiveRecord;

/**
 * Class EmbeddedOneBehavior
 * @property Model $model
 * @property \yii\mongodb\ActiveRecord $owner
 * @package common\behaviors
 */
class EmbedsOneBehavior extends AbstractEmbeddedBehavior
{
    /**
     * @var null|Model
     */
    private $_model = null;

    public function __set($name, $value)
    {
        if ($this->checkName($name)) {
            $this->model->scenario = $this->owner->scenario;
            $this->model->setAttributes($value);

            $this->owner->{$this->attribute} = $this->model->attributes;
        } else {
            parent::__set($name, $value);
        }

    }

    public function __get($name)
    {
        if ($this->checkName($name)) {
            return $this->model;
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        if (empty($this->_model)) {
            $this->_model = new $this->embeddedClass;
            $this->_model->scenario = $this->owner->scenario;
            $this->_model->setAttributes($this->owner->{$this->attribute});
            $this->owner->on(ActiveRecord::EVENT_BEFORE_VALIDATE, [$this, 'validate']);
            $this->owner->on(ActiveRecord::EVENT_BEFORE_INSERT, [$this, 'proxy']);
            $this->owner->on(ActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'proxy']);
        }
        return $this->_model;
    }

    public function proxy($event)
    {
        $this->model->trigger($event->name);
        $this->owner->{$this->attribute} = $this->model->attributes;
    }

    public function validate()
    {
        if (!$this->model->validate()) {
            $this->owner->addError($this->attribute, \Yii::t('yii', 'Embedded document in {attribute} must be valid.'));
            return false;
        }
    }
}