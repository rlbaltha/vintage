<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * PointRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PointRepository extends EntityRepository
{
    public function findMine($user)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT p from AppBundle:Point p WHERE p.user = ?1 ORDER BY p.')->setParameter('1',$user)->getResult();
    }

    public function findReleased()
    {
        $pending = 1;
        return $this->getEntityManager()
            ->createQuery('SELECT p from AppBundle:Point p WHERE p.status = ?1 ORDER BY p.')->setParameter('1',$pending)->getResult();
    }

    public function findPending()
    {
        $pending = 1;
        return $this->getEntityManager()
            ->createQuery('SELECT p from AppBundle:Point p WHERE p.status != ?1 ORDER BY p.')->setParameter('1',$pending)->getResult();
    }
}
