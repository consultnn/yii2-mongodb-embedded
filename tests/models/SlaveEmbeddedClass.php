<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace consultnn\embedded\tests\models;
 
use consultnn\embedded\EmbeddedDocument;
/**
 * Description of Branches
 *
 * @author panik
 */
class SlaveEmbeddedClass extends EmbeddedDocument 
{
    public $name;
    public $value;
    
    public function rules()
    {
        return [
            ['value', 'boolean', 'on' => 'requiredValue'],
            ['name', 'integer', 'on' => 'requiredName'],
            [['name', 'value'], 'safe', 'on'=>'default']
        ];
    }
}
