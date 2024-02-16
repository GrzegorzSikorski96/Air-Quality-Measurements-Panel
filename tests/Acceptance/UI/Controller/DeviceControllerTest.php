<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UI\Controller;

use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;
use App\Tests\Asserts\UuidAssert;
use App\Tests\Common\AcceptanceTestCase;
use App\Tests\Fixtures\Entity\DeviceBuilder;
use Symfony\Component\HttpFoundation\Response;

final class DeviceControllerTest extends AcceptanceTestCase
{
    /** @test */
    public function all_devices()
    {
        // given
        $givenFirstDevice = DeviceBuilder::any()->build();
        $this->handleCreateDevice($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()->build();
        $this->handleCreateDevice($givenSecondDevice);
        
        // when
        $response = $this->selfRequest('GET', '/devices');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $devices = json_decode($response->getContent());
        
        foreach($devices as $device) {
            Assert::assertObjectHasProperty('id', $device);
            UuidAssert::assertUuid($device->id);

            Assert::assertObjectHasProperty('name', $device);
            Assert::assertObjectHasProperty('latitude', $device);
            Assert::assertObjectHasProperty('longitude', $device);
            Assert::assertObjectHasProperty('provider', $device);
        }
    }

    /** @test */
    public function device()
    {
        // given
        $givenFirstDevice = DeviceBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->handleCreateDevice($givenFirstDevice);

        $givenSecondDevice = DeviceBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->handleCreateDevice($givenSecondDevice);

        // when
        $response = $this->selfRequest('GET', '/device/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_OK, $response->getStatusCode());
        Assert::isJson($response->getContent());
        $device = json_decode($response->getContent());

        Assert::assertObjectHasProperty('id', $device);
        UuidAssert::assertUuid($device->id);

        Assert::assertObjectHasProperty('name', $device);
        Assert::assertObjectHasProperty('latitude', $device);
        Assert::assertObjectHasProperty('longitude', $device);
        Assert::assertObjectHasProperty('provider', $device);
    }

    /** @test */
    public function not_existing_device()
    {
        // given

        // when
        $response = $this->selfRequest('GET', '/device/43192d2a-724e-4e43-b5bd-ec0588b38c53');

        // then
        Assert::assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}