<?php

namespace App\Repository;

use App\Entity\Deadgarage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Deadgarage|null find($id, $lockMode = null, $lockVersion = null)
 * @method Deadgarage|null findOneBy(array $criteria, array $orderBy = null)
 * @method Deadgarage[]    findAll()
 * @method Deadgarage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeadgarageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Deadgarage::class);
    }

    /**
     * @param $stayId
     * @return mixed
     */
    public function findDeadGaragebyStay($stayId){
        return $this->createQueryBuilder('d')
            ->where('d.stay = :stayId')
            ->setParameter('stayId', $stayId)
            ->orderBy('d.started_at', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $stayId
     * @param $date
     * @return mixed
     */
    public function findCurrentDeadGaragebyStay($stayId, $date){
        return $this->createQueryBuilder('d')
            ->where('d.stay = :stayId')
            ->andWhere('d.started_at < :date')
            ->andWhere('d.stopped_at > :date')
            ->setParameter('stayId', $stayId)
            ->setParameter('date', $date)
            ->orderBy('d.started_at', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Deadgarage[] Returns an array of Deadgarage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Deadgarage
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
