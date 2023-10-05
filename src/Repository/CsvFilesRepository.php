<?php

namespace App\Repository;

use App\Entity\CsvFiles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CsvFiles>
 *
 * @method CsvFiles|null find($id, $lockMode = null, $lockVersion = null)
 * @method CsvFiles|null findOneBy(array $criteria, array $orderBy = null)
 * @method CsvFiles[]    findAll()
 * @method CsvFiles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CsvFilesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CsvFiles::class);
    }

//    /**
//     * @return CsvFiles[] Returns an array of CsvFiles objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CsvFiles
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
