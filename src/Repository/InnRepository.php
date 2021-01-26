<?php

namespace App\Repository;

use App\Entity\Inn;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inn|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inn|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inn[]    findAll()
 * @method Inn[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InnRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inn::class);
    }

    public function findActualInn($inn)
    {
        $now = new \DateTime();
        return $this->createQueryBuilder('i')
            ->where('i.updatedAt > :today')
            ->andWhere('i.inn = :inn')
            ->setParameters([
                'today' => $now->format('Y-m-d'),
                'inn' => $inn
            ])
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
