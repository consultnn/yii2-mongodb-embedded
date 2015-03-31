Yii2 behaviors implement handling of mongodb embedded documents
===============================================================

1. Add attribute to model. Attribute must started with underscore.
2. Add safe rule for attribute without underscore.
3. Attach behavior
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

4. Use attribute without underscore in form or view
~~~
echo $form->field($company->address, 'detail');
echo $form->field($company->address, 'id')->hiddenInput();

foreach($company->phones as $key => $phone) {
    echo $form->field($phone, 'number');
    echo $form->field($phone, 'type');
}
~~~