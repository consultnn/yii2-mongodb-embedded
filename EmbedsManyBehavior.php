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
            $model = $this->createEmbedded(
                $modelAttributes,
                $safeOnly,
                ['formName' => $this->getFormName($this->storage->getNextIndex())]
            );
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

            $attributes = (array)$this->owner->{$this->attribute};
            if (empty($attributes) && in_array($this->owner->scenario, $this->initEmptyScenarios)) {
                $attributes[] = [];
            }

            foreach ($attributes as $modelAttributes) {
                $model = $this->createEmbedded(
                    $modelAttributes,
                    false,
                    ['formName' => $this->getFormName($this->storage->getNextIndex())]
                );
                $this->_storage[] = $model;
            }
        }
        return $this->_storage;
    }
}