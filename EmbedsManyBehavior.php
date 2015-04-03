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
            $model->formName = $this->getFormName($this->storage->getNextIndex());
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