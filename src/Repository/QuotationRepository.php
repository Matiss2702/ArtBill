<?php

namespace App\Repository;

use App\Entity\Quotation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quotation>
 *
 * @method Quotation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quotation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quotation[]    findAll()
 * @method Quotation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuotationRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Quotation::class);
        $this->entityManager = $entityManager;
    }

    //    /**
    //     * @return Quotation[] Returns an array of Quotation objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    public function findOneById($value): ?Quotation
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }


    public function findAllPreviousVersions(Quotation $quotation): array
    {
        $previousQuotations = [];

        while ($quotation !== null && $quotation->getPreviousVersion() !== null) {
            $previousQuotations[] = $quotation->getPreviousVersion();
            $quotation = $quotation->getPreviousVersion();
        }

        return $previousQuotations;
    }

    public function findAllNextVersions(Quotation $quotation): array
    {
        $nextQuotations = [];

        while ($quotation !== null && $quotation->getNextQuotation() !== null) {
            $nextQuotations[] = $quotation->getNextQuotation();
            $quotation = $quotation->getNextQuotation();
        }

        return $nextQuotations;
    }

    public function findLatestQuotations(): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder->select('q')
            ->from('App\Entity\Quotation', 'q')
            ->where(
                $queryBuilder->expr()->not(
                    $queryBuilder->expr()->exists(
                        $this->entityManager->createQueryBuilder()
                            ->select('qq.id')
                            ->from('App\Entity\Quotation', 'qq')
                            ->where('qq.previousVersion = q.id')
                    )
                )
            )
            ->andWhere('q.status != :status')
            ->setParameter('status', 'archived') 
            ->getQuery();

        return $query->getResult();
    }

    public function findLatestQuotationsForCustomer($customer): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();

        $query = $queryBuilder->select('q')
            ->from('App\Entity\Quotation', 'q')
            ->where(
                $queryBuilder->expr()->not(
                    $queryBuilder->expr()->exists(
                        $this->entityManager->createQueryBuilder()
                            ->select('qq.id')
                            ->from('App\Entity\Quotation', 'qq')
                            ->where('qq.previousVersion = q.id')
                            ->andWhere('qq.customer = :customer')
                    )
                )
            )
            ->andWhere('q.status != :status')
            ->setParameter('customer', $customer)
            ->setParameter('status', 'archived') 
            ->getQuery();

        return $query->getResult();
    }

    public function findLatestQuotationsForCompany(User $user): array
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $company = $user->getCompany();

        $subQueryBuilder = $this->entityManager->createQueryBuilder();
        $subQuery = $subQueryBuilder
            ->select('qq.id')
            ->from('App\Entity\Quotation', 'qq')
            ->where('qq.previousVersion = q.id');

        $query = $queryBuilder->select('q')
            ->from('App\Entity\Quotation', 'q')
            ->where(
                $queryBuilder->expr()->not(
                    $queryBuilder->expr()->exists($subQuery)
                )
            )
            ->andWhere('q.company = :company')
            ->andWhere('q.status != :status')
            ->setParameter('company', $company)
            ->setParameter('status', 'archived') 
            ->getQuery();

        return $query->getResult();
    }

    public function findAllArchivedByCompany(User $user): ?array
    {
        $company = $user->getCompany();
    
        return $this->createQueryBuilder('q')
            ->andWhere('q.company = :company')
            ->andWhere('q.status = :status')
            ->setParameter('company', $company)
            ->setParameter('status', 'archived') 
            ->getQuery()
            ->getResult()
        ;
    }
}
