<?php

// ... an event took place on my website

/** @var array $payload event payload */
$payload = [
    'firstname' => 'John',
    'lastname' => 'DOE',
    'email' => 'john.doe@webmarketer.io',
    'phone' => '+33612345678'
];
/** @var string $user_tracker_id tracker_id of the user that made the event */
$user_tracker_id = 'trackerabc123';

try {
    // use a Service Account to authenticate against Webmarketer API (@see https://github.com/webmarketer-saas/php-webmarketer-sdk?tab=readme-ov-file#authentication)
    $sa_auth_provider = new \Webmarketer\Auth\ServiceAccountAuthProvider([
        'credential' => '{ ...serviceAccount }',
        'scopes' => 'full_access'
    ]);

    $client = new \Webmarketer\WebmarketerSdk(
        [
            'default_project_id' => 'my-awesome-project'
        ],
        $sa_auth_provider
    );

    // create the Webmarketer Event payload with data
    $event = \Webmarketer\Api\Project\Events\EventPayload::createFromArray([
        'nonce' => 'unique-random-key',
        'eventType' => 'contact-form', // eventType must match the Webmarketer eventType on your project
        'trackerId' => $user_tracker_id,
        'data' => $payload // ensure data is an associative array and is json serializable (API could throw an error if event data does not match registered eventType schema)
    ]);

    // call the API with the event payload to create the event
    $client->getEventService()->create($event);
    // 🎉 your event is sent to Webmarketer
} catch (\Webmarketer\Exception\DependencyException $dep_ex) {
    // SDK init throw a dependency exception if requirements are not meet (see Install)

    // TODO: handle error
} catch (\Webmarketer\Exception\CredentialException $cred_ex) {
    // SDK automatically try to authenticate you against API
    // A credential exception could be throw if credentials are invalid

    // TODO: handle error
} catch (\Webmarketer\Exception\BadRequestException $bad_req_ex) {
    // API could throw a Bad Request exception if event data are invalid

    // TODO: handle error, maybe fix the event configuration or implementation
}
