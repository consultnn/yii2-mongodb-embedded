<?php

namespace consultnn\embedded;

use yii\helpers\Html;

/**
 * Class EmbedsManyBehavior
 * @property Storage $storage
 * @package common\behaviors
 */
class EmbedsManyBehavior extends AbstractEmbeddedBehavior
{
    public $initEmptyScenarios = [];

    public function getFormName($index)
    {
        return Html::getInputName($this->owner, $this->fakeAttribute."[{$index}]");
    }

    protected function setAttributes(array $attributes, $safeOnly = true)
    {
        $this->storage->removeAll();
        foreach($attributes as $modelAttributes) {
            $model = $this->createEmbedded($modelAttributes, $safeOnly);
            $model->setFormName($this->getFormName($this->storage->getNextIndex()));
            $this->storage[] = $this->createEmbedded($modelAttributes, $safeOnly);
        }
    }

    /**
     * @return Storage
     */
    public function getStorage()
    {
        if (empty($this->_storage)) {
            $this->_storage = new Storage();
            foreach ((array)$this->owner->{$this->attribute} as $attributes) {
                $model = $this->createEmbedded($attributes, false);
                $model->setFormName($this->getFormName($this->_storage->getNextIndex()));
                $this->_storage[] = $this->createEmbedded($attributes, false);
            }
        }
        return $this->_storage;
    }
}