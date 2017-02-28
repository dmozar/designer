<?php namespace Imedia\User\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Imedia\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", length=10, nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;
    
    /** @ORM\Column(type="string", length=30, nullable=false) */
    public $first_name;
    
    /** @ORM\Column(type="string", length=30, nullable=false) */
    public $last_name;
    
    /** @ORM\Column(type="string", length=120, nullable=false) */
    public $company;
    
    /** @ORM\Column(type="string", length=120, nullable=false) */
    public $email;
    
    /** @ORM\Column(type="string", length=120, nullable=false) */
    public $password;
    
    /** @ORM\Column(type="string", length=30, nullable=false) */
    public $phone;
    
    /** @ORM\Column(type="string", length=60, nullable=false) */
    public $image;
    
    /** @ORM\Column(type="smallint", length=2, nullable=false, options={"default":0}) */
    public $type;
    
    /** @ORM\Column(type="smallint", length=4, nullable=false, options={"default":0}) */
    public $status;
    
    /** @ORM\Column(type="string", length=255, nullable=false) */
    public $token;
    
    /** @ORM\Column(type="smallint", length=2, nullable=false, options={"default":0}) */
    public $admin;

    
    public function __construct() {

    }
    
    public function getID(){ return $this->id; }
    public function getAd(){ return $this->ad; }
    public function setAd( Ad $ad ){ $this->ad = $ad; }
    public function getFirstName(){ return $this->first_name; }
    public function setFirstName($val){ $this->first_name = $val; }
    public function getLastName(){ return $this->last_name; }
    public function setLastName($val){ $this->last_name = $val; }
    public function getFullName(){ return $this->getFirstName() . ' ' . $this->getLastName(); }
    public function getCompany(){ return $this->company; }
    public function setCompany($val){ $this->company = $val; }
    public function getEmail(){ return $this->email; }
    public function setEmail( $email ){ $this->email = $email; }
    public function getPassword(){ return $this->password; }
    public function setPassword($password){ $this->password = md5($password . APP_SECRET_KEY); }
    public function setPhone($phone){ $this->phone = $phone; }
    public function getPhone(){ return $this->phone; }
    public function setImage( $image ){ $this->image = $image; }
    public function getImage(){ return $this->IsNotEmpty($this->image) ? assets_url('img/users/'.$this->image) : assets_url('img/users/default.jpg'); }
    public function setType($type){ $this->type = $type; }
    public function getType(){ return $this->type; }
    public function getStatus(){ return $this->status; }
    public function setStatus($status){ $this->status = $status; }
    public function setToken($token){ $this->token = $token; }
    public function getToken(){ return $this->token; }
    public function getAdmin(){ return $this->admin; }
    public function setAdmin($val){ $this->admin = $val; }
    
}