<?php

namespace App\Repository;

use App\Entity\RecruiterProfile;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RecruiterProfile>
 *
 * @method RecruiterProfile|null find($id, $lockMode = null, $lockVersion = null)
 * @method RecruiterProfile|null findOneBy(array $criteria, array $orderBy = null)
 * @method RecruiterProfile[]    findAll()
 * @method RecruiterProfile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecruiterProfileRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RecruiterProfile::class);
    }

//    /**
//     * @return RecruiterProfile[] Returns an array of RecruiterProfile objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RecruiterProfile
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
