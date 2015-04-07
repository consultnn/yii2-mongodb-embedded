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
                'embeddedClass' => SlaveEmbeddedClass::className()
                ],
            'many' => [
                'class' => EmbedsManyBehavior::className(),
                'attribute' => '_many',
                'initEmptyScenarios' => ['create', 'update'],
                'embeddedClass' => SlaveEmbeddedClass::className()
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
            [['_id', 'one', 'many'], 'safe']
        ];
    }
    
    public function scenarios() 
    {
        $scenarios = parent::scenarios();
        $scenarios['nameV'] = ['name'];
        $scenarios['valueV'] =  ['value'];
        return $scenarios;
    }
}
