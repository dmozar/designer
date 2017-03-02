<?php namespace Imedia\Designer\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Minty\MVC;

class DesignRepository extends EntityRepository {
    
    
    const User          = 'Imedia\User\Entity\User';
    const Design        = 'Imedia\Designer\Entity\Design';
    
    /**
     *
     * @var type 
     */
    protected $options = [];
    
    /**
     * 
     * @param type $options
     */
    public function setOptions( $options ){ $this->options = $options; $this->options['user'] = MVC::getUserData(); return $this; }
    
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
        
        $query->useResultCache(TRUE, NULL, 'designer_single');
        
        return $query->getOneOrNullResult();
    }
    
    
    /**
     * 
     * @return Paginator
     */
    public function getListItems(){
        
        if( ! $User = $this->options['user'] ) return null;
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->Select('d,u')->From( self::Design, 'd')
            ->leftJoin('d.user', 'u')
            ->where('u.id = :user_id')
            ->setParameter('user_id', $User->getID());
        
        $qb->setFirstResult( $this->options['offset'] );
        $qb->setMaxResults( $this->options['limit'] );
        
        $query = $qb->getQuery(); 
        
        $query->useResultCache(TRUE, NULL, 'designer_list');
        
        $Paginator = new Paginator( $query, true );
        
        return $Paginator;
    }
    
}