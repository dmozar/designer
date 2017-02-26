function __( e ){
    e = $(e);
    return !e.length ? null : e;
} 


var ToggleClass = function(){
    
    
    this.Init = function(){
        
        $('body').find('.toggle').each(function(){
            
            var tg = $(this);
            
            if( ! tg.hasClass('inited') ){
                tg.addClass('inited');
                tg.on('click', function(){
                    $(this).toggleClass('active');
                });
            }
            
        });
        
    };
    
    
$(function(){  Toggle.Init(); });};



/**
 * NavigationClass
 * -----------------------------------------------------------------------------
 * @returns {NavigationClass}
 */
var NavigationClass = function(){
    
    var self = this;
    
    this.n;
    
    this.m;
    
    this.Init = function(){
        
        this.n = __('.navigation');

        if( ! this.n) return false;
        if( this.n.hasClass('inited') )return false;
        
        this.n.addClass('inited');
        
        this.__initialize();
    };
     
    
    this.__initialize = function(){
        
        this.m = this.n.find('.nav-group .nav-item > span');

        if(this.m.length){
            this.m.on('click', function(){

                var a = __(this);
                var s = a.parent().find('.nav-subgroup');
                    s.show();
                var h = 0;
                if(!a.parent().hasClass('active')){
                    
                    self.n.find('.nav-group .nav-item.active').each(function(){
                        
                        var f = __(this);
                            f.removeClass('active');
                            f.find('.nav-subgroup').animate({
                                height:0
                            },200,
                            function(){
                                f.find('.nav-subgroup').hide();
                            });
                        
                    });
                    
                    s.css('height','auto');
                    h = s.height(); console.log(h)
                    s.height(0);
                    s.stop().animate({
                        height: h
                    },200, function(){
                        a.parent().toggleClass('active');
                    });
                } else {
                    s.stop().animate({
                        height: h
                    },200, function(){
                        a.parent().toggleClass('active');
                        s.hide();
                    });
                }
            });
        }
    };

$(function(){ Navigation.Init(); });}; 



var ButtonManagerClass = function(){
    
    var self = this;
    
    this.Init = function(){
        
        $('body').find('button[data-redirect]').on('click', this.Redirect );
        
    };
    
    
    this.Redirect = function(){
        
        var e = $( $(this).data('redirect') );
        
        if(e.length){
            
            if(e.is('select')){
                var url = e.find('option:selected').data('url');
            } else {
                var url = e.data('url');
            }
            
            if(url){
                window.location.href = url;
            } else {
                e.addClass('error');
            }
            
        }
        
    };
    
    
    $(function(){ ButtonManager.Init(); });
};




var Navigation = new NavigationClass();
var Toggle = new ToggleClass();
var ButtonManager = new ButtonManagerClass();