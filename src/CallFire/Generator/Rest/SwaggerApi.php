<?php
namespace CallFire\Generator\Rest;

use Zend\Stdlib\Hydrator;

class SwaggerApi
{
    protected $path;

    protected $description;

    protected $operations = array();

    protected $hydrator;

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    public function getOperations()
    {
        return $this->operations;
    }

    public function setOperations($operations)
    {
        $this->operations = $operations;

        return $this;
    }

    public function addOperation($operation)
    {
        $this->operations[] = $operation;

        return $this;
    }

    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->hydrator = new Hydrator\ClassMethods;
        }

        return $this->hydrator;
    }

    public function setHydrator(Hydrator\HydratorInterface $hydrator)
    {
        $this->hydrator = $hydrator;

        return $this;
    }

    public function hydrate($data, Hydrator\HydratorInterface $operationHydrator = null)
    {
        if (isset($data['operations']) && ($operations = $data['operations'])) {
            unset($data['operations']);

            $operationProto = new SwaggerOperation;
            if ($operationHydrator) {
                $operationProto->setHydrator($operationHydrator);
            }

            foreach ($operations as $operationData) {
                $operation = clone $operationProto;
                $operation->hydrate($operationData);
                $this->addOperation($operation);
            }
        }

        $hydrator = $this->getHydrator();
        $hydrator->hydrate($data, $this);

        return $this;
    }
}
