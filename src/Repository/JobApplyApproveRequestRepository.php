<?php

namespace App\Repository;

use App\Entity\JobApplyApproveRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobApplyApproveRequest>
 *
 * @method JobApplyApproveRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApplyApproveRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApplyApproveRequest[]    findAll()
 * @method JobApplyApproveRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApplyApproveRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApplyApproveRequest::class);
    }

//    /**
//     * @return JobApplyApproveRequest[] Returns an array of JobApplyApproveRequest objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('j.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?JobApplyApproveRequest
//    {
//        return $this->createQueryBuilder('j')
//            ->andWhere('j.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
