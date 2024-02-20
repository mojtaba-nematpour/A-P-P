<?php

namespace App\Dto\Resource\Employee;

use App\Core\Dto\AbstractResource;
use Symfony\Component\Validator\Constraints as Assert;

class EmployeePostResource extends AbstractResource
{
    #[Assert\NotBlank]
    #[Assert\Length(10)]
    public ?string $nid = null;

    #[Assert\Length(min: 6, max: 64)]
    #[Assert\NotBlank]
    public ?string $fullname = null;

    #[Assert\Length(min: 3, max: 32)]
    #[Assert\NotBlank]
    public ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    public ?string $email = null;

    public ?string $plainPassword = null;

    public ?string $confirmPassword = null;
}
