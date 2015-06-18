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
            $model = \Yii::createObject(array_merge(
                $this->getEmbeddedConfig(),
                [
                    'formName' => $this->getFormName($this->storage->getNextIndex())
                ]
            ));
            $model->scenario = $this->owner->scenario;
            $model->setAttributes($modelAttributes, $safeOnly);
            $this->storage[] = $model;
        }
    }

    /**
     * @return Storage
     */
    public function getStorage()
    {
        if (empty($this->_storage)) {
            $this->_storage = new Storage();
        }
        return $this->_storage;
    }
}