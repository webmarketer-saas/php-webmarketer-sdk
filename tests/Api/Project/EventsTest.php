<?php

namespace Webmarketer\Tests\Api\Project;

use PHPUnit\Framework\TestCase;
use Webmarketer\Api\Project\EventTypes\EventType;
use Webmarketer\Api\Project\EventTypes\EventTypeClassicField;
use Webmarketer\Api\Project\EventTypes\EventTypeProjectField;
use Webmarketer\Api\Project\EventTypes\EventTypeService;
use Webmarketer\Exception\EndpointNotFoundException;
use Webmarketer\WebmarketerSdk;

class EventsTest extends TestCase
{
    const TEST_EVENT_TYPE_NAME = 'Test SDK';
    const TEST_EVENT_TYPE_REF = 'test-sdk';

    public function testGetAllProjectEventTypes()
    {
        $event_service = $this->getEventTypeService();
        $res = $event_service->getAll([
            "limit" => 1
        ]);

        $count = 0;
        foreach ($res->paginationIterator() as $el) {
            $count++;
        }
        $this->assertEquals($count, $res->getTotal());
    }

    public function testCreateProjectEventType()
    {
        $this->expectNotToPerformAssertions();

        $event_type = EventType::createFromArray([
            'name' => self::TEST_EVENT_TYPE_NAME,
            'reference' => self::TEST_EVENT_TYPE_REF,
            'fields' => [
                EventTypeClassicField::createFromArray([
                    'type' => 'string',
                    'key' => 'my-field',
                    'label' => 'My Field',
                    'optional' => false,
                ]),
                EventTypeProjectField::createFromArray([
                    'fieldId' => '5ee0984bcf96e900f912896e',
                    'optional' => false,
                ]),
                EventTypeProjectField::createFromArray([
                    'fieldId' => '5ee0984bcf96e900f912896d',
                    'key' => 'Override Email',
                    'optional' => true
                ])
            ],
        ]);

        $this->getEventTypeService()
            ->create($event_type);
    }

    /**
     * @depends testCreateProjectEventType
     */
    public function testGetProjectEventTypeByReference()
    {
        $event_service = $this->getEventTypeService();
        $event_type = $event_service->getByReference(self::TEST_EVENT_TYPE_REF);
        $this->assertTrue($event_type->name === self::TEST_EVENT_TYPE_NAME);
    }

    /**
     * @depends testGetProjectEventTypeByReference
     */
    public function testDeleteProjectEventType()
    {
        $this->expectException(EndpointNotFoundException::class);
        $event_service = $this->getEventTypeService();
        $event_service->deleteByReference(self::TEST_EVENT_TYPE_REF);
        $event_service->getByReference(self::TEST_EVENT_TYPE_REF);
    }

    /**
     * @return EventTypeService
     */
    private function getEventTypeService()
    {
        $sdk = new WebmarketerSdk([
            'default_project_id' => 'my-awesome-project'
        ]);
        return $sdk->getEventTypeService();
    }
}
