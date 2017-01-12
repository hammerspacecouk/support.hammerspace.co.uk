<?php

declare(strict_types = 1);
namespace SupportService\Data\Database\Mapper;

/**
 * Factory to create mappers as needed
 */
class MapperFactory
{
    public function createMapper(string $type): Mapper
    {
        $mapperMethod = 'create' . $type;
        if (!method_exists($this, $mapperMethod)) {
            throw new \InvalidArgumentException('Unexpected data type');
        }
        return $this->$mapperMethod();
    }

    public function createPayment(): PaymentMapper
    {
        return new PaymentMapper($this);
    }
}
