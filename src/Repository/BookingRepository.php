<?php

namespace App\Repository;

use App\Entity\Booking;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Booking|null find($id, $lockMode = null, $lockVersion = null)
 * @method Booking|null findOneBy(array $criteria, array $orderBy = null)
 * @method Booking[]    findAll()
 * @method Booking[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Booking::class);
    }

     /**
      * @return Booking[] Returns an array of Booking objects
      */
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.visitDate = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Booking
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     *
     *
     * @param DateTimeInterface|null $visitDate
     * @return int
     * @throws NonUniqueResultException
     */
    public function countTicketsPerDate(?DateTimeInterface $visitDate): int
    {

            return $this->createQueryBuilder('b')
                ->select('SUM(b.quantity)')
                ->where('b.visitDate = :visitDate')
                ->setParameter('visitDate', $visitDate)
                ->getQuery()
                ->getSingleScalarResult() ?? 0
            ;

    }
}
