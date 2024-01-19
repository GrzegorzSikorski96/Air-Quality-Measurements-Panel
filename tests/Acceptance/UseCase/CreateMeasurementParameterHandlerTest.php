<?php

declare(strict_types=1);

namespace App\Tests\Acceptance\UseCase;

use App\EventStorming\MeasurementParameterCreated\MeasurementParameterCreatedEvent;
use App\Infrastructure\Messenger\Command\CommandHandlerInterface;
use App\Tests\Asserts\InMemoryTransportAssert;
use App\Tests\Common\AcceptanceTestCase;
use App\UseCase\CreateMeasurementParameter\CreateMeasurementParameterCommand;
use App\UseCase\CreateMeasurementParameter\CreateMeasurementParameterHandler;
use PHPUnit\Framework\Assert;

final class CreateMeasurementParameterHandlerTest extends AcceptanceTestCase
{
    protected CommandHandlerInterface $handler;

    public function setUp(): void
    {
        parent::setUp();

        $handler = $this->container->get(CreateMeasurementParameterHandler::class);
        Assert::assertInstanceOf(CommandHandlerInterface::class, $handler);
        $this->handler = $handler;
    }

    /** @test */
    public function create_measurement_parameter_handler_test(): void
    {
        // given
        $givenCreateMeasurementParameterCommand = new CreateMeasurementParameterCommand(
            name: 'Given name',
            code: 'Given code',
            formula: 'Given formula'
        );

        // when
        $this->handler->__invoke($givenCreateMeasurementParameterCommand);

        // then
        InMemoryTransportAssert::assertAtLeastOneMessageInTypeWasSent(MeasurementParameterCreatedEvent::class, $this->asyncTransport);
        InMemoryTransportAssert::assertExactCountOfSentMessages(1, $this->asyncTransport);
    }
}