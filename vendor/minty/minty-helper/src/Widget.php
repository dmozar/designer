<?php namespace Minty\Helper;

class Widget {
    
    
static function Cretate($name, $id, $content, $options = null){
        
$o = '';
if($options){
    $o = '<span class="dropdown" >' . PHP_EOL;
    $o.= '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>' . PHP_EOL;
    $o.= '<ul class="dropdown-menu" role="menu">' . PHP_EOL;
    foreach ($options as $n => $link){
        
        $o.= '<li><a href="'.$link.'">'.$n.'</a></li>' . PHP_EOL;
        
    }
    $o.= '</ul>' . PHP_EOL;
    $o.= "</span>" . PHP_EOL;
}
        
$html = <<<EOD
<div class="widget">
    <div class="widget-header">
        <h3>%s</h3>
            <div class="widget-head-options">
                $o
                <span class="toggle active" data-toggle="collapse" data-target="#%s"></span>
            </div>
        </div>
        <div class="widget-content collapse in" id="%s">%s</div>
    </div>
EOD;

return sprintf( $html, $name, $id, $id, $content);

}
    
    
}
