<?php

namespace App\Repository;

use App\Entity\Address;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Address>
 *
 * @method Address|null find($id, $lockMode = null, $lockVersion = null)
 * @method Address|null findOneBy(array $criteria, array $orderBy = null)
 * @method Address[]    findAll()
 * @method Address[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddressRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Address::class);
    }

//    /**
//     * @return Address[] Returns an array of Address objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

   public function findOneByExactAddress($city, $street, $zip_code, $country): ?Address
   {
       return $this->createQueryBuilder('a')
           ->andWhere('a.city = :city')
           ->setParameter('city', $city)
           ->andWhere('a.street = :street')
           ->setParameter('street', $street)
           ->andWhere('a.zip_code = :zip_code')
           ->setParameter('zip_code', $zip_code)
           ->andWhere('a.country = :country')
           ->setParameter('country', $country)
           ->getQuery()
           ->getOneOrNullResult()
       ;
   }
}
