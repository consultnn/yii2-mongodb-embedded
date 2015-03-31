<?php

namespace consultnn\embedded;


use yii\base\Model;

/**
 * Class EmbeddedDocument
 * @package consultnn\embedded
 */
class EmbeddedDocument extends Model
{
    /**
     * @var string
     */
    private $_formName;

    /**
     * @inheritdoc
     */
    public function formName()
    {
        if (!empty($this->_formName)) {
            return $this->_formName;
        } else {
            return parent::formName();
        }
    }

    /**
     * @param $formName
     */
    public function setFormName($formName)
    {
        $this->_formName = $formName;
    }
}