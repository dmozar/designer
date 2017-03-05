

/**
 * 01.03.2017
 * @author Damir Mozzart
 * @email dmozar@gmail.com
 * 
 *  Responsive Visual page generator javascript component
 *
 * @returns {RedirectClass}
 */
var RedirectClass = function(){
    
    var self = this;

    // <editor-fold defaultstate="collapsed" desc="__init">
    var __init = function(){
        if( $('.redirect').length ){
            var r = $('.redirect');
            if(r.data('redtype') == 'countdown'){
                var url = r.data('redirect');
                self.countdown(url);
            }
        }
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="countdown">
    this.countdown = function(url){
        
        var countdownElement,
        seconds = 10,
        second = 0,
        interval = null;
        
        countdownElement = document.getElementById('countdown');
        
        interval = setInterval(function() {
            countdownElement.innerHTML = (seconds - second);
            if (second >= seconds) {
                window.location.href = url;
                clearInterval(interval);
            }
            second++;
        }, 1000);
        
    };
    // </editor-fold>

$(function(){ __init(); });};



var Redirect = new RedirectClass();


