<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Dto\Representation\Employee\EmployeePostRepresentation;
use App\Dto\Resource\Employee\EmployeePostResource;
use App\Interface\DtoInterface;
use App\Repository\EmployeeRepository;
use App\State\Processor\Employee\EmployeePostProcessor;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueEntity(['username', 'email'])]
#[ORM\Entity(repositoryClass: EmployeeRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Post(
        input: EmployeePostResource::class, output: EmployeePostRepresentation::class, processor: EmployeePostProcessor::class
    ),
    new Delete()
])]
final class Employee implements DtoInterface, PasswordAuthenticatedUserInterface, UserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(10)]
    #[ORM\Column(length: 10)]
    private ?string $nid = null;

    #[Assert\Length(min: 6, max: 64)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 64)]
    private ?string $fullname = null;

    #[Assert\Length(min: 3, max: 32)]
    #[Assert\NotBlank]
    #[ORM\Column(length: 32)]
    private ?string $username = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 180)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registerDate;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $lastOnlineDate = null;

    #[ORM\ManyToMany(targetEntity: Task::class, mappedBy: 'employee')]
    private Collection $tasks;

    #[ORM\ManyToMany(targetEntity: Reminder::class, mappedBy: 'employee')]
    private Collection $reminders;

    public function __construct()
    {
        $this->registerDate = new \DateTime();

        $this->tasks = new ArrayCollection();
        $this->reminders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNid(): ?string
    {
        return $this->nid;
    }

    public function setNid(string $nid): Employee
    {
        $this->nid = $nid;

        return $this;
    }

    public function getFullname(): ?string
    {
        return $this->fullname;
    }

    public function setFullname(string $fullname): Employee
    {
        $this->fullname = $fullname;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): Employee
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): Employee
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): Employee
    {
        $this->password = $password;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): Employee
    {
        $this->registerDate = $registerDate;

        return $this;
    }

    public function getLastOnlineDate(): ?\DateTimeInterface
    {
        return $this->lastOnlineDate;
    }

    public function setLastOnlineDate(\DateTimeInterface $lastOnlineDate): Employee
    {
        $this->lastOnlineDate = $lastOnlineDate;

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): Employee
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->addEmployee($this);
        }

        return $this;
    }

    public function removeTask(Task $task): Employee
    {
        if ($this->tasks->removeElement($task)) {
            $task->removeEmployee($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reminder>
     */
    public function getReminders(): Collection
    {
        return $this->reminders;
    }

    public function addReminder(Reminder $reminder): Employee
    {
        if (!$this->reminders->contains($reminder)) {
            $this->reminders->add($reminder);
            $reminder->addEmployee($this);
        }

        return $this;
    }

    public function removeReminder(Reminder $reminder): Employee
    {
        if ($this->reminders->removeElement($reminder)) {
            $reminder->removeEmployee($this);
        }

        return $this;
    }

    public function allowedFields(): array
    {
        return [];
    }

    public function guardFields(): array
    {
        return ['password', 'plainPassword', 'confirmPassword'];
    }

    public function getRoles(): array
    {
        $roles = ['ROLE_EMPLOYEE'];

        return $roles;
    }

    public function eraseCredentials(): void
    {
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }
}
