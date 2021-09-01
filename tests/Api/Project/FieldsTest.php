<?php

namespace Webmarketer\Tests\Api\Project;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\Project\Fields\FieldService;
use Webmarketer\Exception\DependencyException;
use Webmarketer\WebmarketerSdk;

class FieldsTest extends TestCase
{
    public function testGetAllFields()
    {
        $field_service = $this->getSdkFieldService();
        $fields = $field_service->getAll();
        $this->assertTrue(is_array($fields));
    }

    /**
     * @return FieldService
     * @throws DependencyException
     */
    private function getSdkFieldService()
    {
        $sdk = new WebmarketerSdk([
            'default_project_id' => 'my-awesome-project'
        ]);
        return $sdk->getFieldService();
    }
}
