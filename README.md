Yii2 behaviors implement handling of mongodb embedded documents
===============================================================

* Add attribute with name starting with underscore to model.
~~~
/**
* @inheritdoc
*/
public function attributes()
{
    return [
        '_address',
    ]
}
~~~
* Add "safe" validation rule for attribute without underscore in name.
~~~
/**
 * @inheritdoc
 */
public function rules()
{
    return [
            [['address'], 'safe'],
        ]
}
~~~
* Add behavior with attribute name with underscore in name
~~~
'address' => [
    'class' => EmbedsOneBehavior::className(),
    'attribute' => '_address',
    'embedded' => Address::className()
],
~~~
* Your embedded documents must be inherited from [EmbeddedDocument](EmbeddedDocument.php) class.
~~~
class SlaveEmbeddedClass extends EmbeddedDocument 
{
    public $name;
    public $value;
    
    public function rules()
    {
        return [
            [['value'], 'boolean', 'on' => 'valueV'],
            [['name'], 'integer', 'on' => 'nameV'],
            [['name', 'value'], 'safe', 'on'=>'default']
        ];
    }
}
~~~
* To create empty embedded document set base document's scenario to the value listed in initEmptyScenarios parameter of EmbedsManyBehavior
~~~
'address' => [
    'class' => EmbedsOneBehavior::className(),
    'attribute' => '_address',
    'initEmptyScenarios' => ['create', 'update'],
    'embedded' => Address::className()
],
~~~
* Use attribute without underscore in form or view
~~~
echo $form->field($company->address, 'detail');
echo $form->field($company->address, 'id')->hiddenInput();

foreach($company->phones as $key => $phone) {
    echo $form->field($phone, 'number');
    echo $form->field($phone, 'type');
}
~~~
