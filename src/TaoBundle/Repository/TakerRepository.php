<?php

namespace TaoBundle\Repository;

/**
 * TakerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TakerRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * @param string $login
     *
     * @return array
     */
    public function findByLogin($login)
    {
        $qb = $this->createQueryBuilder('t');

        $qb->where('t.login = :login')
            ->setParameter('login', $login);

        return $qb->getQuery()->getResult();
    }

    /**
     * @param int $from
     * @param int $to
     * @return array
     */
    public function loadTakers($from = 0, $to = 10)
    {
        $qb = $this->createQueryBuilder('t');

        $qb
            ->select('t.id, t.login, t.title, t.lastname, t.firstname, t.gender, t.email, t.picture, t.address')
            ->addOrderBy('t.id', 'ASC')
            ->setMaxResults($to)
            ->setFirstResult($from);

        return $qb->getQuery()->getArrayResult();
    }
}
