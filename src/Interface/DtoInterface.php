<?php

namespace App\Interface;

/**
 * Each doctrine entity is a Dto and Dtos have some allowed fields to represent and some fields to guard
 */
interface DtoInterface
{
    public function allowedFields(): array;

    public function guardFields(): array;
}
