<?php

namespace consultnn\embedded;

use yii\base\Model;
use yii\mongodb\ActiveRecord;
use yii\base\UnknownPropertyException;
use yii\validators\DefaultValueValidator;

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
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
    public function setFormName($formName)
    {
        if (!empty($formName)) {
            $this->_formName = $formName;
        }
    }

    /**
     * set link to primary model
     * @param ActiveRecord $model
     */
    #[\ReturnTypeWillChange]
    public function setPrimaryModel(ActiveRecord $model)
    {
        $this->_primaryModel = $model;
    }

    /**
     * get link to primary model
     * @return ActiveRecord
     * @throws UnknownPropertyException
     */
    #[\ReturnTypeWillChange]
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
    #[\ReturnTypeWillChange]
    public function setSource($value)
    {
        $this->_source = $value;
    }

    /**
     * Save embedded model as attribute on primary model
     * @throws UnknownPropertyException
     */
    #[\ReturnTypeWillChange]
    public function save()
    {
        if (!isset($this->_source) || !$this->primaryModel->hasAttribute($this->_source)) {
            throw new UnknownPropertyException('source attribute is not set or not exists');
        }
        $this->primaryModel->save(false, [$this->_source]);
    }

    #[\ReturnTypeWillChange]
    public function setScenario($scenario)
    {
        if (array_key_exists($scenario, $this->scenarios())) {
            parent::setScenario($scenario);
        }
    }

    /**
     * Checks if embedded model is empty.
     * Doesn't take into account validator's "when" and "isEmpty" parameters.
     *
     * Проверяет объект на пустоту.
     * Пустым считается объект все атрибуты которого пусты (empty($value)) или равны значениям по-умолчанию. Не учитывает параметры "when" и "isEmpty" валидаторов "по-умолчанию".
     * @return bool
     */
    #[\ReturnTypeWillChange]
    public function isEmpty()
    {
        $notEmptyAttributes = [];
        foreach ($this->attributes() as $attribute) {
            if (!empty($this->$attribute)) {
                $notEmptyAttributes[$attribute] = $attribute;
            }
        }

        foreach ($this->getActiveValidators() as $validator) {
            if (($validator instanceof DefaultValueValidator) && ($checkAttributes = array_intersect($validator->attributes, $notEmptyAttributes))) {
                /** @var \yii\validators\DefaultValueValidator $validator */
                foreach ($checkAttributes as $attribute) {
                    if ($this->$attribute == $validator->value) {
                        unset($notEmptyAttributes[$attribute]);
                    }
                }
            }
        }
        return empty($notEmptyAttributes);
    }
}
