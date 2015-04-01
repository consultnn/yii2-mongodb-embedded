<?php

namespace consultnn\embedded;

/**
 * Class EmbedsManyBehavior
 * @property Storage $storage
 * @package common\behaviors
 */
class EmbedsManyBehavior extends AbstractEmbeddedBehavior
{
    public $initEmptyScenarios = [];

    protected function setAttributes(array $attributes, $safeOnly = true)
    {
        $this->storage->removeAll();
        if (in_array($this->owner->scenario, $this->initEmptyScenarios) && !count($attributes)) {
            $attributes[] = [];
        }
        foreach($attributes as $modelAttributes)
        {
            /** @var EmbeddedDocument $model */
            $model = new $this->embeddedClass;
            $model->scenario = $this->owner->scenario;
            $model->setAttributes($modelAttributes, $safeOnly);
            $this->storage[] = $model;
        }

    }

    public function modelProcessing($model, $index)
    {
        $model->formName = $this->getFormName($index);
    }

    /**
     * @return Storage
     */
    public function getStorage()
    {
        if (empty($this->_storage)) {
            $this->_storage = new Storage(['callBack' => [$this, 'modelProcessing']]);
        }
        return $this->_storage;
    }
}