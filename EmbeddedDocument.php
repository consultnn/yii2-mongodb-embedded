<?php

namespace consultnn\embedded;


use yii\base\Model;
use yii\mongodb\ActiveRecord;
use yii\base\UnknownPropertyException;

/**
 * Class EmbeddedDocument
 * @property string $formName
 * @property ActiveRecord $primaryModel
 * @package consultnn\embedded
 */
class EmbeddedDocument extends Model
{
    /**
     * @var string
     */
    private $_formName;

    /**
     * @var ActiveRecord
     */
    private $_primaryModel;

    /**
     * @var string
     */
    private $_source;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        if (!empty($this->_formName)) {
            return $this->_formName;
        } else {
            return parent::formName();
        }
    }

    /**
     * @param $formName
     */
    public function setFormName($formName)
    {
        $this->_formName = $formName;
    }

    /**
     * set link to primary model
     * @param ActiveRecord $model
     */
    public function setPrimaryModel(ActiveRecord $model)
    {
        $this->_primaryModel = $model;
    }

    /**
     * get link to primary model
     * @return ActiveRecord
     * @throws UnknownPropertyException
     */
    public function getPrimaryModel()
    {
        if (!isset($this->_primaryModel)) {
            throw new UnknownPropertyException('primary model is not set');
        }
        return $this->_primaryModel;
    }


    /**
     * set link to primary model attribute
     * @param $value
     */
    public function setSource($value)
    {
        $this->_source = $value;
    }

    /**
     * Save embedded model as attribute on primary model
     * @throws UnknownPropertyException
     */
    public function save()
    {
        if (!isset($this->_source) || !$this->primaryModel->hasAttribute($this->_source)) {
            throw new UnknownPropertyException('source attribute is not set or not exists');
        }
        $this->primaryModel->save(false, [$this->_source]);
    }

    public function setScenario($scenario)
    {
        if (array_key_exists($scenario, $this->scenarios())) {
            parent::setScenario($scenario);
        }
    }
}