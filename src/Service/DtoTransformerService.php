<?php

namespace App\Service;

use App\Interface\DtoInterface;
use App\Interface\RepresentationInterface;
use App\Interface\ResourceInterface;
use Exception;
use ReflectionClass;

/**
 * Transforming objects to objects
 *
 * Resources to Dtos/Entities
 * Dtos/Entities to Representation/Presentation
 */
class DtoTransformerService
{
    private ResourceInterface|DtoInterface|null $resource = null;

    /**
     * Converts resources to Dtos/Entities
     *
     * @param DtoInterface $dto your Dto/Entity
     * @param array $guard fields to guard against (not to represent in response)
     *
     * @return DtoInterface transferred Dto
     *
     * @throws Exception
     */
    public function transfer(DtoInterface $dto, array $guard = []): DtoInterface
    {
        if ($this->resource === null && !($this->resource instanceof ResourceInterface)) {
            throw new Exception('Pass ResourceInterface first');
        }

        $reflectedDto = new ReflectionClass($this->resource);
        foreach ($reflectedDto->getProperties() as $property) {
            $propertyName = "set" . ucfirst($property->getName());

            /**
             * Allowed fields is more important
             */
            if ((!in_array($property->getName(), $guard) && !in_array($property->getName(), $dto->guardFields())) || in_array($property->getName(), $dto->allowedFields())) {
                $dto->$propertyName($this->resource->{$property->getName()});
            }
        }

        return $dto;
    }

    /**
     * Converts resources to Representation/Presentation
     *
     * @param RepresentationInterface $represent your presentation
     * @param array $guard fields to guard against (not to represent in response)
     *
     * @return RepresentationInterface represented presentation
     *
     * @throws Exception
     */
    public function represent(RepresentationInterface $represent, array $guard = []): RepresentationInterface
    {
        if ($this->resource === null && !($this->resource instanceof DtoInterface)) {
            throw new Exception('Pass DtoInterface first');
        }

        $reflectedDto = new ReflectionClass($this->resource);
        foreach ($reflectedDto->getProperties() as $property) {
            $propertyName = "get" . ucfirst($property->getName());

            /**
             * Allowed fields is more important
             */
            if ((!in_array($property->getName(), $guard) && !in_array($property->getName(), $this->resource->guardFields())) || in_array($property->getName(), $this->resource->allowedFields())) {
                $represent->{$property->getName()} = $this->resource->$propertyName();
            }
        }

        return $represent;
    }

    /**
     * @param ResourceInterface|DtoInterface $resource passing the Dto you want to transfer
     *
     * @return $this
     */
    public function from(ResourceInterface|DtoInterface $resource): static
    {
        $this->resource = $resource;

        return $this;
    }
}
