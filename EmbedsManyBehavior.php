<?php
/**
 * Created by PhpStorm.
 * User: sokrat
 * Date: 30.03.15
 * Time: 14:00
 */

namespace consultnn\embedded;

use yii\base\Model;

/**
 * Class EmbedsManyBehavior
 *
 * @property Model[]|array $models
 * @package common\behaviors
 */
class EmbedsManyBehavior extends AbstractEmbeddedBehavior
{
    /**
     * @var array|Model[]
     */
    private $_models = [];

    public function __set($name, $value)
    {
        if ($this->checkName($name)) {
//            $this->model->scenario = $this->owner->scenario;
//            $this->model->setAttributes($value);
//
//            $this->owner->{$this->attribute} = $this->model->attributes;
        } else {
            parent::__set($name, $value);
        }

    }

    public function __get($name)
    {
        if ($this->checkName($name)) {
            return $this->models;
        } else {
            return parent::__get($name);
        }
    }

    /**
     * @return Model[]
     */
    public function getModels()
    {
        if (empty($this->_storage)) {
            $this->_storage = new \SplObjectStorage();
        }
//        if (empty($this->_models) && !empty($this->owner->{$this->attribute})) {
//            $this->_models = new $this->embeddedClass;
//            $this->_models->scenario = $this->owner->scenario;
//            $this->_models->setAttributes($this->owner->{$this->attribute});
//            $this->owner->on(ActiveRecord::EVENT_BEFORE_VALIDATE, [$this, 'validate']);
//            $this->owner->on(ActiveRecord::EVENT_BEFORE_INSERT, [$this, 'proxy']);
//            $this->owner->on(ActiveRecord::EVENT_BEFORE_UPDATE, [$this, 'proxy']);
//        }
        return $this->_storage;
    }

    public function proxy($event)
    {
//        $this->model->trigger($event->name);
//        $this->owner->{$this->attribute} = $this->model->attributes;
    }

    public function validate()
    {
//        if (!$this->model->validate()) {
//            $this->owner->addError($this->attribute, \Yii::t('yii', 'Embedded document in {attribute} must be valid.'));
//            return false;
//        }
    }
}