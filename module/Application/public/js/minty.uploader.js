var UploaderClass = function(){
    
    
    /**
     * 
     * @type UploaderClass
     */
    var self = this;
    
    /**
     * image-helper
     */
    this.e;
    
    /**
     * Upload button
     */
    this.b;
    
    
    /**
     * image-helper element ID
     */
    this.id;
    
    
    /**
     * Input file element
     */
    this.f;
    
    
    /**
     * Error message
     */
    this.error = null;
    
    
    /**
     * Maximum sizes in bytes
     */
    this.maxsize = 0;
    
    
    /**
     * Upload url;
     */
    this.url;
    
    
    /**
     * 
     */
    this.progress_bar;
    
    
    /**
     * 
     */
    this.bar;
    
    
    /**
     * 
     */
    this.progress_cnt;
    
    
    /**
     * 
     */
    this.progress_info;
    
    
    /**
     * 
     */
    this.used = 0;
    
    /**
     * 
     */
    this.limit = 0;
    
    /**
     * List of all uploder class initialized
     */
    this.collections = {};
    
    
    /**
     * 
     * @returns {Boolean}
     */
    this.Init = function(){
        
        if( ! $('.image-helper').length ) return false;
        
        $('.image-helper').each(function(){
            if( !$(this).hasClass('inited') )
            {
                $(this).addClass('inited');
                var Uc = new UploaderClass();
                    Uc.e = $(this);
                    Uc.__initialize();
                    
                self.collections[ $(this).attr('id') ] = Uc;
            }
        });
    };
    
    
    /**
     * 
     * @returns {Boolean}
     */
    this.__initialize = function(){
        
        if( ! this.__set_button_click()) return false;
        
        this.__move_action();
        
    };
    
    /**
     * 
     * @returns {Boolean}
     */
    this.__set_button_click = function(){
        
        this.id         = this.e.attr('id');
        this.b          = $('body').find('[for='+this.id+'] button');
        this.maxsize    = this.b.data('maxsize') * 1024 * 1000;
        this.url        = this.b.data('url');
        this.used       = this.e.data('used');
        this.limit      = this.e.data('limit');
        
        if( ! this.b.length ) { alert('Image uploader does not have a button defined.!'); return false; }
        
        var wrap = this.b.wrap('<div class="img-u-wraper" />').parent().append('<input type="file" name="'+this.id+'" id="file-'+this.id+'" multiple />');
        
        wrap.append('<div class="img-progress-bar animate"><div class="cssload-container"><div class="cssload-loading"><i></i><i></i><i></i><i></i></div></div><div class="progress-container"><div class="progress-bar"></div></div><div class="progress-info">0%</div></div>');
        
        this.progress_bar   = wrap.find('.img-progress-bar');
        this.progress_cnt   = wrap.find('.progress-container');
        this.bar            = this.progress_bar.find('.progress-bar');
        this.progress_info  = wrap.find('.progress-info');
        
        this.f = wrap.find('input');
        this.f.on('change', this.__upload_action );
        
        return true;
    };
    
    
    this.__move_action = function(){
        
        var container = $( '#'+self.id );
        
        container.sortable({
            items: '.img-thumbnail.active'
        });
        
    };
    
    
    /**
     * 
     * @returns {Boolean}
     */
    this.__upload_action = function(){
        
        var fileInputElement = document.getElementById('file-'+self.id);
        var files    = fileInputElement.files;
            
        if( ! self.__validate_files( files )){ Display.Message( {status: false, message: self.error } ); return false; }
        
        self.progress_bar.addClass('active');
        self.progress_upload(0);
        
        self.__upload( files );
    };
    
    
    /**
     * 
     * @param {type} percent
     * @returns {undefined}
     */
    this.progress_upload = function(percent){

        self.bar.css('width',percent+'%');
        self.progress_info.text(percent+'%');
        
        if(percent >= 100 && self.progress_bar.hasClass('active')){
            self.progress_bar.addClass('busy');
        } else {
            self.progress_bar.removeClass('busy');
        }
        
    };
    
    
    this.__upload = function( files ){
        
        var container = $( '#'+self.id );
        
        var check = files.length + self.used;
        
        if( check > self.limit ){
            
            var opt = {
                'status' : false,
                'message': container.data('limiterror').replace('%s', self.limit)
            };
            
            self.progress_bar.removeClass('active').removeClass('busy');
            
            Display.Message( opt );
            
            return false;
            
        } else {
        
                var formData = new FormData();

                $.each(files, function(i,f){formData.append( 'file-'+i, f ); });

                $.ajax({
                    xhr: function()
                    {
                        var xhr = new window.XMLHttpRequest();
                        //Upload progress
                        xhr.upload.addEventListener("progress", function(evt){
                          if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            self.progress_upload( percentComplete );
                          }
                        }, false);
                        //Download progress
                        xhr.addEventListener("progress", function(evt){
                          if (evt.lengthComputable) {
                            var percentComplete = (evt.loaded / evt.total) * 100;
                            //Do something with download progress
                            console.log(percentComplete, 'download');
                          }
                        }, false);
                        return xhr;
                    },
                    processData: false,
                    contentType: false,
                    type: 'POST',
                    url:  self.url,
                    data: formData,
                    success: function(data){
                        self.progress_bar.removeClass('active');
                        if( data.status === true){
                            self.DisplayItems( data.items );
                        } else {
                            Display.Message( data );
                        }

                    }
                });
        
        }
        
    };
    
    
    
    /**
     * 
     * @param {type} items
     * @returns {undefined}
     */
    this.DisplayItems = function( items ){
        
        var container = $( '#'+self.id );
        
        var filds = container.find('.img-thumbnail:not(.active)');
        
        var iter = 0;
        
        self.used = self.used + items.length;
        
        $.each(items, function(i,e){
            
            var c = $(filds[iter]);
            
            if(c.length){
                c.addClass('active');
                c.data('id',i);
                c.append('<div class="edit-thumb" ><img src="'+e+'" /></div>');
                iter++;
            }
        });

        self.__move_action();
        
        
    };
    
    

    /**
     * 
     */
    this.__validate_files = function( files ){
        
        for(var i = 0; i < files.length; i++ ){
            
            var file = files[i]; 
            
            if( ! file.type.match(/image.*/) ) { self.error = self.b.data('typeerror'); return false; };
            if(file.size > self.maxsize){ self.error = self.b.data('sizeerror'); return false; }
        };
        return true;
    };
    
    
$(function(){  Uploader.Init();  });};




var Uploader = new UploaderClass();
