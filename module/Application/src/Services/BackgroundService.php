<?php namespace Application\Services;


class BackgroundService {
    
    
    /**
     *
     * @var type 
     */
    private $baner_path = '/img/headbanner/';
    
    
    /**
     *
     * @var type 
     */
    private static $html = "";
    
    
    /**
     * 
     */
    public function __construct() {
        
    }
    
    
    /**
     * 
     * @return \Application\Services\BackgroundService
     */
    public static function get(){
        return new BackgroundService;
    }
    
    
    
    /**
     * 
     * @param type $e
     */
    public static function Set( $e ){
        
        $BgService = new BackgroundService();
        
        switch ( $e->controller){
            
            case 'Imedia\RealEstate\Home\Controller\HomeController':
                $BgService->homeBanner($e);
            break;
            default :
                $BgService->defaultBanner($e);
            break;
        }
    }
    
    
    
    
    
    /**
     * 
     * @param type $e
     */
    private function homeBanner( $e ){
        
        $this->defaultBanner( $e );
        
    }
    
    
    
    
    
    /**
     * 
     * @param type $e
     */
    private function defaultBanner( $e ){
        
        $bannerList = [
            'slide-1.jpg'
        ];
        
        
        $html = '<div class="headbanner">';
        
        foreach ($bannerList as $img){
            $html .= '<img src="'.($this->baner_path . $img).'" />';
        }
        
        $html .= '</div>';
        
        self::$html = $html;
    }
    
    
    
    
    
    /**
     * 
     * @return type
     */
    public function  html(){
        
        return self::$html;
        
    }
    
}