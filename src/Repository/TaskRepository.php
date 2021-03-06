<?php

namespace App\Repository;

use App\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function findTasksFilteredByBoolDone(bool $value, int $page, int $nbResult)
    {
        return $this->createQueryBuilder('t')
            ->leftjoin('t.user', 'u')
            ->addSelect('PARTIAL u.{username, id}')
            ->andWhere('t.isDone = :val')
            ->orderBy('t.id', 'DESC')
            ->setFirstResult(($page - 1) * $nbResult)
            ->setMaxResults($nbResult)
            ->setParameter('val', $value)
            ->getQuery()
            ->getResult()
        ;
    }

    public function countTasksDone()
    {
        return $this->createQueryBuilder('t')
            ->select("COUNT(t.id)")
            ->andWhere('t.isDone = 1')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }

    public function countTasksNotDone()
    {
        return $this->createQueryBuilder('t')
            ->select("COUNT(t.id)")
            ->andWhere('t.isDone = 0')
            ->getQuery()
            ->getSingleScalarResult()
        ;
    }
}
