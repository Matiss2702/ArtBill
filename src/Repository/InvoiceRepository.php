<?php

namespace App\Repository;

use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function findAll()
    {
        return parent::createQueryBuilder('entity')
            ->orderBy('entity.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
    //    /**
    //     * @return Invoice[] Returns an array of Invoice objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('i.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Invoice
    //    {
    //        return $this->createQueryBuilder('i')
    //            ->andWhere('i.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findOneById($value): ?Invoice
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findAllByCompany(User $user): ?array
    {
        $company = $user->getCompany();

        return $this->createQueryBuilder('q')
            ->andWhere('q.company = :company')
            ->andWhere('q.status != :status')
            ->setParameter('company', $company)
            ->setParameter('status', 'archived')
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findAllArchivedByCompany(User $user): ?array
    {
        $company = $user->getCompany();
    
        return $this->createQueryBuilder('q')
            ->andWhere('q.company = :company')
            ->andWhere('q.status = :status')
            ->setParameter('company', $company)
            ->setParameter('status', 'archived') 
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllArchived(User $user): ?array
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.status = :status')
            ->setParameter('status', 'archived') 
            ->orderBy('q.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
}
