<?php

namespace App\Dto\Representation\Employee;

use App\Core\Dto\AbstractRepresentation;

class EmployeePostRepresentation extends AbstractRepresentation
{
    public ?string $nid = null;

    public ?string $fullname = null;

    public ?string $username = null;

    public ?string $email = null;
}
