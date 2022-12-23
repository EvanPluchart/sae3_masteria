<?php

namespace App\Repository;

use App\Entity\CandidacyTER;
use App\Entity\Student;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CandidacyTER>
 *
 * @method CandidacyTER|null find($id, $lockMode = null, $lockVersion = null)
 * @method CandidacyTER|null findOneBy(array $criteria, array $orderBy = null)
 * @method CandidacyTER[]    findAll()
 * @method CandidacyTER[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CandidacyTERRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CandidacyTER::class);
    }

    public function save(CandidacyTER $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(CandidacyTER $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function searchCandidacies(Student $student = null)
    {
        return $this->createQueryBuilder('ct')
            ->join('ct.student', 'student')
            ->addSelect('student')
            ->where('ct.student = :student')
            ->orderBy('student.lastname')
            ->addOrderBy('student.firstname')
            ->addOrderBy('ct.date')
            ->setParameter('student', $student)
            ->getQuery()
            ->execute();
    }

    public function searchCandidaciesAdmin()
    {
        return $this->createQueryBuilder('ct')
            ->join('ct.student', 'student')
            ->addSelect('student')
            ->orderBy('student.lastname')
            ->addOrderBy('student.firstname')
            ->getQuery()
            ->execute();
    }

//    /**
//     * @return CandidacyTER[] Returns an array of CandidacyTER objects
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

//    public function findOneBySomeField($value): ?CandidacyTER
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
