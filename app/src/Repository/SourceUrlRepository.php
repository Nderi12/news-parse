<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

use App\Entity\SourceUrl;

/**
 * @extends ServiceEntityRepository<SourceUrl>
 *
 * @method SourceUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method SourceUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method SourceUrl[]    findAll()
 * @method SourceUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SourceUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SourceUrl::class);
    }

    public function add(SourceUrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(SourceUrl $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findOneByUrl($value): ?SourceUrl
    {
        return $this->findOneBy(['url' => $value]);
    }

//    /**
//     * @return SourceUrl[] Returns an array of SourceUrl objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?SourceUrl
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
