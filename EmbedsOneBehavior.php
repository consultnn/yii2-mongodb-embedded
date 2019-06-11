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
    public $dirtyAttributesFriendly = false;

    protected function setAttributes($attributes, $safeOnly = true)
    {
        $this->storage->scenario = $this->owner->scenario;
        $this->storage->setAttributes($attributes, $safeOnly);
    }

    /**
     * @inheritdoc
     */
    protected function getAttributes()
    {
        if ($this->saveEmpty || !$this->storage->isEmpty()) {
            if ($this->dirtyAttributesFriendly) {
                $values = $this->storage->attributes;
                ksort($values);
                return $values;
            }

            return $this->storage->attributes;
        } else {
            return null;
        }
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

    public function proxy($event)
    {
        parent::proxy($event);

        if ($this->dirtyAttributesFriendly) {
            $values = $this->owner->getOldAttribute($this->attribute);
            ksort($values);
            $this->owner->setOldAttribute($this->attribute, $values);
        }
    }
}
