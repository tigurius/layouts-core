<?php

declare(strict_types=1);

namespace Netgen\BlockManager\Tests\Validator;

use Exception;
use Netgen\BlockManager\Tests\Validator\Stubs\ValidatorValue;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ValidatorTraitTest extends TestCase
{
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $validatorMock;

    /**
     * @var \Netgen\BlockManager\Tests\Validator\Stubs\ValidatorValue
     */
    private $validator;

    public function setUp(): void
    {
        $this->validatorMock = $this->createMock(ValidatorInterface::class);
        $this->validator = new ValidatorValue();
        $this->validator->setValidator($this->validatorMock);
    }

    /**
     * @covers \Netgen\BlockManager\Validator\ValidatorTrait::setValidator
     * @covers \Netgen\BlockManager\Validator\ValidatorTrait::validate
     */
    public function testValidate(): void
    {
        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with(
                $this->equalTo('some value'),
                $this->equalTo([new Constraints\NotBlank()])
            )
            ->will($this->returnValue(new ConstraintViolationList()));

        $this->validator->validate('some value', [new Constraints\NotBlank()]);
    }

    /**
     * @covers \Netgen\BlockManager\Validator\ValidatorTrait::validate
     * @expectedException \Netgen\BlockManager\Exception\Validation\ValidationException
     * @expectedExceptionMessage There was an error validating "value": Value should not be blank
     */
    public function testValidateThrowsValidationException(): void
    {
        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->with(
                $this->equalTo('some value'),
                $this->equalTo([new Constraints\NotBlank()])
            )->will(
                $this->returnValue(
                    new ConstraintViolationList(
                        [
                            $this->createConfiguredMock(
                                ConstraintViolationInterface::class,
                                ['getMessage' => 'Value should not be blank']
                            ),
                        ]
                    )
                )
            );

        $this->validator->validate('some value', [new Constraints\NotBlank()], 'value');
    }

    /**
     * @covers \Netgen\BlockManager\Validator\ValidatorTrait::validate
     * @expectedException \Netgen\BlockManager\Exception\Validation\ValidationException
     * @expectedExceptionMessage Test exception text
     */
    public function testValidateThrowsValidationExceptionOnOtherException(): void
    {
        $this->validatorMock
            ->expects($this->once())
            ->method('validate')
            ->will(
                $this->throwException(new Exception('Test exception text'))
            );

        $this->validator->validate('some value', [new Constraints\NotBlank()]);
    }
}
