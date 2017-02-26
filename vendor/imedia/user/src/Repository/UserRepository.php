<?php namespace Imedia\User\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class UserRepository extends EntityRepository {
    
    
    const User          = 'Imedia\User\Entity\User';
    
    
    /**
     * 
     * @param type $user_id
     * @return type
     */
    public function getUser($user_id){

        if( ! $user_id ) return null;
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->Select(array(
                'partial u.{ id, first_name, last_name, company, image, email, phone, type, status, token }',
            ))->From( self::User, 'u')
            ->where('u.id = :id')
            ->setParameter('id', $user_id);
        
        $query = $qb->getQuery(); 
        
        return $query->getOneOrNullResult();
        
    }
    
    
    
    public function login($email, $password){
        
        global $session_key;
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->Select(array(
                'partial u.{ id, first_name, last_name, company, image, email, phone, type, status, token }',
            ))->From( self::User, 'u')
            ->where('u.email = :email')
            ->andWhere('u.password = :password')
            ->andWhere('u.status = 1')
            ->setParameter('email', $email)
            ->setParameter('password', $password);
        
        //b8ef704b8c1bfbd0d0c27d47fed1ca18
        
        if( $session_key ){
            switch ($session_key ){
                case 'adm_credential':
                    $qb->addWhere('u.admin = 1');
                break;
            }
        }

        $query = $qb->getQuery();
        
        return $query->getOneOrNullResult();
        
    }
    
    
    
    public function validateUser($id,  $token ){
        
        $qb = $this->_em->createQueryBuilder();
        
        $qb->Select(array(
                'partial u.{ id, first_name, last_name, company, image, email, phone, type, status, token }',
            ))->From(   self::User, 'u')
            ->where('u.id = :id')
            ->andWhere('u.token = :token')
            ->andWhere('u.status = 1')
            ->setParameter('id', $id)
            ->setParameter('token', $token);
        
        $query = $qb->getQuery();
        
        return $query->getOneOrNullResult();
        
    }
   
    
    

}
