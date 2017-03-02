/**
 * 01.03.2017
 * @author Damir Mozzart
 * @email dmozar@gmail.com
 * 
 *  Responsive Visual page generator javascript component
 *    
 * @returns {EditorClass}
 */
var EditorClass = function(){

    /**
     * 
     * @type EditorClass
     */
    var self = this;
    
    /**
     * 
     */
    this.projectId = null;
    
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
    this.ActiveElement = null;
    
    /**
     * 
     */
    this.lastSelection = null;
    
    /**
     * 
     */
    this.selectionLimit = 20;
    
    /**
     * 
     */
    this.lastHistoryIndx = 0;
    
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
            'width' : 1120,
            'height': 300,
            'history': {},
            'hisindx': 0
        },
        'tablet' : {
            'width' : 1024,
            'height': 300,
            'history': [],
            'hisindx': 0
        },
        'mobile-hd' : {
            'width' : 768,
            'height': 300,
            'history': [],
            'hisindx': 0
        },
        'mobile' : {
            'width' : 480,
            'height': 300,
            'history': [],
            'hisindx': 0
        },
        'mobile-small' : {
            'width' : 340,
            'height': 300,
            'history': [],
            'hisindx': 0
        }
    };
    
    /**
     * 
     */
    this.currentResolution = "desktop";
    
    /**
     * 
     */
    this.maxHistory = 20;
    
    /**
     * 
     */
    this.grid = null;
    
    /**
     * 
     */
    this.gridIndx = 0;
    
    /**
     * 
     */
    this.gridStep = 40;

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
        'video'     : '#ec-video',
        'magic'     : '#ec-magic',
        'expand'    : '#ec-expand',
        'grid'      : '#ec-grid',
        'center'    : '#ec-center',
        'save'      : '#btnsv',
        'saveasnew' : '#btnsn',
        'compile'   : '#btngc'
    };
    
    /**
     * 
     */
    this.clickedElement = null;

    // <editor-fold defaultstate="collapsed" desc="__initialize">
    var __initialize = function(){

        if(document.contentEditable != undefined && document.execCommand != undefined){ 
            alert("HTML5 Document Editing API Is Not Supported"); } else { Init(); }
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Init">
    var Init = function(){
        
        self.etc = $('.editor');
        self.etc.on('click', self.removeActiveControl );
        self.etc.bind("mouseup", self.SelectorMouseup );
        self.Controls();
        self.Events();
        self.SizeHandler();
        self.Pin();
        self.Resolution();
        self.LoadProject();
        
    };
    // </editor-fold> 

    // <editor-fold defaultstate="collapsed" desc="Controls">
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
        self.controls.magic         = $(self.controls.magic);
        self.controls.expand        = $(self.controls.expand);
        self.controls.grid          = $(self.controls.grid);
        self.controls.center        = $(self.controls.center);
        self.controls.save          = $(self.controls.save);
        self.controls.saveasnew     = $(self.controls.saveasnew);
        self.controls.compile       = $(self.controls.compile);
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Events">
    this.Events = function(){
        self.controls.pointer.on( 'click', self.Pointer );
        self.controls.text.on   ( 'click', self.EditText );
        self.controls.image.on  ( 'click', self.EditImage );
        self.controls.link.on   ( 'click', self.EditLink );
        self.controls.delete.on ( 'click', self.Remove );
        self.controls.background.on ( 'click', self.EditBackground );
        self.controls.video.on ( 'click', self.EditVideo );
        self.controls.undo.on ( 'click', self.Undo );
        self.controls.redo.on ( 'click', self.Redo );
        self.controls.magic.on ( 'click', self.Magic );
        self.controls.expand.on ( 'click', self.Expand );
        self.controls.grid.on ( 'click', self.Grid );
        self.controls.center.on ( 'click', self.CenterObject );
        self.controls.save.on ( 'click', self.SaveProject );
        self.controls.saveasnew.on ( 'click', self.SaveProject );
        self.controls.compile.on( 'click', self.Compile );
        
        $('.editor-wrap, .btn-wrap').css('width',self.resolutions.desktop.width+'px');
        
        $('.editor-controlls li').on('click', function(){
            setTimeout(function(){
                self.ElementListener( self.etc.find('.group-element.active') );
            },50);
        } );
        
        $('#angle').on('change', function(){ self.Rotate(this);});
        
        $('.efl').on('click', self.RotateRight);
        $('.efr').on('click', self.RotateLeft);
        
        self.ElementListener( null );
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Compile">
    this.Compile = function(){
        
        self.DisableEditor();
        
        var items = new Array();
        
        $.each( self.resolutions, function(i,e) {
                var designer = e.history[e.hisindx-1];
                var d = e.history[e.hisindx];
                if(designer == undefined) designer = d;
                
                if( designer ){
                    designer.find('.group-element').each(function(a,g){
                        var obj = $(g);
                        var id = obj.attr('id');
                        var strHtml = obj.html();
                        if( ! strHtml ) {
                            strHtml = obj[0].innerHTML;
                            if( ! strHtml ){
                                strHtml = g;
                            }
                        }
                        if(strHtml){
                            items[id] = {
                                html : strHtml,
                                properties: {}
                            };
                        }
                    });
                }
            }
        );
        
        setTimeout( function(){
            __generate_properties( items );
        },500, items);

    };
    // </editor-fold>
    
    var __generate_properties = function( items ){
        
        
        
        $.each( self.resolutions, function(i,e) {
                var designer = e.history[e.hisindx-1];
                var d = e.history[e.hisindx];
                if(designer == undefined) designer = d;
                
                if(designer !== undefined){
                    designer.find('.group-element').each( function(a,g){
                        var obj = $(g);
                        var id  = obj.attr('id');
                        if(id){
                            if(items[id] !== undefined){

                                    if(items[id].properties[i] == undefined){
                                        items[id]['properties'][i] = {
                                            width                       : null,
                                            height                      : null,
                                            angle                       : null,
                                            top                         : null,
                                            left                        : null,
                                            img                         : null,
                                            imgrepeat                   : null,
                                            imgposition                 : null,
                                            imgsize                     : null,

                                        };
                                    }

                                    items[id]['properties'][i].width            = obj.width();
                                    items[id]['properties'][i].height           = obj.height();
                                    items[id]['properties'][i].angle            = obj.data('angle');
                                    items[id]['properties'][i].top              = parseInt(obj.css('top'));
                                    items[id]['properties'][i].left             = parseInt(obj.css('left'));
                                    items[id]['properties'][i].img              = parseInt(obj.css('background-img'));
                                    items[id]['properties'][i].imgrepeat        = parseInt(obj.css('background-repeat'));
                                    items[id]['properties'][i].imgsize          = parseInt(obj.css('background-size'));
                            }
                                
                        }
                    });
                }
        });
        
        setTimeout( function(){
            console.log(items);
        }, 1000, items);
        
        
    };
    
    // <editor-fold defaultstate="collapsed" desc="DisableEditor">
    this.DisableEditor = function(){
        self.etc.append('<div class="loader"></div>');
        $('#edd').append('<div class="mask-controls" />');
        $('#eff').append('<div class="mask-controls" />');
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="EnableEditor">
    this.EnableEditor = function(){
        
        self.etc.find('.loader').remove();
        $('#edd').find('.mask-controls').remove();
        $('#eff').find('.mask-controls').remove();
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="CenterObject">
    this.CenterObject = function(){
        
        if( self.ActiveElement == null ) return;
        
        var w = self.etc.width();
        var l = w/2;
        var x = self.ActiveElement.width() / 2;
        var c = l - x;
        self.ActiveElement.css('left',c+'px');
        self.HistoryPushState();
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="RotateLeft">
    this.RotateLeft  = function(){
        
        if( ! self.ActiveElement ) return false;
        
        var a = parseInt($('#angle').val());
        a=a+10;
        $('#angle').val(a).trigger('change');
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="RotateRight">
    this.RotateRight = function(){
        
        if( ! self.ActiveElement ) return false;
        
        var a = parseInt($('#angle').val());
        a=a-10;
        $('#angle').val(a).trigger('change');
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Rotate">
    this.Rotate = function(e){
        
        var a = parseInt( $(e).val() );
        if(a < 0 ) $(e).val(0);
        if(a > 359) $(e).val(0);
        
        a = parseInt( $(e).val() )
        
        if(! self.ActiveElement ) return false;
        
        var t = a+'deg';
        
        self.ActiveElement.css({
            '-o-transform': 'rotate('+t+')',
            '-moz-transform': 'rotate('+t+')',
            '-ms-transform': 'rotate('+t+')',
            '-webkit-transform': 'rotate('+t+')',
            '-khtml-transform': 'rotate('+t+')',
            'transform': 'rotate('+t+')',
        });
        self.ActiveElement.data('angle',a);
        self.HistoryPushState();
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Expand">
    this.Expand = function(){
        
        if(self.ActiveElement){
            
            var w = self.etc.width();
            self.ActiveElement.width(w);
            self.ActiveElement.css('left',0);
            self.HistoryPushState();
            
        }
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Grid">
    this.Grid = function(){
        
        var w = self.etc.width();
        var h = self.etc.height();
        
        if(self.grid == null){
            
            self.etc.find('.grid').remove();
            
            for(var x = 0; x < w; x=x+self.gridStep){
                self.etc.append('<div class="grid" style="left:'+x+'px;"></div>');
            }
            for(var y = 0; y < h; y=y+self.gridStep){
                self.etc.append('<div class="grid vertical" style="top:'+y+'px;"></div>');
            }
            self.grid = true;
            self.controls.grid.addClass('active');
        } else {
            
            self.gridIndx++;
            switch (self.gridIndx){
                
                case 1:
                    self.etc.find('.grid').addClass('white');
                break;
                case 2:
                    self.etc.find('.grid').addClass('yellow').removeClass('white');
                break;
                case 3:
                    self.etc.find('.grid').addClass('green').removeClass('yellow');
                break;
                case 4:
                    self.etc.find('.grid').addClass('red').removeClass('green');
                break;
                case 5:
                    self.etc.find('.grid').addClass('blue').removeClass('red');
                break;
                case 6:
                    self.etc.find('.grid').addClass('gray').removeClass('blue');
                break;
                case 7:
                    self.gridIndx = 0;
                    self.grid = null;
                    self.etc.find('.grid').remove();
                    self.controls.grid.removeClass('active');
                break;
                
            }
            
        }
        
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Resolution">
    this.Resolution = function(){
        
        var select = self.etc.closest('.editor-wrap').find('.resolution select');
        $.each(self.resolutions, function(i,e){
            select.append('<option value="'+i+'">'+i+'</option>');
        });
        select.on('change', function(){
            var t = ($(this).find('option:selected').val());
            var resolution = self.resolutions[t];
            $('#width').val(resolution.width).trigger('change');
            $('.btn-wrap').css('width',resolution.width+'px');
            self.currentResolution = t;
            self.resolutions[self.currentResolution].hisindx--;
            if(self.lastHistoryIndx = self.resolutions[self.currentResolution].hisindx < 0)
                self.lastHistoryIndx = self.resolutions[self.currentResolution].hisindx = 0;
            self.lastHistoryIndx = self.resolutions[self.currentResolution].hisindx;
            
            self.Refresh();
            self.HistoryPushState();
            self.FixVisibility();
            self.Pointer();
        });
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="Magic">
    this.Magic = function(){
        
        //TODO resiti nedostajuce elemente na drugim rezolucijama
        
        var list = {};
        
        $.each(self.resolutions, function(i,e){
            
            var indx = e.hisindx;
            
            var obj = e.history[indx];
            
            if( obj == undefined){
                obj = e.history[indx-1];
            }
            
            if(obj !== undefined){
                if(self.ObjectSize(obj)){
                    console.log(obj);
                    obj.find('.group-element').each(function(a,s){
                        var $s = $(s);
                        var id = $s.attr('id');
                        list[id] = $s;
                    });
                }
            }
            
        });
        
        $.each(list, function(i,e){
            
            var exist = self.etc.find('#'+i);
            if( ! exist.length ){
                var c = e.clone();
                self.AddElement(c);
            }
            
        });
        
        setTimeout(function(){
            self.FixVisibility();
        },150);

    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="FixVisibility">
    this.FixVisibility = function(){
        
        var w = self.etc.width();
        
        self.etc.find('.group-element').each(function(i,e){
            
            var $e = $(e);
            
            var left = parseInt( $e.css('left') );
            
            if(left > w){
                $e.css({
                    left: w/2 - $e.width()/2
                });
            }
            
        });
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Pin">
    this.Pin = function(){
        $('body').find('.pin').on('click', function(){
            var p = $(this);
            var m = p.closest('.pinparent');
            m.toggleClass('pinstate');
            
        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ObjectSize">
    this.ObjectSize = function(obj) {
        var size = 0, key;
        for (key in obj) {
            if (obj.hasOwnProperty(key)) size++;
        }
        return size;
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="SizeHandler">
    this.SizeHandler = function(){
        
        $('#width').val( self.etc.outerWidth() );
        $('#height').val( self.etc.outerHeight() );
        
        var checkHeightTimer = function(){
            if( ! $('#height').is(':focus') ){ $('#height').val( self.etc.outerHeight() ); }
            
            if(self.ActiveElement){
                $('#width,#height').attr('disabled','disabled');
                $('#ewidth,#eheight,#zindex').removeAttr('disabled','disabled');
            } else {
                $('#width,#height').removeAttr('disabled','disabled');
                $('#ewidth,#eheight,#zindex').attr('disabled','disabled');
            }
        };
        
        setInterval(checkHeightTimer, 100);
        
        $('#width').on('change', function(){ 
            var w = $(this).val();
            self.etc.width( w ); self.etc.closest('.editor-wrap').width( w ); 
            setTimeout(function(){
                self.resolutions[self.currentResolution]['width'] = w;
            },120,w);
            
        });
        $('#height').on('change', function(){ 
            var h = $(this).val();
            self.etc.height( h ); 
            self.gridIndx = 0; 
            self.grid = null; if(self.grid){ self.Grid(); } 
            self.resolutions[self.currentResolution]['height'] = h;
            setTimeout(function(){
                self.resolutions[self.currentResolution]['height'] = h;
            },120,h);
        });
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="HistoryPushState">
    this.HistoryPushState = function(){
        var code = self.etc.clone(true,true);
        
        if(code !== self.lastSelection){
            self.lastSelection = code;
            self.resolutions[self.currentResolution].history[self.resolutions[self.currentResolution].hisindx] = code;
            self.resolutions[self.currentResolution].hisindx++;
            self.lastHistoryIndx = self.resolutions[self.currentResolution].hisindx;
            
            
        }
        
        self.ControlUndoRedoButtons();
        
        var size = ( self.ObjectSize(self.resolutions[self.currentResolution].history) );
        
    }; 
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ControlUndoRedoButtons">
    this.ControlUndoRedoButtons = function(){
        
        var currIndx = self.resolutions[self.currentResolution].hisindx;
        
        if(currIndx > 0){
            $('#ec-undo').removeClass('disabled');
        } else {
            $('#ec-undo').addClass('disabled');
        }
        
        if(currIndx < self.lastHistoryIndx-1){
            $('#ec-redo').removeClass('disabled');
        } else {
            $('#ec-redo').addClass('disabled');
        }
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Refresh">
    this.Refresh = function(){
        var code = self.resolutions[self.currentResolution].history[self.resolutions[self.currentResolution].hisindx];
        if(code){
            if( code.length){
                $('body').find('.editor').replaceWith( code );
                self.etc = $('body').find('.editor');
                self.etc.find('.group-element').each(function(i,e){
                    self.ElementBind( $(e) );
                });
                self.etc.width( self.resolutions[self.currentResolution].width );
                self.etc.height( self.resolutions[self.currentResolution].height );
            }
        }
        self.ControlUndoRedoButtons();
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Undo">
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
        self.ControlUndoRedoButtons();
       // self.etc.replaceWith(code);
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Redo">
    this.Redo = function(){
        
        var code = self.resolutions[self.currentResolution].history[self.resolutions[self.currentResolution].hisindx + 1];
        if(code){
            if( code.length){
                self.resolutions[self.currentResolution].hisindx++;
                $('body').find('.editor').replaceWith( code );
                self.etc = $('body').find('.editor');
                self.etc.find('.group-element').each(function(i,e){
                    self.ElementBind( $(e) );
                });
            }
        }
        self.ControlUndoRedoButtons();
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ElementBind">
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
            disabled:false,
             start: function (event, ui) {
                var left = parseInt($(this).css('left'),10);
                left = isNaN(left) ? 0 : left;
                var top = parseInt($(this).css('top'),10);
                top = isNaN(top) ? 0 : top;
                recoupLeft = left - ui.position.left;
                recoupTop = top - ui.position.top;
            },
            drag: function (event, ui) {
                ui.position.left += recoupLeft;
                ui.position.top += recoupTop;
            }
        });

        $e.on('click', function( event){
            
            self.etc.find('.group-element').removeClass('active');
            
            $e.addClass('active');
            self.ActiveElement = $e;
            
            self.ElementListener( this );
            
            $('#zindex').val( $(this).css('z-index') );
            $('#ewidth').val( $(this).width() );
            $('#eheight').val( $(this).height() );
            
        });
        
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Remove">
    this.Remove = function(){ self.etc.find('.group-element.active').remove(); self.HistoryPushState(); self.ActiveElement = null; };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="Pointer">
    this.Pointer = function(){ 
        self.etc.find('.group-element.active').removeClass('active'); self.ActiveElement = null; 
        self.ElementListener(null);
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="EditBackground">
    this.EditBackground = function(){
        
        var a = self.etc.find('.group-element.active');
        
        if(a.length)
        {
            if( a.data('action') == 'Editor.EditImage'){
                var img = a.find('img');
                if(img.length){
                    var src = img.attr('src');
                    
                    a.css({
                        'background-image':     'url('+src+')',
                        'background-repeat':    'no-repeat',
                        'background-size':      'cover',
                        'background-position':  'center center',
                        height:                 img.height(),
                        width:                  img.width()
                    });
                    a.html('');
                    a.data('action','Editor.EditBackground');
                    a.attr('data-action','Editor.EditBackground');
                    self.HistoryPushState();
                } else {
                    a.attr('data-action','Editor.EditBackground');
                    self.HistoryPushState();
                }
            } 
            else if( a.data('action') == 'Editor.EditBackground'){
                
                var src = a.css('background-image');
                src = (src.substring(5, src.length-2));
 
                a.css('background-image','');
                
                var Element = new Image();
                    Element.src = src;
                    Element.className += "editable-img";
                    
                a.append(Element);
                a.data('action','Editor.EditImage');
                a.attr('data-action','Editor.EditImage');
                self.HistoryPushState();
            } 
            
        }
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="BackgroundSize">
    this.BackgroundSize = function(e){
        
        var $e = self.etc.find('.group-element.active');
        
        if( ! $e.length ) return false;
        if( $e.data('action') !== 'Editor.EditBackground') return false; 
        
        var s = $(e);
        var opt = s.find('option:selected');

        switch (opt.text()){
            case 'Cover':
            case 'Contain':
            case 'Initial':
                $e.css('background-size', opt.val());
            break;
            case 'Wide':
                $e.css('background-size', '100%');
            break;
            case 'custom':
            
            break;
        }
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="BackgroundPosition">
    this.BackgroundPosition = function(e){
      
        var $e = self.etc.find('.group-element.active');
        
        if( ! $e.length ) return false;
        
        if( $e.data('action') !== 'Editor.EditBackground') return false; 
        
        var s = $(e);
        
        var opt = s.find('option:selected');
        $e.css('background-position', opt.val());
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="UUID">
    this.UUID = function() {
        var s4 = function() { return Math.floor((1 + Math.random()) * 0x10000).toString(16).substring(1); };
        return 'uuid_' + s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="CancelPopWindow">
    this.CancelPopWindow = function(e){ $(e).closest('.pop-window').remove(); };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="previewImage">
    this.previewImage = function(input){
      
        var reader = new FileReader();

        reader.onload = function (e) {
            var src = e.target.result;
            $(input).next('img').attr('src', src);
        };

        reader.readAsDataURL(input.files[0]);
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ValidateLink">
    this.ValidateLink = function( str ){
        var re = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&:\/~+#-]*[\w@?^=%&\/~+#-])?/;
        return re.test( str );
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ValidateEmpty">
    this.ValidateEmpty = function( str ){ return str.trim() !== '' ? true : false; };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="removeActiveControl">
    this.removeActiveControl = function(){
        $.each(self.controls, function(i,e){
            if( ! $(e).hasClass('ignore') )
                $(e).removeClass('active');
        });
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="ElementListener">
    this.ElementListener = function(e){
      
        var $e = $(e);
        
        if(! $e.length ){
            e = null;
            $('#angle').val(0);
        } else {
            var angle = $e.data('angle');
            if(angle !== undefined){
                $('#angle').val(angle);
            } else {
                $('#angle').val(0);
            }
        }
        
        self.clickedElement = $e;
        self.removeActiveControl();
        
        self.controls.background.addClass('disabled').removeClass('active');
        self.controls.delete.addClass('disabled').removeClass('active');
        self.controls.image.addClass('disabled').removeClass('active');
        self.controls.magic.addClass('disabled').removeClass('active');
        self.controls.link.addClass('disabled').removeClass('active');
        self.controls.video.addClass('disabled').removeClass('active');
        self.controls.text.addClass('disabled').removeClass('active');
        self.controls.expand.addClass('disabled').removeClass('active');
        self.controls.center.addClass('disabled').removeClass('active');
        
        if(self.ActiveElement !== null){
            self.controls.center.removeClass('disabled');
        }
        
        $('#angle').attr('disabled','disabled');
        $('#ef-bg').addClass('hidden');
        
        if( $e.is('a') || $e.has('a').length){
            setTimeout(function(){
                self.controls.link.addClass('active').removeClass('disabled');
                self.controls.text.addClass('active').removeClass('disabled');
                self.controls.expand.removeClass('disabled');
                self.controls.delete.removeClass('disabled');
            },100);
        }
        
        if($e.data('action') === 'Editor.EditBackground'){
            setTimeout(function(){
                self.controls.background.addClass('active').removeClass('disabled');
                self.controls.delete.removeClass('disabled');
                self.controls.link.addClass('disabled').removeClass('active');
                self.controls.text.addClass('disabled').removeClass('active');
                self.controls.expand.removeClass('disabled');
                $('#ef-bg').removeClass('hidden');
                $('#ef-bg-pos').removeClass('hidden');
                
            },100);
        } 
        
        if($e.data('action') == 'Editor.EditImage'){
            setTimeout(function(){
                self.controls.background.addClass('active').removeClass('disabled');
                self.controls.link.removeClass('disabled');
                self.controls.delete.removeClass('disabled');
                self.controls.text.addClass('disabled').removeClass('active');
                self.controls.expand.removeClass('disabled');
                $('#angle').removeAttr('disabled');
            },100);
        } 
        
        if($e.data('action') == 'Editor.EditVideo'){
            setTimeout(function(){
                self.controls.video.addClass('active').removeClass('disabled');
                self.controls.delete.removeClass('disabled');
                self.controls.text.addClass('disabled').removeClass('active');
                self.controls.link.addClass('disabled').removeClass('active');
                self.controls.expand.removeClass('disabled');
                $('#angle').removeAttr('disabled');
            },100);
        } 
        
        if($e.data('action') == 'Editor.EditText'){

            setTimeout(function(){
                self.controls.text.addClass('active').removeClass('disabled');
                self.controls.delete.removeClass('disabled');
                self.controls.link.removeClass('disabled');
                self.controls.link.removeClass('active');
                self.controls.expand.removeClass('disabled');
                $('#angle').removeAttr('disabled');
                if($e.has('a').length){
                    self.controls.link.addClass('active');
                }
                
            },100);
        } 
        
        if( e === null ){
            setTimeout(function(){
                self.controls.background.addClass('disabled').removeClass('active');
                self.controls.delete.addClass('disabled').removeClass('active');
                self.controls.image.removeClass('disabled').removeClass('active');
                self.controls.magic.removeClass('disabled').removeClass('active');
                self.controls.link.removeClass('disabled').removeClass('active');
                self.controls.video.removeClass('disabled').removeClass('active');
                self.controls.text.removeClass('disabled').removeClass('active');
            },100);
            
        } else {
            console.log($e.data('action'));
        }
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="EditText">
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
                force_br_newlines : true,
                force_p_newlines : false,
                forced_root_block : '',
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="EditImage">
    this.EditImage = function(){
        var html = self.CloneTemplate('image');
        var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
    };
    // </editor-fold>   
    
    // <editor-fold defaultstate="collapsed" desc="EditVideo">
    this.EditVideo = function(){
        
        var html = self.CloneTemplate('video');
        var $html = self.PopWindow(html.title, html.content, html.size, html.buttons);
        
    };
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="EditLink">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="EditGroup">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="SubmitText">
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
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="SubmitVideo">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="PreviewImage">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="SubmitImage">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="AddElement">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="CloneTemplate">
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
    // </editor-fold>

    // <editor-fold defaultstate="collapsed" desc="PopWindow">
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
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="SaveProject">
    this.SaveProject = function(){
        
        if( $(this).attr('id') == 'btnsn'){
            self.projectId = null;
        }
      
        var btn = $(this),
            saved = {},
            indx,
            obj,
            jsonString,
            $t,
            _title;
    
        self.etc.find('.grid').remove();
        self.grid = null;
        self.gridIndx = 0;

        $.each(self.resolutions, function(i,e){
            saved[i] = {
                'width' : 0,
                'height': 0,
                'json' : null,
            };
            
            indx = e.hisindx;
            if(indx < 0) indx = 0;
            obj = e.history[indx];
            
            if(obj == undefined){ obj = e.history[indx-1];}
            
            if(obj !== undefined){
                obj.find('.grid').remove();
                obj.find('.active').removeClass('active');
                saved[i].width = e.width;
                saved[i].height = e.height;
                saved[i].json = obj[0].outerHTML;
            }
        });
        
        jsonString = JSON.stringify(saved);
        $t = $('body').find('input[name=title]');
        _title = $t.val();
        
        if(_title.trim() == ""){
                $('#ModalTitle').html('Warning');
                $('#ModalText').html( $t.data('error') );
                $('#Modal').modal('show');
                $t.addClass('error');
                return false;
        } else {
            $t.removeClass('error');
        }
        
        
        
        self.DisableEditor();
        
        btn.addClass('disabled').prop('disabled','disabled');

        $.post(btn.data('url'), { json:jsonString, id:self.projectId, title:_title }, function(response){

               self.EnableEditor();

               if(response.status == true){
                   self.projectId = response.id;
               }

               btn.removeClass('disabled').removeAttr('disabled');

               $('#ModalTitle').html(response.title);
               $('#ModalText').html(response.message);
               $('#Modal').modal('show');

       });
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="LoadProject">
    this.LoadProject = function(){
        
        var id =  self.etc.data('id');
        var url = self.etc.data('url');
        
        self.DisableEditor();
        
        if(! id || ! url ) { self.EnableEditor(); return false; }
        
        $.post( url, {'id':id}, function(response){
            
            
            
            if( response.status == false ){
                
                $('#ModalTitle').html(response.title);
                $('#ModalText').html(response.message);
                $('#Modal').modal('show');
                
                self.EnableEditor();
                
                return false;
            }
            
            $('body').find('input[name=title]').val(response.title);
            
            self.projectId  = response.id;
            
            self.ParseJson( response.json );
            
        });
        
    };
    // </editor-fold>
    
    // <editor-fold defaultstate="collapsed" desc="ParseJson">
    this.ParseJson = function(json){
      
        obj = JSON.parse( json );
        
        $.each(obj, function(i,e){
            if(e.width > 0 && e.height > 0){
                
                self.resolutions[i]['history'][0]   = $(e.json);
                self.resolutions[i].width           = e.width;
                self.resolutions[i].height          = e.height;
                
                if(i == 'desktop'){
                    self.etc.width(e.width);
                    self.etc.height(e.height);
                    $('.editor-wrap').width(e.width);
                }
            }
        });
        
        setTimeout(function(){
            self.Refresh();
            setTimeout(function(){
                self.EnableEditor();
            },2000);
            
        },1000);
        
    };
    // </editor-fold>

$(function(){ __initialize(); });};


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


var Editor = new EditorClass();
var Redirect = new RedirectClass();


