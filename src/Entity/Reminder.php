<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\ReminderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReminderRepository::class)]
#[ApiResource(operations: [
    new Get(),
    new GetCollection(),
    new Post(),
    new Delete()
])]
class Reminder
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Employee::class, inversedBy: 'reminders')]
    private Collection $employee;

    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $reminderableId = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $remindDate = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $registerDate;

    public function __construct()
    {
        $this->registerDate = new \DateTime();

        $this->employee = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Employee>
     */
    public function getEmployee(): Collection
    {
        return $this->employee;
    }

    public function addEmployee(Employee $employee): static
    {
        if (!$this->employee->contains($employee)) {
            $this->employee->add($employee);
        }

        return $this;
    }

    public function removeEmployee(Employee $employee): static
    {
        $this->employee->removeElement($employee);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getReminderableId(): ?string
    {
        return $this->reminderableId;
    }

    public function setReminderableId(string $reminderableId): static
    {
        $this->reminderableId = $reminderableId;

        return $this;
    }

    public function getRemindDate(): ?\DateTimeInterface
    {
        return $this->remindDate;
    }

    public function setRemindDate(\DateTimeInterface $remindDate): static
    {
        $this->remindDate = $remindDate;

        return $this;
    }

    public function getRegisterDate(): ?\DateTimeInterface
    {
        return $this->registerDate;
    }

    public function setRegisterDate(\DateTimeInterface $registerDate): static
    {
        $this->registerDate = $registerDate;

        return $this;
    }
}
