<?php

namespace App\Repository;

use App\Entity\ApprovalRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ApprovalRequest>
 *
 * @method ApprovalRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApprovalRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApprovalRequest[]    findAll()
 * @method ApprovalRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprovalRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApprovalRequest::class);
    }

    //    /**
    //     * @return ApprovalRequest[] Returns an array of ApprovalRequest objects
    //     */
    public function findByApproved($value): array
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.approved = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?ApprovalRequest
    //    {
    //        return $this->createQueryBuilder('a')
    //            ->andWhere('a.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
