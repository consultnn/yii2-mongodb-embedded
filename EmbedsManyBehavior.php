<?php

namespace consultnn\embedded;

use yii\helpers\Html;
use Yii;
use yii\validators\Validator;

/**
 * Class EmbedsManyBehavior
 * @property Storage $storage
 * @package common\behaviors
 */
class EmbedsManyBehavior extends AbstractEmbeddedBehavior
{
    public $initEmptyScenarios = [];

    #[\ReturnTypeWillChange]
    public function getFormName($index)
    {
        if ($this->setFormName) {
            return Html::getInputName($this->owner, $this->fakeAttribute."[{$index}]");
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    protected function setAttributes($attributes, $safeOnly = true)
    {
        $this->storage->removeAll();

        if (empty($attributes)) {
            return;
        }

        foreach ($attributes as $modelAttributes) {
            $model = $this->createEmbedded(
                $modelAttributes,
                $safeOnly,
                ['formName' => $this->getFormName($this->storage->getNextIndex())]
            );

            if ($this->saveEmpty || !$model->isEmpty()) {
                $this->storage[] = $model;
            }
        }
    }

    /**
     * @inheritdoc
     */
    #[\ReturnTypeWillChange]
    protected function getAttributes()
    {
        return $this->storage->attributes;
    }

    /**
     * @return Storage
     */
    #[\ReturnTypeWillChange]
    public function getStorage()
    {
        if (empty($this->_storage)) {
            $this->_storage = new Storage();

            $attributes = (array)$this->owner->{$this->attribute};
            if (empty($attributes) && in_array($this->owner->scenario, $this->initEmptyScenarios)) {
                $attributes[] = [];
            }

            foreach ($attributes as $modelAttributes) {
                $model = $this->createEmbedded(
                    $modelAttributes,
                    false,
                    ['formName' => $this->getFormName($this->_storage->getNextIndex())]
                );
                $this->_storage[] = $model;
            }
        }
        return $this->_storage;
    }
}