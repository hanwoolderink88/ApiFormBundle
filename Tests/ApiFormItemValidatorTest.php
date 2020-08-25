<?php

use Hanwoolderink\ApiForm\ApiForm\ApiFormItem;
use Hanwoolderink\ApiForm\ApiForm\ApiFormItemValidator;
use PHPUnit\Framework\TestCase;

class ApiFormItemValidatorTest extends TestCase
{
    public function testRequiredError(): void
    {
        $item = new ApiFormItem('foo');
        $item->setRequired(true);

        $validator = new ApiFormItemValidator($item, null, true);
        $validator->isValid();

        $this->assertEquals(count($validator->getErrors()), 1, 'Item is required has failed');
    }

    public function testCanChangeError(): void
    {
        $item = new ApiFormItem('foo');
        $item->setChangeable(false);

        $validator = new ApiFormItemValidator($item, '1', false);
        $validator->isValid();

        $this->assertEquals(count($validator->getErrors()), 1, 'Item is changeable has failed');
    }

    public function testTypeStringPassword(): void
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

    public function testTypeInt(): void
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_INT);

        $validator = new ApiFormItemValidator($item, 'Foo', true);

        $this->assertFalse($validator->isValid(), 'Type int typecheck is not working');
    }

    public function testTypeFloat(): void
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_FLOAT);

        $validator = new ApiFormItemValidator($item, 1, true);

        $this->assertFalse($validator->isValid(), 'Type int typecheck is not working');
    }

    public function testTypeArray(): void
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_ARRAY);

        $validator = new ApiFormItemValidator($item, ['foo', 'bar'], true);
        $validatorInvalid = new ApiFormItemValidator($item, 'foobar', true);

        $this->assertTrue($validator->isValid(), 'Type array typecheck is not working');
        $this->assertFalse($validatorInvalid->isValid(), 'Type array typecheck is not working');

        // min length (should be invalid)
        $item->setMinLength(3);
        $this->assertFalse($validator->isValid(), 'min length test failed for type array');

        // max length (should be invalid)
        $item->setMinLength(null); // reset
        $item->setMaxLength(1);
        $this->assertFalse($validator->isValid(), 'max length test failed for type array');
    }

    public function testTypeDate(): void
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_DATE);

        $validator = new ApiFormItemValidator($item, '2020-01-33', true);

        $this->assertFalse($validator->isValid(), 'Type Date typecheck is not working');
    }

    public function testTypeDatetime(): void
    {
        $item = new ApiFormItem('foo');
        $item->setType(ApiFormItem::TYPE_DATETIME);

        $validator = new ApiFormItemValidator($item, '2020-01-01T21:11', true);
        $validatorInvalid = new ApiFormItemValidator($item, '2020-33-01T26:11', true);

        $this->assertTrue($validator->isValid(), 'Type Date typecheck is not working');
        $this->assertFalse($validatorInvalid->isValid(), 'Type Date typecheck is not working');
    }
}
