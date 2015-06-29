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
        '_phones',
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
            [['address', 'phones'], 'safe'],
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
'phones' => [
    'class' => EmbedsManyBehavior::className(),
    'attribute' => '_phones',
    'embedded' => Phone::className()
],
~~~
* Your embedded documents must be inherited from [EmbeddedDocument](EmbeddedDocument.php) class.
~~~
class Phone extends EmbeddedDocument 
{
    public $number;
    public $type;
    
    public function rules()
    {
        return [
            [['number', 'type'], 'string'],
            [['type'], 'in', 'range' => ['home', 'work']],
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
// Address
echo $form->field($company->address, 'detail');
echo $form->field($company->address, 'id')->hiddenInput();

// Phones
foreach($company->phones as $key => $phone) {
    echo $form->field($phone, 'number');
    echo $form->field($phone, 'type');
}
~~~
