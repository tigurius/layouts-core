<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Parameters\ParameterType;

use DateTimeImmutable;
use DateTimeZone;
use Netgen\BlockManager\Parameters\ParameterType\DateTimeType;
use Netgen\BlockManager\Tests\TestCase\ValidatorFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

final class DateTimeTypeTest extends TestCase
{
    use ParameterTypeTestTrait;

    public function setUp(): void
    {
        $this->type = new DateTimeType();
    }

    /**
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::getIdentifier
     */
    public function testGetIdentifier(): void
    {
        $this->assertEquals('datetime', $this->type->getIdentifier());
    }

    /**
     * @param mixed $value
     * @param bool $isEmpty
     *
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::isValueEmpty
     * @dataProvider emptyProvider
     */
    public function testIsValueEmpty($value, bool $isEmpty): void
    {
        $this->assertEquals($isEmpty, $this->type->isValueEmpty($this->getParameterDefinition(), $value));
    }

    public function emptyProvider(): array
    {
        return [
            [null, true],
            [new DateTimeImmutable(), false],
            [new DateTimeImmutable(), false],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), false],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), false],
        ];
    }

    /**
     * @param mixed $value
     * @param mixed $convertedValue
     *
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::toHash
     * @dataProvider toHashProvider
     */
    public function testToHash($value, $convertedValue): void
    {
        $this->assertEquals($convertedValue, $this->type->toHash($this->getParameterDefinition(), $value));
    }

    public function toHashProvider(): array
    {
        return [
            [42, null],
            [null, null],
            [[], null],
            [['datetime' => '2018-02-01 00:00:00'], null],
            [['timezone' => 'Antarctica/Casey'], null],
            [['datetime' => '2018-02-01 00:00:00', 'timezone' => ''], null],
            [['datetime' => '', 'timezone' => 'Antarctica/Casey'], null],
            [['datetime' => '', 'timezone' => ''], null],
            [['datetime' => '2018-02-01 15:00:00', 'timezone' => 'Antarctica/Casey'], null],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), ['datetime' => '2018-02-01 15:00:00.000000', 'timezone' => 'Antarctica/Casey']],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), ['datetime' => '2018-02-01 15:00:00.000000', 'timezone' => 'Antarctica/Casey']],
        ];
    }

    /**
     * @param mixed $value
     * @param mixed $convertedValue
     *
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::fromHash
     * @dataProvider fromHashProvider
     */
    public function testFromHash($value, $convertedValue): void
    {
        $this->assertEquals($convertedValue, $this->type->fromHash($this->getParameterDefinition(), $value));
    }

    public function fromHashProvider(): array
    {
        return [
            [null, null],
            [[], null],
            [['datetime' => '2018-02-01 00:00:00'], null],
            [['timezone' => 'Antarctica/Casey'], null],
            [['datetime' => '2018-02-01 00:00:00', 'timezone' => ''], null],
            [['datetime' => '', 'timezone' => 'Antarctica/Casey'], null],
            [['datetime' => '', 'timezone' => ''], null],
            [['datetime' => '2018-02-01 15:00:00.000000', 'timezone' => 'Antarctica/Casey'], new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey'))],
        ];
    }

    /**
     * @param mixed $value
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::getRequiredConstraints
     * @covers \Netgen\BlockManager\Parameters\ParameterType\DateTimeType::getValueConstraints
     * @dataProvider validationProvider
     */
    public function testValidation($value, bool $isValid): void
    {
        $parameter = $this->getParameterDefinition();
        $validator = Validation::createValidatorBuilder()
            ->setConstraintValidatorFactory(new ValidatorFactory($this))
            ->getValidator();

        $errors = $validator->validate($value, $this->type->getConstraints($parameter, $value));
        $this->assertEquals($isValid, $errors->count() === 0);
    }

    public function validationProvider(): array
    {
        return [
            [null, true],
            [new DateTimeImmutable(), true],
            [new DateTimeImmutable(), true],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), true],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('Antarctica/Casey')), true],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('+01:00')), false],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('+01:00')), false],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('CAST')), false],
            [new DateTimeImmutable('2018-02-01 15:00:00', new DateTimeZone('CAST')), false],
        ];
    }
}
