<?php


namespace App\Tests\Validator;


use App\Validator\NotSunday;
use App\Validator\NotSundayValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class NotSundayValidatorTest extends  ConstraintValidatorTestCase
{

    protected function createValidator()
    {
        return new NotSundayValidator();
    }


    /**
     * @dataProvider getValidValues
     */
    public function testValidValues($value)
    {
        $this->validator->validate($value, new NotSunday());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getInvalidValues
     */
    public function testInvalidValues($value)
    {
        $constraint = new NotSunday();
        $this->validator->validate($value, $constraint);

        $this->buildViolation($constraint->message)
            ->assertRaised();
    }

    public function getInvalidValues()
    {
        return [
            [new \DateTime('sunday')],
        ];
    }

    public function getValidValues()
    {
        return [
            [new \DateTime('monday')],
            ['Invalid'],
            [new \DateTime('tuesday')]
        ];
    }
}