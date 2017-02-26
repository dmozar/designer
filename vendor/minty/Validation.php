<?php namespace Minty;



class Validation {
    
    
     /**
     *
     * @var type 
     */
    public $error;
    
    
    /**
     *
     * @var type 
     */
    public $field;
    
    
    /**
     *
     * @var type 
     */
    private $inputs = [];
    
    
    
    /**
     *
     * @var type 
     */
    private $values = [];
    
    
    
    /**
     *
     * @var type 
     */
    private $value;
    
    
    
    /**
     *
     * @var type 
     */
    private $valueList = [];
    
    
    
    /**
     *
     * @var type 
     */
    private $errors = [
        'emtpy'         => '%s filed cannot be empty!'
        
    ];
    
    
    
    
    /**
     * 
     */
    public function __construct( ) {
        $this->errors = [];
    }
    
    
    
    /**
     * 
     * @return \Minty\Validation
     */
    public static function get(){
        return new Validation;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getValue(){
        return $this->value;
    }
    
    
    
    /**
     * 
     * @param type $inputs
     * @return \Minty\Validation
     */
    public function setInputs( $inputs ){
        
        $this->inputs = $inputs;
        return $this;
    }
    
    
    
    /**
     * 
     * @param type $values
     * @return \Minty\Validation
     */
    public function setValues( $values ){
        
        $this->values = $values;
        return $this;
    }
    
    
    /**
     * 
     * @return type
     */
    public function getValues(){
        return $this->valueList;
    }
    
    
    
    /**
     * 
     * @param type $validate
     * @return \Minty\Validation
     */
    public function validate( $validate = [] ){
        
        $v = [];
        
        foreach ($validate as $k => $r){
            $v[language($r)] = $r;
        }
        
        $validate = $v;
        
        foreach ($this->inputs as $key => $property){
            
            if(in_array($key, $validate)) {
                
                $this->field = $key;

                if( empty(@$property[0]) ){
                    $this->valueList[$key] = 
                            array_key_exists($key, $this->values) 
                            ? $this->values[$key] 
                            : null;
                    $this->value = $this->valueList[$key];
                } else {

                    $name = isset($property[2]) ? $property[2] : 'Field';
                    
                    if( ! array_key_exists(language($key), $this->values)){
                        $this->error = sprintf( language('empty'), language($name)); 
                        return $this;
                    } else {
                        $val = $this->values[language($key)]; $this->value = $val;
                    }

                    $p = explode('|', @$property[0]);

                    $isvalid = true;

                    foreach ($p as $rule){

                        switch ($rule){

                            case 'name':
                                $isvalid = $this->validateName( $val );
                            break;
                            case 'email':
                                $isvalid = $this->validateEmail( $val );
                            break;
                            case 'phone':
                                $isvalid = $this->validatePhone( $val );
                            break;
                            case 'number':
                            case 'integer':
                            case 'int':
                            case 'numeric':
                                $isvalid = $this->validateInt( $val );
                            break;
                            case 'url':
                                $isvalid = $this->validateUrl( $val );
                            break;
                            case 'float':
                            case 'decimal':
                                $isvalid = $this->validateFloat( $val );
                            break;
                            case 'date':
                                $isvalid = $this->validateDate( $val );
                            break;
                            case 'datetime':
                                $isvalid = $this->validateDateTime( $val );
                            break;
                            case 'string':
                                $isvalid = $this->validateString( $val );
                            break;
                            case 'alphanum':
                                $isvalid = $this->validateAlphaNumString( $val );
                            break;
                            case 'price':
                                $isvalid = $this->validatePrice( $val );
                            break;
                            case 'required':
                                $isvalid = $this->isNotEmpty( $val, @$property[2] );
                            break;
                        }


                        if(! $isvalid ) {
                            $this->error = language(@$property[1]);
                            return $this;
                        }
                        if(strpos($rule, '@') === 0){
                            $a = str_replace('@', '', $rule);
                            $isvalid = @$this->values[$a] == $val && $isvalid ? true : false;
                        }
                        if(strpos($rule, 'max') === 0){
                            $a = str_replace('max', '', $rule);
                            if(strlen($val) > $a){
                                $isvalid = false;
                                $this->error = sprintf( language('maxerror'), language(@$property[2]), $a );
                                return $this;
                            }
                        }
                        if(strpos($rule, 'min') === 0){
                            $a = str_replace('min', '', $rule);
                            if(strlen($val) < $a){
                                $isvalid = false;
                                $this->error = sprintf( language('minerror'), language(@$property[2]), $a );
                                return $this;
                            }
                        }
                        if(! $isvalid ) {
                            $this->error = language(@$property[1]);
                            return $this;
                        }
                        $this->valueList[$key] = $val;
                    }
                }

                if( ! $isvalid ){
                    $this->error = language(@$property[1]);
                    return $this;
                }
            }
        }
        return $this;
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateName($str){
        
        if( ! $str ) return true;

        return preg_match('/^[a-zA-Z-.\s]+$/',$str);
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateEmail($str){
        
        if( ! $str ) return true;
        
        $e = explode('@', $str);             

        if(count($e) != 2)
            return false;

        return true;
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateUrl($str){
        
        if( ! $str ) return true;

        if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$str)) 
            return false;
        
        return true;
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateInt($str){
        
        if( ! $str ) return true;

        if(! filter_var($str, FILTER_VALIDATE_INT)) 
            return false;
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateFloat($str){
        
        if( ! $str ) return true;
        
        if(!filter_var($str, FILTER_VALIDATE_FLOAT))
            return false;
        
        return true;
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @param type $format
     * @return boolean
     */
    function validateDate($str, $format = 'd.m.Y'){
        
        if( ! $str ) return true;
    
        if(! \DateTime::createFromFormat($format, $str))
            return FALSE;
        
        return true;
    }
    
    
    
    
    /**
     * 
     * @param type $str
     * @param type $format
     * @return boolean
     */
    function validateDateTime($str, $format = 'd.m.Y H:i'){
        
        if( ! $str ) return true;
    
        if(! \DateTime::createFromFormat($format, $str))
            return false;
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function validateString($str){
        
        if( ! $str ) return true;

        if(strlen($str) != strlen(strip_tags($str)))
            return false;
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $string
     * @return boolean
     */
    function validateAlphaNumString($string){
        
        if( ! $string ) return true;
        
        if (!ctype_alpha($string)) 
            return false;
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $string
     * @return boolean
     */
    function validatePhone( $string ) {
        
        if( ! $string ) return true;
        
        if ( ! preg_match( '/^[+]?([\d]{0,3})?[\(\.\-\s]?([\d]{3})[\)\.\-\s]*([\d]{3})[\.\-\s]?([\d]{4})$/', $string ) )
           return false;
        
        return true;
    }
    
    
    
    /**
     * 
     * @param type $string
     * @return boolean
     */
    function validatePrice( $string ){
        
        if( ! $string ) return true;
        
        return preg_match('/^[+\-]?\d+(\.\d+)?$/', $string) ? true : false;
        
    }
    
    
    
    /**
     * 
     * @param type $str
     * @return boolean
     */
    function isNotEmpty($str, $name){
        
        if( strlen(trim($str)) == 0){
//            
//            echo language($name) . PHP_EOL;
//            echo $str . PHP_EOL;
//            echo '---------------' . PHP_EOL;
            
            $this->error = sprintf (language('empty'), language($name));
            return false;
        }
        
        return true;
    }

    
    
    public function SanitizeString($str){
        return filter_var( $str, FILTER_SANITIZE_STRING);
    }
    
    
    
    /**
     * 
     * @param type $content
     * @param type $simple
     * @return boolean
     */
    function prepareContent(& $content, $simple = false){
        
        $config = \HTMLPurifier_Config::createDefault();
        $config->set('Core.Encoding', 'UTF-8');
        $config->set('HTML.Doctype', 'HTML 4.01 Transitional');
        if($simple){
        $config->set('HTML.Allowed', "p,u,i,strong,span[style],br,em");
        } else {
            $def=$config->getHTMLDefinition(true);
            $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
        }
        
        $purifier = new \HTMLPurifier($config);
        $content = $purifier->purify( $content );
        
        if(! $content ){
            return false;
        } 
        
        return $content;
    }
    
    
}
