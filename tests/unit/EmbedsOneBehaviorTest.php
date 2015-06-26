<?php
use consultnn\embedded\tests\models\MasterTestClass;
use consultnn\embedded\tests\models\SlaveEmbeddedClass;


class EmbedsTest extends \Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    /**
     *
     * @var MasterTestClass
     */
    protected $company;

    protected function _before()
    {
        $this->company = new MasterTestClass();
        $this->company->deleteAll();
    }

    public function testSaveNotChanged()
    {
        $data = ['_id' => 1,
            '_one' => ['name'=>1, 'value' => 1],
            '_many' => [
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1],
                ['name'=>1, 'value' => 1]
            ]
        ];
        $this->company->setAttributes($data, false);
        $this->company->save();
        $this->assertEquals($this->company->toArray(), $data);
        $this->company->save();
        $this->assertEquals($this->company->toArray(), $data);
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
        $this->company->setScenario('requiredValue');
        $this->assertEquals($this->company->validate(), $isValidValue);
        $this->company->setScenario('requiredName');
        $this->assertEquals($this->company->validate(), $isValidName);
    }
    
    /**
     * @dataProvider testStorageDataProvider
     */
    public function testSetGetStorage($data, $condition, $insert, $insertCondition)
    {
        $this->company->setAttributes($data);
        $branch = new SlaveEmbeddedClass();
        $branch->setAttributes(['name' => $insert[0], 'value' => $insert[1]]);
        $this->company->many->set($condition, $branch);
        $this->assertEquals($this->company->many->get($insertCondition)->name, $insert[0]);
        $this->assertEquals($this->company->many->get($insertCondition)->value, $insert[1]);
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
            [
                'data' => ['_id' => 1,
                    'one' => ['name'=>1, 'value' => 1],
                    'many' => [
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1],
                        ['name'=>1, 'value' => 1]
                    ]
                ],
                'isValidValue' => true,
                'isValidName' => true
            ],
            [
                'data' => ['_id' => 2,
                    'one' => ['name'=>2, 'value' => 547],
                    'many' => [
                        ['name'=>2, 'value' => false],
                        ['name'=>2, 'value' => true],
                        ['name'=>2, 'value' => true],
                        ['name'=>2, 'value' => false],
                        ['name'=>2, 'value' => false],
                        ['name'=>2, 'value' => false]
                    ]
                ],
                'isValidValue' => false,
                'isValidName' => true
            ],
            [
                'data' => ['_id' => 3,
                    'one' => ['name'=>3, 'value' => false],
                    'many' => [
                        ['name'=>3, 'value' => false],
                        ['name'=>'foo', 'value' => false],
                        ['name'=>3, 'value' => true],
                        ['name'=>3, 'value' => false],
                        ['name'=>3, 'value' => true]
                    ]
                ],
                'isValidValue' => true,
                'isValidName' => false
            ],
            [
                'data' => ['_id' => 4,
                    'one' => ['name'=>4, 'value' => 4],
                    'many' => [
                        ['name'=>4, 'value' => 4],
                        ['name'=>'foo', 'value' => 4],
                        ['name'=>4, 'value' => 4],
                        ['name'=>4, 'value' => 4]
                    ]
                ],
                'isValidValue' => false,
                'isValidName' => false
            ],
        ];
    }
    
    public function testSaveDataProvider()
    {
        return [
            [
                'baseData' => ['_id' => 1, 'one' => ['name'=>1, 'value' => 1], 'many' => [['name'=>1, 'value' => 1],['name'=>1, 'value' => 1]]],
                'saveData' => ['_id' => 1, 'one' => ['name'=>7, 'value' => 7], 'many' => [['name'=>7, 'value' => 7]]]
            ],
            [
                'baseData' => ['_id' => 2, 'one' => ['name'=>2, 'value' => 2], 'many' => [['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]],
                'saveData' => ['_id' => 2, 'one' => ['name'=>6, 'value' => 6], 'many' => [['name'=>6, 'value' => 6],['name'=>6, 'value' => 6]]]
            ],
            [
                'baseData' => ['_id' => 3, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]],
                'saveData' => ['_id' => 3, 'one' => ['name'=>5, 'value' => 5], 'many' => [['name'=>5, 'value' => 5],['name'=>5, 'value' => 5]]]
            ],
            [
                'baseData' => ['_id' => 4, 'one' => ['name'=>4, 'value' => 4], 'many' => [['name'=>4, 'value' => 4],['name'=>4, 'value' => 4]]],
                'saveData' => ['_id' => 4, 'one' => ['name'=>4, 'value' => 4], 'many' => [['name'=>4, 'value' => 4],['name'=>4, 'value' => 4]]]
            ],
            [
                'baseData' => ['_id' => 5, 'one' => ['name'=>5, 'value' => 5], 'many' => [['name'=>5, 'value' => 5],['name'=>5, 'value' => 5]]],
                'saveData' => ['_id' => 5, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]]
            ],
            [
                'baseData' => ['_id' => 6, 'one' => ['name'=>6, 'value' => 6], 'many' => [['name'=>6, 'value' => 6],['name'=>6, 'value' => 6]]],
                'saveData' => ['_id' => 6, 'one' => ['name'=>2, 'value' => 2], 'many' => [['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]]
            ],
            [
                'baseData' => ['_id' => 7, 'one' => ['name'=>7, 'value' => 7], 'many' => [['name'=>7, 'value' => 7]]],
                'saveData' => ['_id' => 7, 'one' => ['name'=>1, 'value' => 1], 'many' => [['name'=>1, 'value' => 1],['name'=>1, 'value' => 1]]]
            ],
        ];
    }
    
    public function testStorageDataProvider()
    {
        return [
            
            [
                'initData' => ['_id' => 2, 'one' => ['name'=>3, 'value' => 2], 'many' => [['name'=>4, 'value' => 2],['name'=>'foo', 'value' => 2],['name'=>5, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2],['name'=>2, 'value' => 2]]],
                'insertCondition' => ['value', 2],
                'insertData' => [999, 'foo'],
                'selectData' => ['name', 999]
            ],
            [
                'initData' => ['_id' => 3, 'one' => ['name'=>3, 'value' => 3], 'many' => [['name'=>3, 'value' => 3],['name'=>'foo', 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3],['name'=>3, 'value' => 3]]],
                'insertCondition' => ['name', 'foo'],
                'insertData' => ['abc', 'gfr'],
                'selectData' => ['value', 'gfr']
            ],
        ];
    }
}