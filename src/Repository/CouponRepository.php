<?php

namespace App\Repository;

use App\Entity\Coupon;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Coupon>
 */
class CouponRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Coupon::class);
    }

    public function findByCode(string $code): ?Coupon
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.code = :code')
            ->andWhere('c.used = :used')
            ->setParameter('code', $code)
            ->setParameter('used', false)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
