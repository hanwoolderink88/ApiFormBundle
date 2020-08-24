<?php

use Hanwoolderink\ApiForm\ApiForm\ApiFormItem;
use Hanwoolderink\ApiForm\ApiForm\ApiFormItemValidator;
use PHPUnit\Framework\TestCase;

class ApiFormItemValidatorTest extends TestCase
{
    public function testRequiredError()
    {
        $item = new ApiFormItem('foo');
        $item->setRequired(true);

        $validator = new ApiFormItemValidator($item, null, true);
        $validator->isValid();

        $this->assertEquals(count($validator->getErrors()), 1, true);
    }

    public function testCanChangeError()
    {
        $item = new ApiFormItem('foo');
        $item->setChangeable(false);

        $validator = new ApiFormItemValidator($item, '1', false);
        $validator->isValid();

        $this->assertEquals(count($validator->getErrors()), 1, true);
    }

    public function testTypeStringPassword()
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_PASSWORD);

        $value = 'Bar';
        $isNew = false;
        $validator = new ApiFormItemValidator($item, $value, $isNew);
        $validatorInvalid = new ApiFormItemValidator($item, 1, $isNew);

        $this->assertTrue($validator->isValid(), 'Type string should give no errors but does');
        $this->assertFalse($validatorInvalid->isValid(), 'Type incorrect check failed for type string');

        // min length (should be invalid)
        $item->setMinLength(5);
        $this->assertFalse($validator->isValid(), 'min length test failed for type string');

        // max length (should be invalid)
        $item->setMinLength(null); // reset
        $item->setMaxLength(1);
        $this->assertFalse($validator->isValid(), 'max length test failed for type string');

        // regex check (should be invalid)
        $item->setMaxLength(null);
        $item->setRegex('/^[a-z]+$/');
        $this->assertFalse($validator->isValid(), 'regex test failed for type string');
    }
}
