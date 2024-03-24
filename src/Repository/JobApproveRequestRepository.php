<?php

namespace App\Repository;

use App\Entity\JobApproveRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobApproveRequest>
 *
 * @method JobApproveRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApproveRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApproveRequest[]    findAll()
 * @method JobApproveRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApproveRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApproveRequest::class);
    }


    /**
     * Finds unapproved requests based on the given value.
     *
     * @param mixed $value The value to filter the unapproved requests.
     * @return array The array of unapproved requests.
     */
    public function findUnapprovedRequest($value): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }
}
