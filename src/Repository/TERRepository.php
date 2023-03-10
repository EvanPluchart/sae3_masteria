<?php

namespace App\Repository;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\TER;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TER>
 *
 * @method TER|null find($id, $lockMode = null, $lockVersion = null)
 * @method TER|null findOneBy(array $criteria, array $orderBy = null)
 * @method TER[]    findAll()
 * @method TER[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TERRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TER::class);
    }

    public function save(TER $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TER $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function search()
    {
        return $this->createQueryBuilder('t')
            ->addSelect('teach as teacher')
            ->addSelect('stud as student')
            ->leftJoin('t.teacher', 'teach')
            ->leftJoin('t.selectedStudent', 'stud')
            ->orderBy('t.date', 'DESC')
            ->getQuery()
            ->execute();
    }

    public function searchTeacherTERS(Teacher $teacher = null)
    {
        $id = $teacher->getId();

        return $this->createQueryBuilder('ter')
            ->join('ter.teacher', 'teacher')
            ->addSelect('teacher')
            ->where('teacher.id LIKE :id')
            ->orderBy('ter.date')
            ->setParameter('id', $id)
            ->getQuery()
            ->execute();
    }

    public function findWithTeacher(int $id): ?TER
    {
        return $this->createQueryBuilder('ter')
            ->addSelect('teach as teacher')
            ->leftJoin('ter.teacher', 'teach')
            ->where('ter.id = :id')
            ->setParameter(':id', $id)
            ->getQuery()
            ->getResult()[0];
    }

    public function findAllNotInCandidatures(Student $student, CandidacyTERRepository $candidacyTERRepository): array
    {
        $allTER = $this->search();
        $candidatures = $candidacyTERRepository->searchCandidacies($student);
        $notInCandidatures = [];

        // Pour chaque TER de la base de donn??es
        foreach ($allTER as $ter) {
            $inCandidature = false;

            // Pour chaque candidature de l'??tudiant
            foreach ($candidatures as $candidature) {
                // Si le TER est pr??sent dans la candidature
                if ($candidature->getTER() === $ter) {
                    $inCandidature = true;
                    break;
                }
            }

            // Si le TER n'est pas pr??sent dans aucune candidature de l'??tudiant
            if (!$inCandidature) {
                // Ajout du TER ?? la liste des TER qui ne sont pas dans une candidature
                $notInCandidatures[] = $ter;
            }
        }

        return $notInCandidatures;
    }

    public function countNumberOfTER()
    {
        return $this->createQueryBuilder('ct')
            ->select('COUNT(ct.id)')
            ->getQuery()
            ->getResult()[0][1];
    }

//    /**
//     * @return TER[] Returns an array of TER objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TER
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
