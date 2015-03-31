Yii2 behaviors implement handling of mongodb embedded documents
===============================================================

* Add attribute to model. Attribute must started with underscore.
* Add safe rule for attribute without underscore.
* Attach behavior
~~~
'address' => [
    'class' => EmbedsOneBehavior::className(),
    'attribute' => '_address',
    'embeddedClass' => Address::className()
],
'phones' => [
    'class' => EmbedsManyBehavior::className(),
    'attribute' => '_phones',
    'embeddedClass' => Phone::className()
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