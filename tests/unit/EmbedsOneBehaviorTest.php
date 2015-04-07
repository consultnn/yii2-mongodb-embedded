<?php
use consultnn\embedded\tests\models\MasterTestClass;
use consultnn\embedded\tests\models\SlaveEmbeddedClass;


class EmbedsOneBehaviorTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     *
     * @var Company
     */
    protected $company;

    protected function _before()
    {
        $this->company = new MasterTestClass();
    }
    
    
    /**
    * @dataProvider testSaveDataProvider
    */
    public function testSave($inputParams, $saveParams)
    {
        $this->company->setAttributes($inputParams);
        $this->company->load($saveParams, '');
        $this->company->save();
        $this->assertEquals($this->company->one->name, $saveParams['one']['name']);
        $this->assertEquals($this->company->_id, $saveParams['_id']);
        $this->assertEquals($this->company->many[0]->name, $saveParams['many'][0]['value']);
    }
    /**
     * @dataProvider testCompanyDataProvider
     */
    public function testSetValidateAttribute($arr, $isValidValue, $isValidName)
    {
        $this->company->setAttributes($arr);
        
        $this->company->setScenario('valueV');
        
        $this->assertEquals($this->company->validate(), $isValidValue);
        
        $this->company->setScenario('nameV');
        
        $this->assertEquals($this->company->validate(), $isValidName);
    }
    
    /**
     * @dataProvider testStorageDataProvider
     */
    public function testSetGetStorage($data, $condition, $insert, $insertCondition)
    {
        $this->company->setAttributes($data);
        $branch = new SlaveEmbeddedClass();
        $branch->name = $insert[0];
        $branch->value = $insert[1];
        $this->company->many->set($condition, $branch);
        $this->assertEquals($this->company->many->get($insertCondition)->value, $insert[1]);
        $this->assertEquals($this->company->many->get($insertCondition)->name, $insert[0]);
    }
    
    public function testEmptyScenario()
    {
        $obj = new MasterTestClass();
        $obj->setScenario('create');
        $this->assertEquals($obj->many->count(), 1);
        $obj2 = new MasterTestClass();
        $obj2->setScenario('update');
        $this->assertEquals($obj2->many->count(), 1);
    }
    
    public function testCompanyDataProvider()
    {
        return [
            [['_id' => 1, 'one' => ['name'=>1, 'value' => 1], 'many' => [['name'=>1, 'value' => 1],['name'=>1, 'value' => 1],['name'=>1, 'value' => 1],['name'=>1, 'value' => 1],['name'=>1, 'value' => 1],['name'=>1, 'value' => 1],['name'=>1, 'value' => 1]]],true,true],
            [['_id' => 2, 'one' => ['name'=>2, 'value' => 2], 'many' => [['name'=>2, 'value' => 2],['name'=>'foo', 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]],false,false],
            [['_id' => 3, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>'foo', 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]],false,false],
            [['_id' => 4, 'one' => ['name'=>4, 'value' => 4], 'many' => [['name'=>4, 'value' => 4],['name'=>'foo', 'value' => 4],['name'=>4, 'value' => 4],['name'=>4, 'value' => 4]]],false,false],
            [['_id' => 5, 'one' => ['name'=>5, 'value' => 5], 'many' => [['name'=>5, 'value' => 5],['name'=>'foo', 'value' => 5],['name'=>5, 'value' => 5]]],false,false],
            [['_id' => 6, 'one' => ['name'=>6, 'value' => 6], 'many' => [['name'=>6, 'value' => 6],['name'=>'foo', 'value' => 6]]],false,false],
            [['_id' => 7, 'one' => ['name'=>7, 'value' => 7], 'many' => [['name'=>7, 'value' => 7]]],false,true],
        ];
    }
    
    public function testSaveDataProvider()
    {
        return [
            [['_id' => 1, 'one' => ['name'=>1, 'value' => 1], 'many' => [['name'=>1, 'value' => 1],['name'=>1, 'value' => 1]]],['_id' => 1, 'one' => ['name'=>7, 'value' => 7], 'many' => [['name'=>7, 'value' => 7]]]],
            [['_id' => 2, 'one' => ['name'=>2, 'value' => 2], 'many' => [['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]],['_id' => 2, 'one' => ['name'=>6, 'value' => 6], 'many' => [['name'=>6, 'value' => 6],['name'=>6, 'value' => 6]]]],
            [['_id' => 3, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]],['_id' => 3, 'one' => ['name'=>5, 'value' => 5], 'many' => [['name'=>5, 'value' => 5],['name'=>5, 'value' => 5]]]],
            [['_id' => 4, 'one' => ['name'=>4, 'value' => 4], 'many' => [['name'=>4, 'value' => 4],['name'=>4, 'value' => 4]]],['_id' => 4, 'one' => ['name'=>4, 'value' => 4], 'many' => [['name'=>4, 'value' => 4],['name'=>4, 'value' => 4]]]],
            [['_id' => 5, 'one' => ['name'=>5, 'value' => 5], 'many' => [['name'=>5, 'value' => 5],['name'=>5, 'value' => 5]]],['_id' => 5, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]]],
            [['_id' => 6, 'one' => ['name'=>6, 'value' => 6], 'many' => [['name'=>6, 'value' => 6],['name'=>6, 'value' => 6]]],['_id' => 6, 'one' => ['name'=>2, 'value' => 2], 'many' => [['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]]],
            [['_id' => 7, 'one' => ['name'=>7, 'value' => 7], 'many' => [['name'=>7, 'value' => 7]]],['_id' => 7, 'one' => ['name'=>1, 'value' => 1], 'many' => [['name'=>1, 'value' => 1],['name'=>1, 'value' => 1]]]],
        ];
    }
    
    public function testStorageDataProvider()
    {
        return [
            
            [['_id' => 2, 'one' => ['name'=>3, 'value' => 2], 'many' => [['name'=>4, 'value' => 2],['name'=>'foo', 'value' => 2],['name'=>5, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]],['value', 2],[999, 'foo'], ['name', 999]],
            [['_id' => 3, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>'foo', 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]],['name', 'foo'],['abc', 'gfr'], ['value', 'gfr']],
        ];
    }
}