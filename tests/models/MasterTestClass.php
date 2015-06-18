<?php

namespace consultnn\embedded\tests\models;

use yii\mongodb\ActiveRecord;
use consultnn\embedded\EmbedsOneBehavior;
use consultnn\embedded\EmbedsManyBehavior;
use consultnn\embedded\tests\models\SlaveEmbeddedClass;

class MasterTestClass extends ActiveRecord {
    
    public function behaviors()
    {
        return [
            'one' => [
                'class' => EmbedsOneBehavior::className(),
                'attribute' => '_one',
                'embedded' => SlaveEmbeddedClass::className()
                ],
            'many' => [
                'class' => EmbedsManyBehavior::className(),
                'attribute' => '_many',
                'initEmptyScenarios' => ['create', 'update'],
                'embedded' => SlaveEmbeddedClass::className()
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            '_id',
            '_one',
            '_many'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['_id', 'one', 'many'], 'safe', 'on' => ['default', 'requiredName', 'requiredValue']]
        ];
    }
}
