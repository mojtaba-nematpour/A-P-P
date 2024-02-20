<?php

namespace App\State\Processor\Employee;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Core\AbstractProcessor;
use App\Dto\Representation\Employee\EmployeePostRepresentation;
use App\Dto\Resource\Employee\EmployeePostResource;
use App\Entity\Employee;
use App\Service\DtoTransformerService;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @implements ProcessorInterface<EmployeePostResource, EmployeePostRepresentation>
 */
final class EmployeePostProcessor extends AbstractProcessor
{
    public function __construct(
        DtoTransformerService                        $transformer,
        private readonly UserPasswordHasherInterface $hasher,
        /**
         * @var ProcessorInterface $persistProcessor this can be used in traits or separate classes
         */
        #[Autowire(service: 'api_platform.doctrine.orm.state.persist_processor')]
        private ProcessorInterface                   $persistProcessor,
    )
    {
        parent::__construct($transformer);
    }

    /**
     * @param EmployeePostResource $data
     *
     * @throws \Exception
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): EmployeePostRepresentation
    {
        /**
         * @var Employee $employee
         */
        $employee = $this->transformer->from($data)->transfer(new Employee());
        $employee->setPassword($this->hasher->hashPassword($employee, $data->plainPassword));

        $this->persistProcessor->process($employee, $operation, $uriVariables, $context);

        return $this->transformer->from($employee)->represent(new EmployeePostRepresentation());
    }
}
