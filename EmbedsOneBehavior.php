<?php

namespace consultnn\embedded;

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
            $this->_storage = new $this->embeddedClass;
            $this->_storage->formName = $this->getFormName();
        }
        return $this->_storage;
    }
}