<?php

namespace App\Repository;

use App\Entity\Iut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Iut>
 *
 * @method Iut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Iut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Iut[]    findAll()
 * @method Iut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IutRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Iut::class);
    }

    public function save(Iut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Iut $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Iut[] Returns an array of Iut objects
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

    public function allIut()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.nom', 'ASC');
    }

//    public function findOneBySomeField($value): ?Iut
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
