<?php

namespace Netgen\BlockManager\Tests\Validator\Structs;

use Netgen\BlockManager\API\Values\Block\BlockCreateStruct;
use Netgen\BlockManager\Parameters\CompoundParameterDefinition;
use Netgen\BlockManager\Parameters\ParameterDefinition;
use Netgen\BlockManager\Parameters\ParameterType;
use Netgen\BlockManager\Parameters\Registry\ParameterFilterRegistry;
use Netgen\BlockManager\Tests\Parameters\Stubs\ParameterDefinitionCollection;
use Netgen\BlockManager\Tests\Parameters\Stubs\ParameterFilter;
use Netgen\BlockManager\Tests\TestCase\ValidatorTestCase;
use Netgen\BlockManager\Validator\Constraint\Structs\ParameterStruct;
use Netgen\BlockManager\Validator\Structs\ParameterStructValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ParameterStructValidatorTest extends ValidatorTestCase
{
    public function setUp()
    {
        $compoundParameter = new CompoundParameterDefinition(
            [
                'name' => 'checkbox',
                'type' => new ParameterType\Compound\BooleanType(),
                'parameterDefinitions' => [
                    'param' => new ParameterDefinition(
                        [
                            'name' => 'param',
                            'type' => new ParameterType\IdentifierType(),
                            'isRequired' => true,
                        ]
                    ),
                ],
            ]
        );

        $this->constraint = new ParameterStruct(
            [
                'parameterDefinitions' => new ParameterDefinitionCollection(
                    [
                        'css_id' => new ParameterDefinition(
                            [
                                'name' => 'css_id',
                                'type' => new ParameterType\TextLineType(),
                                'isRequired' => true,
                            ]
                        ),
                        'checkbox' => $compoundParameter,
                    ]
                ),
                'allowMissingFields' => true,
            ]
        );

        parent::setUp();
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintValidator
     */
    public function getValidator()
    {
        $parameterFilterRegistry = new ParameterFilterRegistry();
        $parameterFilterRegistry->addParameterFilter('text_line', new ParameterFilter());

        return new ParameterStructValidator($parameterFilterRegistry);
    }

    /**
     * @param string $value
     * @param bool $required
     * @param bool $isValid
     *
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::__construct
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::buildConstraintFields
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::filterParameters
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::validate
     * @dataProvider validateDataProvider
     */
    public function testValidate($value, $required, $isValid)
    {
        $this->constraint->allowMissingFields = !$required;

        $this->assertValid(
            $isValid,
            new BlockCreateStruct(['parameterValues' => $value])
        );
    }

    /**
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\Validator\Constraint\Structs\ParameterStruct", "Symfony\Component\Validator\Constraints\NotBlank" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidConstraint()
    {
        $this->constraint = new NotBlank();
        $this->assertValid(true, new BlockCreateStruct());
    }

    /**
     * @covers \Netgen\BlockManager\Validator\Structs\ParameterStructValidator::validate
     * @expectedException \Symfony\Component\Validator\Exception\UnexpectedTypeException
     * @expectedExceptionMessage Expected argument of type "Netgen\BlockManager\API\Values\ParameterStruct", "integer" given
     */
    public function testValidateThrowsUnexpectedTypeExceptionWithInvalidValue()
    {
        $this->assertValid(true, 42);
    }

    public function validateDataProvider()
    {
        return [
            [['css_id' => 'ID', 'checkbox' => true, 'param' => 'value'], true, true],
            [['css_id' => '', 'checkbox' => true, 'param' => 'value'], true, false],
            [['css_id' => null, 'checkbox' => true, 'param' => 'value'], true, false],
            [['checkbox' => true, 'param' => 'value'], true, false],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => 'value'], false, true],
            [['css_id' => '', 'checkbox' => true, 'param' => 'value'], false, false],
            [['css_id' => null, 'checkbox' => true, 'param' => 'value'], false, false],
            [['checkbox' => true, 'param' => 'value'], false, true],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => 'value'], true, true],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => '?'], true, false],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => ''], true, false],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => null], true, true],
            [['css_id' => 'ID', 'checkbox' => true], true, true],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => 'value'], true, true],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => '?'], true, false],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => ''], true, false],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => null], true, true],
            [['css_id' => 'ID', 'checkbox' => false], true, true],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => 'value'], true, true],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => '?'], true, false],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => ''], true, false],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => null], true, true],
            [['css_id' => 'ID', 'checkbox' => null], true, true],
            [['css_id' => 'ID', 'param' => 'value'], true, true],
            [['css_id' => 'ID', 'param' => '?'], true, false],
            [['css_id' => 'ID', 'param' => ''], true, false],
            [['css_id' => 'ID', 'param' => null], true, true],
            [['css_id' => 'ID'], true, true],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => 'value'], false, true],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => '?'], false, false],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => ''], false, false],
            [['css_id' => 'ID', 'checkbox' => true, 'param' => null], false, true],
            [['css_id' => 'ID', 'checkbox' => true], false, true],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => 'value'], false, true],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => '?'], false, false],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => ''], false, false],
            [['css_id' => 'ID', 'checkbox' => false, 'param' => null], false, true],
            [['css_id' => 'ID', 'checkbox' => false], false, true],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => 'value'], false, true],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => '?'], false, false],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => ''], false, false],
            [['css_id' => 'ID', 'checkbox' => null, 'param' => null], false, true],
            [['css_id' => 'ID', 'checkbox' => null], false, true],
            [['css_id' => 'ID', 'param' => 'value'], false, true],
            [['css_id' => 'ID', 'param' => '?'], false, false],
            [['css_id' => 'ID', 'param' => ''], false, false],
            [['css_id' => 'ID', 'param' => null], false, true],
            [['css_id' => 'ID'], false, true],
        ];
    }
}
