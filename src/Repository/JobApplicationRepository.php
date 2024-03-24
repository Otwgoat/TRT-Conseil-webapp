<?php

namespace App\Repository;

use App\Entity\JobApplication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobApplication>
 *
 * @method JobApplication|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobApplication|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobApplication[]    findAll()
 * @method JobApplication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobApplication::class);
    }

    //    /**
    //     * @return JobApplication[] Returns an array of JobApplication objects
    //     */
    public function findApprovedApplication($value): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    public function findApprovedApplicationByUser($value, $user): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->andWhere('j.candidateID = :user')
            ->setParameter('val', $value)
            ->setParameter('user', $user)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }
    public function findApprovedApplicationByAd($value, $job): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->andWhere('j.jobID = :job')
            ->setParameter('val', $value)
            ->setParameter('job', $job)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function findApplicationsByUserAndById($jobId, $candidate): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.jobID = :id')
            ->andWhere('j.candidateID = :candidate')
            ->setParameter('id', $jobId)
            ->setParameter('candidate', $candidate)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
}
