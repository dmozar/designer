<?php namespace Imedia\Designer\Entity;

use Doctrine\ORM\Mapping as ORM;
use Imedia\User\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Imedia\Designer\Repository\DesignRepository")
 * @ORM\Table(name="design")
 */
class Design {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    
    /** @ORM\Column(type="string", length=60, nullable=false) */
    public $img;
    
    /** @ORM\Column(type="string", length=250, nullable=false) */
    public $title;
    
    /** @ORM\Column(type="text", nullable=false) */
    public $json;
    
    /** @ORM\Column(type="datetime", nullable=false) */
    public $created;
    
    /** @ORM\Column(type="datetime", nullable=false) */
    public $updated;
    

    /**
     * @ORM\ManyToOne(targetEntity="Imedia\User\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    public $user;
    
    public function __construct() {

        $date = new \DateTime();
        
        $this->created = $date;
        $this->updated = $date;
    }
    
    public function getID(){ return $this->id; }
    
    public function getImg(){ return $this->img; }
    public function setImg($img){ $this->img = $img; }
    
    public function getJson(){ return $this->json; }
    public function setjson( $json ){ $this->json = $json; }
    
    public function getCreated( $format = 'd.m.Y h:i:s'){ return $format ? $this->created->format($format) : $this->created; }
    public function getUpdated( $format = 'd.m.Y h:i:s'){ return $format ? $this->updated->format($format) : $this->updated; }
   
    public function getUser(){ return $this->user; }
    public function setUser( User $User ){ $this->user = $User; }
    
    public function getTitle(){ return $this->title; }
    public function setTitle( $title ){ $this->title = $title; }
    
}