
var SwicthClass = function(){
    
    var self = this;
    
    this.e;
    
    this.wrap;
    
    this.Init = function ( e ){
        
        $.each(e,function(i,a){
            var $a = $(a);
            if(!$a.hasClass('inited')){
                $a.addClass('inited');
                var sc = new SwicthClass();
                    sc.initialize( $a );
            }
            
        });
    };
    
    this.initialize = function( e ){
        
        this.e = e;
        
        
        this.e.wrap( '<div class="switch-wrap" />' );
        this.wrap = this.e.parent();
        
        var fn = e.data('list').split(',');
        var wide = 100/(fn.length + 1);
        $.each( fn, function(i,d){
           
            var value = e.data(d);
            var name  = e.data(d+'name');
           
            var opt = $('<span class="switch-opt '+(value == self.e.val() ? 'active':'')+' animate" data-value="'+value+'" style="width:'+wide+'%;left:'+(wide/2)+'%;"><em>'+name+'</em><i class="dot"></i><b class="pulse"></b></span>');
            self.wrap.append(opt);
            opt.on('click', self.__click_event );
            
            
        });
        
                    
            
            
        
    };
    
    
    this.__click_event = function(){
        
        var a = $(this);
        
        self.wrap.find('.switch-opt').removeClass('active');
        
        a.addClass('active');
        
        self.e.val( a.data('value') ).trigger('change');
        
        console.log(self.e.val())
        
    };
    
};





$.fn.switch = function(){
    
    var Switch = new SwicthClass();
        Switch.Init( this );  
    
};


$(function(){
    
    $('input.switch-el[type=text]').switch();
    
});