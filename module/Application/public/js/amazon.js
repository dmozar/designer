

var EditorClass = function(){

    /**
     * 
     * @type EditorClass
     */
    var self = this;
    
    /**
     * 
     */
    this.etc;
    
    /**
     * 
     */
    this.selection = null;
    
    /**
     * 
     */
    this.selectionRange;
    
    /**
     * 
     */
    this.selectionText;
    
    
    /**
     * 
     */
    this.resolutions = {
        'desktop' : {
            'width' : 1123,
            'history': {},
            'hisindx': 0
        },
        'tablet' : {
            'width' : 1024,
            'history': [],
            'hisindx': 0
        },
        'mobile-hd' : {
            'width' : 768,
            'history': [],
            'hisindx': 0
        },
        'mobile' : {
            'width' : 480,
            'history': [],
            'hisindx': 0
        },
        'mobile-small' : {
            'width' : 340,
            'history': [],
            'hisindx': 0
        }
    };
    
    this.currentResolution = "desktop";
    
    this.maxHistory = 20;
    
    
    
    /**
     * 
     */
    this.controls = {
        'undo'      : '#ec-undo',
        'redo'      : '#ec-redo',
        'pointer'   : '#ec-pointer',
        'text'      : '#ec-text',
        'image'     : '#ec-img',
        'link'      : '#ec-link',
        'delete'    : '#ec-delete',
        'background': '#ec-background',
        'video'     : '#ec-video'
    };
    
    this.clickedElement = null;
    
    /**
     * 
     * @returns {undefined}
     */
    var __initialize = function(){

        if(document.contentEditable != undefined && document.execCommand != undefined){ 
            alert("HTML5 Document Editing API Is Not Supported"); } else { Init(); }
    };
    
    /**
     * 
     * @returns {undefined}
     */
    var Init = function(){
        
        self.etc = $('.editor');
        self.etc.on('click', self.removeActiveControl );
        self.etc.bind("mouseup", self.SelectorMouseup );
        self.Controls();
        self.Events();
        self.SizeHandler();
        self.Pin();
        self.Reolution();
       
    };
    
    
    this.Reolution = function(){
        
        var select = self.etc.closest('.editor-wrap').find('.resolution select');
        $.each(self.resolutions, function(i,e){
            select.append('<option value="'+i+'">'+i+'</option>');
        });
        select.on('change', function(){
            var t = ($(this).find('option:selected').val());
            var resolution = self.resolutions[t];
            $('#width').val(resolution.width).trigger('change');
            self.currentResolution = t;
        });
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Controls = function(){
        
        self.controls.undo          = $(self.controls.undo);
        self.controls.redo          = $(self.controls.redo);
        self.controls.pointer       = $(self.controls.pointer);
        self.controls.text          = $(self.controls.text);
        self.controls.image         = $(self.controls.image);
        self.controls.link          = $(self.controls.link);
        self.controls.delete        = $(self.controls.delete);
        self.controls.background    = $(self.controls.background);
        self.controls.video         = $(self.controls.video);
        
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Pin = function(){
        $('body').find('.pin').on('click', function(){
            var p = $(this);
            var m = p.closest('.pinparent');
            m.toggleClass('pinstate');
            
        });
    };
    
    
    this.HistoryPushState = function(){
        var code = self.etc.clone(true,true);
        self.resolutions[self.currentResolution].history[self.resolutions[self.currentResolution].hisindx] = code;
        self.resolutions[self.currentResolution].hisindx++;
    }; 
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.SizeHandler = function(){
        
        $('#width').val( self.etc.outerWidth() );
        $('#height').val( self.etc.outerHeight() );
        
        var checkHeightTimer = function(){
            if( ! $('#height').is(':focus') ){ $('#height').val( self.etc.outerHeight() ); }
        };
        
        setInterval(checkHeightTimer, 100);
        
        $('#width').on('change', function(){ 
            var w = $(this).val();
            self.etc.width( w ); self.etc.closest('.editor-wrap').width( w ); 
            setTimeout(function(){
                self.resolutions[self.currentResolution]['width'] = w;
            },120,w);
            
        });
        $('#height').on('change', function(){ self.etc.height( $(this).val() ); });
        $('#zindex').on('change', function(){ var a = self.etc.find('.group-element.active'); if(a.length){ a.css('z-index', $(this).val() ); }});
        
        $('#ewidth').on('change', function(){
            var a = self.etc.find('.group-element.active');
            if(a.length){
                a.width( $(this).val() );
                if(a.data('action') == 'Editor.EditImage') { a.find('img').width( $(this).val() ); }
                if(a.data('action') == 'Editor.EditVideo') { a.find('iframe').width( $(this).val() ); }
                $('#eheight').val(a.height());
                self.HistoryPushState();
            }
        });
        
        $('#eheight').on('change', function(){
            var a = self.etc.find('.group-element.active');
            if(a.length){
                a.height( $(this).val() );
                if(a.data('action') == 'Editor.EditImage') { a.find('img').height( $(this).val() ); }
                if(a.data('action') == 'Editor.EditVideo') { a.find('iframe').height( $(this).val() ); }
                $('#ewidth').val(a.width());
                self.HistoryPushState();
            }
        });
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Events = function(){
        self.controls.pointer.on( 'click', self.Pointer );
        self.controls.text.on   ( 'click', self.EditText );
        self.controls.image.on  ( 'click', self.EditImage );
        self.controls.link.on   ( 'click', self.EditLink );
        self.controls.delete.on ( 'click', self.Remove );
        self.controls.background.on ( 'click', self.Background );
        self.controls.video.on ( 'click', self.EditVideo );
        self.controls.undo.on ( 'click', self.Undo );
        self.controls.redo.on ( 'click', self.Redo );
    };
    
    
    this.Undo = function(){
        
        var code = self.resolutions[self.currentResolution].history[self.resolutions[self.currentResolution].hisindx - 1];
        if(code){
            if( code.length){
                self.resolutions[self.currentResolution].hisindx--;
                $('body').find('.editor').replaceWith( code );
                self.etc = $('body').find('.editor');
                self.etc.find('.group-element').each(function(i,e){
                    self.ElementBind( $(e) );
                });
            }
        }
       // self.etc.replaceWith(code);
        
    };
    
    this.Redo = function(){
        
        
        
    };
    
    
    this.ElementBind = function( $e ){
        
        var c = $e.clone(false,false);
        $e.replaceWith(c);
        $e = c;
        try {
            $e.draggable('destroy');
        }
        catch(e) {
            
        }
        $e.draggable({
            stop: self.HistoryPushState,
            disabled:false
        });

        $e.on('click', function(event){
            self.ElementListener( this );
        });
        
        $e.on('click', function( event){
            
            self.etc.find('.group-element').removeClass('active');
            
            $e.addClass('active');
            
            $('#zindex').val( $(this).css('z-index') );
            $('#ewidth').val( $(this).width() );
            $('#eheight').val( $(this).height() );
            
        });
        
        
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Remove = function(){ self.etc.find('.group-element.active').remove(); self.HistoryPushState(); };
    
    /**
     * 
     * @returns {undefined}
     */
    this.Pointer = function(){ self.etc.find('.group-element.active').removeClass('active'); };
    
    /**
     * 
     * @returns {undefined}
     */
    this.Background = function(){
        
        var a = self.etc.find('.group-element.active');
        
        if(a.length)
        {
            if( a.data('action') == 'Editor.EditImage'){
                var img = a.find('img');
                if(img.length){
                    var src = img.attr('src');
                    
                    a.css({
                        'background-image': 'url('+src+')',
                        'background-repeat': 'no-repeat',
                        'background-size': 'cover',
                        height: img.height(),
                        width: img.width()
                    });
                    
                    img.remove();
                    a.data('action','Edit.Background');
                    self.HistoryPushState();
                }
            } 
        }
    };
    
    /**
     * 
     * @returns {String}
     */
    this.UUID = function() {
        var s4 = function() { return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1); };
        return 'uuid_' + s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    };

    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.CancelPopWindow = function(e){ $(e).closest('.pop-window').remove(); };
    
    /**
     * 
     * @param {type} input
     * @returns {undefined}
     */
    this.previewImage = function(input){
      
        var reader = new FileReader();

        reader.onload = function (e) {
            var src = e.target.result;
            $(input).next('img').attr('src', src);
        };

        reader.readAsDataURL(input.files[0]);
    };
    
    
    /**
     * 
     * @param {type} str
     * @returns {Boolean}
     */
    this.ValidateLink = function( str ){
        var re = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/;
        return re.test( str );
    };
    
    /**
     * 
     * @param {type} str
     * @returns {Boolean}
     */
    this.ValidateEmpty = function( str ){ return str.trim() !== '' ? true : false; };
    
    /**
     * 
     * @returns {undefined}
     */
    this.removeActiveControl = function(){
        $.each(self.controls, function(i,e){
            if( ! $(e).hasClass('ignore') )
                $(e).removeClass('active');
        });
    };
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.ElementListener = function(e){
      
        var $e = $(e);
        
        self.clickedElement = $e;
        self.removeActiveControl();
        
        if( $e.is('a') ){
            self.controls.link.addClass('active');
        }
    };

    this.EditText = function( e ){
        var html = self.CloneTemplate('text');
        var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
        
        var id = self.UUID();
        var ta = $html.find('textarea');
        ta.attr('id', id);
        
        var element = self.etc.find('.group-element.active');
        
        if(element.length){
            ta.val(element.html());
            ta.data('update', element.attr('id') );
        }
        
        tinymce.init({
                selector: "#" + id,
                height: 500,
                plugins: [
                  "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
                  "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
                  "table contextmenu directionality emoticons template textcolor paste fullpage textcolor colorpicker textpattern"
                ],

                toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
                toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
                toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

                menubar: false,
                toolbar_items_size: 'small',

                style_formats: [{
                  title: 'Bold text',
                  inline: 'b'
                }, {
                  title: 'Red text',
                  inline: 'span',
                  styles: {
                    color: '#ff0000'
                  }
                }, {
                  title: 'Red header',
                  block: 'h1',
                  styles: {
                    color: '#ff0000'
                  }
                }, {
                  title: 'Example 1',
                  inline: 'span',
                  classes: 'example1'
                }, {
                  title: 'Example 2',
                  inline: 'span',
                  classes: 'example2'
                }, {
                  title: 'Table styles'
                }, {
                  title: 'Table row 1',
                  selector: 'tr',
                  classes: 'tablerow1'
                }],

                templates: [{
                  title: 'Test template 1',
                  content: 'Test 1'
                }, {
                  title: 'Test template 2',
                  content: 'Test 2'
                }],
                content_css: [
                  /*'//fast.fonts.net/cssapi/e6dc9b99-64fe-4292-ad98-6974f93cd2a2.css',
                  '//www.tinymce.com/css/codepen.min.css'*/
                ]
        });
        
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.EditImage = function(){
        var html = self.CloneTemplate('image');
        var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
    };
    
    
    
    this.EditVideo = function(){
        
        var html = self.CloneTemplate('video');
        var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
        
    };
    
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.EditLink = function( e ){
        
        var element = self.etc.find('.group-element.active');
        var src = null;
        
        if(element.length){
            
            var node = $(element.html());
            
            if( ! node.is('a')){
                src = null;
            } else {
                src = node.attr('href');
            }
            
            var html = self.CloneTemplate('link');
            var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
            $html.find('input[name=url]').val(src);
        }
    };
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.EditGroup = function( e ){ $(this).toggleClass('active'); };
    
    /**
     * 
     * @param {type} e
     * @param {type} x1
     * @param {type} x2
     * @param {type} x3
     * @param {type} s
     * @returns {undefined}
     */
    this.SubmitLink = function(e){

        var input = $(e).closest('.pop-window').find('input[name=url]');

        var url = input.val();
        
        var element = self.etc.find('.group-element.active');
        
        if(element.length){
            
            var cnt = $(element.html());
            if(cnt.length){
                if( ! cnt.is('a') ){
                    element.wrapInner('<a href="'+url+'" onclick="return false" /></a>');
                    self.HistoryPushState();
                } else {
                    if(url){
                        cnt.attr('href', url);
                        element.html( cnt );
                        self.HistoryPushState();
                    } else {
                        var html = cnt.html();
                        element.html(html);
                        self.HistoryPushState();
                    }
                }
            }
            self.CancelPopWindow(e);
        }
    };
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.SubmitText = function( e ){
        tinyMCE.triggerSave();
        var t = $(e).closest('.pop-window').find('textarea[name=text]');
        var id = self.UUID();
        
        if(t.data('update')){
            
            $('body').find('#' + t.data('update')).html(t.val());
            
        } else {
        
            var element = $('<div id="'+id+'" class="group-element" data-action="Editor.EditText">'+t.val()+'</div>');
            self.AddElement(element);
        }
        self.CancelPopWindow(e);
    };
    
    
    this.SubmitVideo = function( e ){
        
        var url = $(e).closest('.pop-window').find('input[name=url]').val();
        
        var code = $(e).closest('.pop-window').find('textarea[name=code]').val();
        
        if(url){
            
            var video_id = url.split('v=')[1];
            var ampersandPosition = video_id.indexOf('&');
            if(ampersandPosition != -1) {
              video_id = video_id.substring(0, ampersandPosition);
            }
            
            if(video_id){
                var code = '<iframe width="560" height="315" src="https://www.youtube.com/embed/'+video_id+'" frameborder="0" allowfullscreen></iframe>';
            }
        }
        
        if(code){
            var id = self.UUID();
            var element = $('<div id="'+id+'" class="group-element" data-action="Editor.EditVideo">'+code+'</div>');
            self.AddElement(element);
        }
        
        self.CancelPopWindow(e);
        
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.PreviewImage = function(e){
      
        var prv = $(e).parent().find('.img-prev');
        
        var src = $(e).val();
        
        var df  = $(e).data('src');
        
        prv.src = df;
        
        var Element = document.createElement('img');
            Element.onload = function(){
                 prv.attr('src', src);
            };
            Element.src = src;
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.SubmitImage = function(e){
        
         var url = $(e).closest('.pop-window').find('input[name=url]').val();
         
         var Element = document.createElement('img');
             Element.className += "editable-img";
             var id = self.UUID();
             Element.onload = function(){
                 var el = $('<div id="'+id+'" class="group-element" data-action="Editor.EditImage">'+Element.outerHTML+'</div>');
                 self.AddElement(el);
             };
             Element.src = url;
             
        self.CancelPopWindow(e);
    };
    
    
    /**
     * 
     * @param {type} element
     * @param {type} s
     * @returns {window.$|$}
     */
    this.AddElement = function( element, s){
      
        var $e = $(element);
        
        if(! s) {
            self.etc.append(element);
            self.HistoryPushState();
        }
        else  {
            try{
                s.replaceWith($e);
                self.HistoryPushState();
            } catch( e ){
                self.etc.append(element);
                self.HistoryPushState();
            }
        }

        self.ElementBind( $e );
        
        return $(element);
    };
    
    
    /**
     * 
     * @param {type} target
     * @returns {EditorClass.CloneTemplate.html}
     */
    this.CloneTemplate = function(target){
        
        var t = '.template-' + target;
        
        var html = {
            'content' : null,
            'title' : null,
            'size' : null,
            'buttons' : null
        };
        
        html.content = $('.templates ' + t).clone();
        html.title = $('.templates ' + t).data('title');
        html.size = $('.templates ' + t).data('size');
        html.buttons = $('.templates '+t+' .template-buttons');
        html.content.find('.template-buttons').remove(); 

        return html;
    };
    
    /**
     * 
     * @param {type} title
     * @param {type} content
     * @param {type} size
     * @param {type} buttons
     * @returns {EditorClass.PopWindow.$html|window.$|$}
     */
    this.PopWindow = function(title, content, size, buttons){
        
        var html = '<div class="pop-window '+size+'"><div class="pop-title noselect">'+title+'</div><div class="pop-content">'+content.html()+'</div><div class="pop-buttons">'+buttons.html()+'</div></div>';
        var $html = $(html);
        $('body').append($html);
        
        var w = $html.width();
        var h = $html.height();
        
        $html.css({
            'margin-left': -(w/2)+'px',
            'margin-top': -((h/2)+100)+'px'
        });
        
        $html.draggable({
            containment: "window"
        });
        
        return $html;
    };
    
    
    
    
$(function(){ __initialize(); });};

var Editor = new EditorClass();



