<?php

namespace App\Repository;

use App\Entity\Stay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Stay|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stay|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stay[]    findAll()
 * @method Stay[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stay::class);
    }

    public function findStayWithDeadGarage($stay)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('d')
            ->leftJoin('s.deadgarages', 'd')
            ->where('s.id = :stay')
            ->setParameter('stay', $stay)
            ->getQuery()
            ->getResult();
    }

    public function findStaysByBill($bill)
    {
        return $this->createQueryBuilder('s')
            ->where('s.bill = :bill')
            ->setParameter('bill', $bill)
            ->getQuery()
            ->getResult();
    }

    public function findNotPaidStaysByEntry($entry)
    {
        return $this->createQueryBuilder('s')
            ->where('s.entry = :entry')
            ->andWhere('s.bill is NULL')
            ->setParameter('entry', $entry)
            ->orderBy('s.booking')
            ->getQuery()
            ->getResult();
    }

    public function findCurrentStays($date)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->where('s.arrived_at <= :date')
            ->andWhere('s.leaved_at >= :date')
            ->andWhere('s.booking = false')
            ->setParameter('date', $date)
            ->leftJoin('s.entry', 'e')
            ->getQuery()
            ->getResult();
    }

    public function findCurrentStaysAndBookings($date)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->where('s.arrived_at <= :date')
            ->andWhere('s.leaved_at >= :date')
            ->setParameter('date', $date)
            ->leftJoin('s.entry', 'e')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $date
     * @return Query
     */
    public function findAllLastStaysQuery($date) : Query
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->where('s.arrived_at <= :date')
            ->andWhere('s.booking = false')
            ->setParameter('date', $date)
            ->leftJoin('s.entry', 'e')
            ->orderBy('s.arrived_at', 'DESC')
            ->getQuery();
    }

    /**
     * @param $entry
     * @param $date
     * @return Stay[] Returns an array of Stay objects
     */
    public function findCurrentStaysByEntry($entry, $date)
    {
        return $this->createQueryBuilder('s')
            ->where('s.entry = :entry')
            ->andWhere('s.arrived_at <= :date')
            ->andWhere('s.leaved_at >= :date')
            ->andWhere('s.booking = false')
            ->setParameter('entry', $entry)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $entry
     * @param $date
     * @return Stay[] Returns an array of Stay objects
     */
    public function findCurrentStaysAndBookingsByEntry($entry, $date)
    {
        return $this->createQueryBuilder('s')
            ->where('s.entry = :entry')
            ->andWhere('s.arrived_at <= :date')
            ->andWhere('s.leaved_at >= :date')
            ->setParameter('entry', $entry)
            ->setParameter('date', $date)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $entry
     * @param $date
     * @return Stay Returns an Stay object
     */
    public function findLastStayByEntry($entry, $date)
    {
        return $this->createQueryBuilder('s')
            ->where('s.entry = :entry')
            ->andWhere('s.leaved_at <= :date')
            ->andWhere('s.booking = false')
            ->setParameter('entry', $entry)
            ->setParameter('date', $date)
            ->orderBy('s.leaved_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Stay[] Returns an array of Stay objects
     */
    public function findAllBooking()
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->leftJoin('s.entry', 'e')
            ->where('s.booking = true')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Stay[] Returns an array of Stay objects
     */
    public function findNextBookingByEntry($entry, $date)
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->leftJoin('s.entry', 'e')
            ->where('s.booking = true')
            ->andWhere('e.id = :entry')
            ->andWhere('s.arrived_at > :date')
            ->setParameter('entry', $entry)
            ->setParameter('date', $date)
            ->orderBy('s.leaved_at', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Stay[] Returns an array of Stay objects
     */
    public function findAllBookingForWoodenCaravan()
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->leftJoin('s.entry', 'e')
            ->where('s.booking = true')
            ->andWhere('s.wooden_caravan = true')
            ->getQuery()
            ->getResult();
    }

    public function findAllBookingForBareGround()
    {
        return $this->createQueryBuilder('s')
            ->addSelect('e')
            ->leftJoin('s.entry', 'e')
            ->where('s.booking = true')
            ->andWhere('s.wooden_caravan = false')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Stay[] Returns an array of Stay objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Stay
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
