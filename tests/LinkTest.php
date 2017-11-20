<?php

use PHPUnit\Framework\TestCase;

class LinkTest extends TestCase
{

    protected $data;

    public function __construct()
    {
        parent::__construct();

        $this->data =  (object) [
            'alias' => 'alias'
        ];

    }

    public function testCanBeCreatedFromValidObject()
    {
        $this->assertInstanceOf(
            \Iivannov\Branchio\Link::class,
            new \Iivannov\Branchio\Link($this->data)
        );
    }

    public function testCannotBeCreatedFromValidObject()
    {
        $this->expectException(\InvalidArgumentException::class);

        new \Iivannov\Branchio\Link('string');
    }

}

