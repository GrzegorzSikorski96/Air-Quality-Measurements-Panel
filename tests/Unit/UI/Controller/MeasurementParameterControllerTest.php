<?php

declare(strict_types=1);

namespace App\Tests\Unit\UI\Controller;

use App\Tests\Common\UnitTestCase;
use App\Tests\Fixtures\Entity\MeasurementParameterBuilder;
use PHPUnit\Framework\Assert;
use Symfony\Component\Uid\Uuid;

final class MeasurementParameterControllerTest extends UnitTestCase
{    
    /** @test */
    public function all_measurement_parameters()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()->build();
        $this->measurementParameterRepository->save($givenSecondMeasurementParameter);

        // when
        $content = $this->selfRequest('GET', '/measurementParameters')->getContent();

        // then
        Assert::isJson($content);
        $content = json_decode($content);

        foreach($content as $measurementParameter) {
            if ($measurementParameter->id === $givenFirstMeasurementParameter->getId()->toRfc4122()) {
                $expectedMeasurementParameter = $givenFirstMeasurementParameter;
            } else if ($measurementParameter->id === $givenSecondMeasurementParameter->getId()->toRfc4122()) {
                $expectedMeasurementParameter = $givenSecondMeasurementParameter;}

            Assert::assertEquals($expectedMeasurementParameter->getId(), $measurementParameter->id);
            Assert::assertEquals($expectedMeasurementParameter->getName(), $measurementParameter->name);
            Assert::assertEquals($expectedMeasurementParameter->getCode(), $measurementParameter->code);
            Assert::assertEquals($expectedMeasurementParameter->getFormula(), $measurementParameter->formula);
        }
    }
    
    /** @test */
    public function measurement_parameter()
    {
        // given
        $givenFirstMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::fromString('43192d2a-724e-4e43-b5bd-ec0588b38c53'))
        ->build();
        $this->measurementParameterRepository->save($givenFirstMeasurementParameter);

        $givenSecondMeasurementParameter = MeasurementParameterBuilder::any()
        ->withId(Uuid::v4())
        ->build();
        $this->measurementParameterRepository->save($givenSecondMeasurementParameter);

        // when
        $content = $this->selfRequest('GET', '/measurementParameter/43192d2a-724e-4e43-b5bd-ec0588b38c53')->getContent();

        // then
        Assert::isJson($content);
        $content = json_decode($content);

        Assert::assertEquals($givenFirstMeasurementParameter->getId(), $content->id);
        Assert::assertEquals($givenFirstMeasurementParameter->getName(), $content->name);
        Assert::assertEquals($givenFirstMeasurementParameter->getCode(), $content->code);
        Assert::assertEquals($givenFirstMeasurementParameter->getFormula(), $content->formula);
    }
}