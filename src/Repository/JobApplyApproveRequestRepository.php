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
    public function findUnapprovedRequests(): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->setParameter('val', 0)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
