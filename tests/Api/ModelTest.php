<?php

namespace Webmarketer\Tests\Api;

use PHPUnit\Framework\TestCase;

class ModelTest extends TestCase
{
    public function testModelCreateFromArray()
    {
        $stub_array = [
            '_id' => 'a1',
            'test_values' => [
                'value1',
                'value2'
            ],
        ];

        $model = PlaceholderModel::createFromArray($stub_array);

        $this->assertInstanceOf(PlaceholderModel::class, $model);
        $this->assertEquals($model->_id, $stub_array['_id']);
        $this->assertIsArray($model->test_values);
        $this->assertEquals($model->test_values, $stub_array['test_values']);
    }

    public function testModelCreateFromObject()
    {
        $stub_object = (object) [
            '_id' => 'a1',
            'test_values' => [
                'value1',
                'value2'
            ],
        ];

        $model = PlaceholderModel::createFromArray($stub_object);

        $this->assertInstanceOf(PlaceholderModel::class, $model);
        $this->assertEquals($model->_id, $stub_object->_id);
        $this->assertIsArray($model->test_values);
        $this->assertEquals($model->test_values, $stub_object->test_values);
    }
}