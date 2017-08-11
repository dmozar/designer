var AjaxCallClass = function(){
    
    var a = this;
    
    this.callback = null;
    
    this.Request = function( keyService, data, callback){
      
        if(callback !== undefined)
            this.callback = callback;
      
        if( ! data === undefined ) data = {};
        
        data.keyService = keyService;
      
        $.ajax({
        'method': "POST",
        'url'   : "/proxy",
        'data'  : data
      })
        .done(function( response ) {

            if(response.callback)
                a.callback = response.callback;
            
            eval(a.callback)(response);
        });
        
    };
};


/**
 * Ajax Form
 * -----------------------------------------------------------------------------
 * @returns {undefined}
 */
var FormClass = function(){
    
    /**
     * 
     * @type FormClass
     */
    var self = this;
    
    /**
     * 
     * @type type
     */
    this.f;
    
    
    this.values = null;
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Init = function(){
        $(function(){
            $('body').find('.ajaxform').each(function(){
                var e = $(this);
                if( ! e.hasClass('inited')) {
                    e.addClass('inited');

                    var $f = new FormClass();
                        $f._initialize( e );
                }

            });
            
            
            $('body').find('.submit-btn').each(function(){
                $(this).unbind().on('click', Form.FormSubmitEvent);
            });
            
            
        });
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this._initialize = function( e ){
        
        self.f = e;

        self.f.ajaxForm({
            beforeSubmit: self._before,
            success: self._success
        });
        
    };
    
    
    /**
     * 
     * @param {type} arr
     * @param {type} $form
     * @param {type} options
     * @returns {undefined}
     */
    this._before = function(arr, $form, options){
        console.log( $form, arr, options);
        /*if($form.data('before')){
            eval($form.data('before'))($form);
        }*/
    };
    
    
    
    /**
     * 
     * @param {type} response
     * @returns {undefined}
     */
    this._success = function(response){

        self.values = null;
        
        if(response.clear && ! response.callback){
            location.reload();
        }
        
        if (typeof response === 'string' || response instanceof String){
            try{
                $(self.f.data('target')).replaceWith(response);
            } catch(e){
                
            }
            self.Init();
        } else {
            
            if(response.callback){
                eval(response.callback)(response);
            }
        }
    };
    
    
    
    this.FormSubmitEvent = function(){

            if (typeof(tinyMCE) != "undefined") {
                tinyMCE.triggerSave();
            }

            var $f = $(this).closest('form');

            var data = {};

            var is_valid = true;


            /* input, custom select */
            $f.find('input').each(function(){

                var s = $(this);

                if(s.hasClass('custom-select')) {
                    if( ! s.data('value') && s.attr('required') ){

                        s.addClass('error');
                        $('#modal-text').text( s.data('error') );
                        s.val('').trigger('keyup');
                        $('.modal').modal({});
                        is_valid = false;
                        return false;
                    } else {
                        s.removeClass('error');
                        data[s.attr('name')] = s.data('value');
                    }
                } else {

                    if(s.attr('type') === 'text' || s.attr('type') == 'email' || s.attr('type') == 'password' || s.attr('type') == 'number'){

                        if(s.attr('required') && s.val().replace(/ /g,'') == ''){
                            s.addClass('error');
                            location.href = "#" + s.attr('id');

                            is_valid = false;

                            return false;
                        } else {
                            s.removeClass('error');
                            data[s.attr('name')] = s.val();
                        }
                    }



                }
            });


            /* textarea , tynimce */
            $f.find('textarea').each(function(){

                var s = $(this);
                if(s.hasClass('tiny')){
                    if( s.val().replace(/ /g,'') == '' && s.attr('required')){

                        s.parent().find('.mce-panel').addClass('error');

                        $('#modal-text').text( s.data('error') );
                        $('.modal').modal({});

                        is_valid = false;

                        return false;
                    } else {
                        s.parent().find('.mce-panel').removeClass('error');
                        data[s.attr('name')] = s.val();
                    }
                } else {
                    if(s.val().replace(/ /g,'') == '' && s.attr('required')){
                        s.addClass('error');
                        location.href = "#" + s.attr('id');
                        return false;
                    } else {
                        s.removeClass('error');
                        data[s.attr('name')] = s.val();
                    }
                }
            });


            /* Select */
            $f.find('select').each(function(){

                 var s = $(this);
                 var a = s.find('option:selected');
                 var n = s.attr('name');
                 var id = s.attr('id');

                 if(s.attr('required') && ! a.val()){
                     s.addClass('error');
                     location.href = "#" + id;

                     is_valid = false;

                     return false;
                 } else {
                     data[n] = a.val();
                 }
            });

            if(is_valid){
                Form.values = data;
                
                if($f.data('before')){
                    eval($f.data('before'))($f);
                }

                $f.ajaxForm({
                    data: Form.values,
                    beforeSubmit: self._before,
                    success: self._success
                });

                $btn = $('<input type="submit" />');

                $f.append($btn);

                $btn.trigger('click');

                $btn.remove();
            }
    };

this.Init()};


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


$(function(){
    
    var sb = $('#searchbar');
    
    if(sb.length){
        
        sb.find('input').on('keyup', function(event){
            
            if(event.which == 13){
                
                var params = {};
                var link = sb.data('url');
                
                var hased = false;
                
                sb.find('input').each(function(){
                    var i = $(this);
                    var v = i.val();
                    if(v.trim()){
                        params[i.attr('name')] = i.val();
                        hased = true;
                    }
                });
                
                if(hased){
                    $.cookie('search', JSON.stringify(params), { expires: 7, path: '/' });
                } else {
                    $.removeCookie('search', { path: '/' });
                }
                
                window.location = link;
                
            }
            
        });
        
    }
    
});




var Redirect = new RedirectClass();
var AjaxCall = new AjaxCallClass();
var Form     = new FormClass();


