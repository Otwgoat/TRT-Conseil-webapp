<?php

namespace App\Repository;

use App\Entity\JobAdvertissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<JobAdvertissement>
 *
 * @method JobAdvertissement|null find($id, $lockMode = null, $lockVersion = null)
 * @method JobAdvertissement|null findOneBy(array $criteria, array $orderBy = null)
 * @method JobAdvertissement[]    findAll()
 * @method JobAdvertissement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class JobAdvertissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, JobAdvertissement::class);
    }

    //    /**
    //     * @return JobAdvertissement[] Returns an array of JobAdvertissement objects
    //     */
    public function findByApprovedAdvertissement($value): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.approved = :val')
            ->setParameter('val', $value)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(30)
            ->getQuery()
            ->getResult();
    }

    public function findOneById($id): ?JobAdvertissement
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.id = :val')
            ->andWhere('j.approved = 1')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findByRecruiterId($recruiter): array
    {
        return $this->createQueryBuilder('j')
            ->andWhere('j.recruiterId = :val')
            ->setParameter('val', $recruiter)
            ->orderBy('j.id', 'ASC')
            ->setMaxResults(50)
            ->getQuery()
            ->getResult();
    }
}
