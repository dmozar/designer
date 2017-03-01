<?php namespace Imedia\Designer\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DesignRepository extends EntityRepository {
    
    
    const User          = 'Imedia\User\Entity\User';
    const Design        = 'Imedia\Designer\Entity\Design';
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function SingleDesign( $design_id = null, $User = null ){

        if( ! $design_id || ! $User) return null;
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->Select('d,u')->From( self::Design, 'd')
            ->leftJoin('d.user', 'u')
            ->where('u.id = :user_id')
            ->andWhere('d.id = :id')
            ->setParameter('id', $design_id)
            ->setParameter('user_id', $User->getID());
        
        $query = $qb->getQuery(); 
        
        return $query->getOneOrNullResult();
    }
    
}