<?php

namespace App\Core;

use ApiPlatform\State\ProcessorInterface;
use App\Service\DtoTransformerService;

/**
 * Base Processor class
 */
abstract class AbstractProcessor implements ProcessorInterface
{
    public function __construct(protected readonly DtoTransformerService $transformer)
    {
    }
}
