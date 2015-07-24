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
    protected function setAttributes($attributes, $safeOnly = true)
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
            $this->_storage = $this->createEmbedded(
                (array)$this->owner->{$this->attribute},
                false,
                ['formName' => $this->setFormName ? Html::getInputName($this->owner, $this->fakeAttribute) : null]
            );
        }
        return $this->_storage;
    }
}