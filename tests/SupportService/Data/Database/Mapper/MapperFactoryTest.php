<?php

declare(strict_types = 1);
namespace Tests\SupportService\Data\Database\Mapper;

use SupportService\Data\Database\Mapper\MapperFactory;

class MapperFactoryTest extends \PHPUnit_Framework_TestCase
{
    private const MAPPER_NS = '\\SupportService\\Data\\Database\\Mapper\\';

    /**
     * @dataProvider mapperNamesDataProvider
     * @param string $mapperName
     */
    public function testGetters($mapperName)
    {
        $mapperFactory = new MapperFactory();
        $mapper = $mapperFactory->{'create' . $mapperName}();
        // Assert it returns an instance of the correct class
        $this->assertInstanceOf(self::MAPPER_NS . $mapperName . 'Mapper', $mapper);
        // Requesting the same mapper multiple times reuses the same instance of
        // a mapper, rather than creating a new one every time
        $this->assertSame($mapper, $mapperFactory->{'create' . $mapperName}());

        // check the generic getter also gets it
        $this->assertSame($mapper, $mapperFactory->createMapper($mapperName));
    }
    public function mapperNamesDataProvider()
    {
        return [
            ['Payment'],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidMapper()
    {
        $mapperFactory = new MapperFactory();
        $mapperFactory->createMapper('NotReal');
    }
}
