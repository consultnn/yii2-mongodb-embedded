<?php

namespace consultnn\embedded;

use yii\helpers\Html;

/**
 * Class EmbeddedOneBehavior
 * @property EmbeddedDocument $storage
 * @package common\behaviors
 */
class EmbedsOneBehavior extends AbstractEmbeddedBehavior
{
    protected function setAttributes(array $attributes, $safeOnly = true)
    {
        $this->storage->scenario = $this->owner->scenario;
        $this->storage->setAttributes($attributes, $safeOnly);
    }

    /**
     * @return EmbeddedDocument
     */
    public function getStorage()
    {
        if (empty($this->_storage)) {
            $this->_storage = $this->createEmbedded((array)$this->owner->{$this->attribute});
            $this->_storage->formName = Html::getInputName($this->owner, $this->fakeAttribute);
        }
        return $this->_storage;
    }
}