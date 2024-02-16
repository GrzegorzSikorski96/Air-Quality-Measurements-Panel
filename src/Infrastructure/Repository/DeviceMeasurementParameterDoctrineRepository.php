<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Entity\DeviceMeasurementParameter;
use App\Domain\Repository\DeviceMeasurementParameterRepositoryInterface;
use App\Domain\Repository\NonExistentEntityException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

final class DeviceMeasurementParameterDoctrineRepository extends ServiceEntityRepository implements DeviceMeasurementParameterRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeviceMeasurementParameter::class);
    }

    public function save(DeviceMeasurementParameter $deviceMeasurementParameter): void
    {
        $this->getEntityManager()->persist($deviceMeasurementParameter);
    }

    public function get(Uuid $id): DeviceMeasurementParameter
    {
        $deviceMeasurementParameter = $this->findOne($id);

        if (!$deviceMeasurementParameter) {
            throw new NonExistentEntityException(DeviceMeasurementParameter::class, $id->toRfc4122());
        }

        return $deviceMeasurementParameter;
    }

    public function findOne(Uuid $id): ?DeviceMeasurementParameter
    {
        return $this->find($id);
    }

    public function findByDeviceId(Uuid $deviceId): array
    {
        return $this->findBy(['deviceId' => $deviceId->toRfc4122()]);
    }

    public function findOneByDeviceIdAndMeasurementParameterId(Uuid $deviceId, Uuid $measurementParameterId): ?DeviceMeasurementParameter
    {
        return $this->findOneBy(['deviceId' => $deviceId->toRfc4122(), 'measurementParameterId' => $measurementParameterId->toRfc4122()]);
    }
}
