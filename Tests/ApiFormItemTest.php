<?php

use Hanwoolderink\ApiForm\ApiForm\ApiFormItem;
use Hanwoolderink\ApiForm\ApiForm\ApiFormMisconfiguredException;
use PHPUnit\Framework\TestCase;

class ApiFormItemTest extends TestCase
{
    /**
     * @throws Exception
     */
    public function testCreate(): void
    {
        $item = new ApiFormItem('foo');
        $item
            ->setType(ApiFormItem::TYPE_STRING)
            ->setRequired(true)
            ->setChangeable(true)
            ->setUnique(true)
            ->setRegex('/^[a-zA-Z0-9_-]*$/')
            ->setRegexErrorMessage('needs to be url safe string')
            ->setMinLength(1)
            ->setMaxLength(10);

        $this->assertSame(ApiFormItem::TYPE_STRING, $item->getType(), 'type is incorrect');
        $this->assertSame(true, $item->isRequired(), 'type isRequired incorrect');
        $this->assertSame(true, $item->isChangeable(), 'type canChange incorrect');
        $this->assertSame(true, $item->isUnique(), 'type isUnique incorrect');
        $this->assertSame('/^[a-zA-Z0-9_-]*$/', $item->getRegex(), 'regex incorrect');
        $this->assertSame('needs to be url safe string', $item->getRegexErrorMessage(), 'regex message incorrect');
        $this->assertSame(1, $item->getMinLength(), 'min length incorrect');
        $this->assertSame(10, $item->getMaxLength(), 'max length  incorrect');
    }

    /**
     *
     */
    public function testSetNewName(): void
    {
        $item = new ApiFormItem('foo');
        $item->setName('Bar');

        $this->assertSame('Bar', $item->getName(), 'name is not updated or incorrect');
    }

    /**
     * @throws ApiFormMisconfiguredException
     */
    public function testSetWrongType(): void
    {
        $this->expectException(ApiFormMisconfiguredException::class);

        $item = new ApiFormItem('foo');
        $item->setType('bar');
    }

    /**
     * @throws ApiFormMisconfiguredException
     */
    public function testSetWrongRegex(): void
    {
        $this->expectException(ApiFormMisconfiguredException::class);

        $item = new ApiFormItem('foo');
        $item->setRegex('foobar');
    }
}
