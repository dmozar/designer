var MintyTableClass = function(){
    
    
    var self = this;
    
    /**
     * 
     */
    this.tables = {};
    
    /**
     * 
     */
    this.url;
    
    /**
     * 
     */
    this.offset;
    
    /**
     * 
     */
    this.limit;
    
    /**
     * 
     */
    this.t;
    
    /**
     * 
     */
    this.body;

    /**
     * 
     */
    this.scroll;
    
    /**
     * 
     */
    this.input;
    
    
    /**
     * 
     */
    this.pagine_container;
    
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.Init = function(){
        $('body').find('.minty-table').each(function(){
            
            var t = $(this);
            
            if(! t.hasClass('inited')){
                t.addClass('inited');
                var id = t.attr('id');
                t.MintyTable = new MintyTableClass();
                self.tables[t] = t;
                t.MintyTable.__first_initialize( t );
            } 
        });
    };
    
    
    
    /**
     * 
     * @param {type} id
     * @returns {Window.tables}
     */
    this.Instance = function( id ){
        if( self.tables[id] )
            return self.tables[id];
    };
    
    
    
    /**
     * 
     * @param {type} t
     * @returns {undefined}
     */
    this.__first_initialize = function( t ){
        
        self.t = t;
        
        self.offset = t.data('offset');
        self.limit  = t.data('limit');
        self.url    = t.data('source');
        
        self.__load( self.__after_first );  
        
    };
    
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.__after_first = function(e){
        
        if(e.names){
            self.__header( e.names );
            self.__body();
        }
        
        self.__add_items(e.items);
        self.__equalizer();

        $(window).on('resize', self.__equalizer);
        
        self.__footer( e );
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.__after_load = function(e){
      
        self.__add_items(e.items);
        self.__equalizer();
        self.__paginator(e);
        
        self.t.data('limit', e.limit);
        
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.__footer = function( e ){
        
        var f = $('<div class="minty-table-footer" />');
        
        self.t.parent().append(f);
        
        self.pagine_container = $('<div class="minty-pagine" />');
        
        f.append(self.pagine_container);
        
        self.__paginator(e);
        
        var s = $('<div class="minty-search" />');
        f.append(s);
        
        self.input = $('<input type="text" name="keywords" value="" placeholder="Enter text for search..." maxlength="100" /> ');
        s.append( self.input );
        
        self.input.on('keyup', function(event){
           if(event.keyCode === 13){
               self.__load( self.__after_load );
           } 
        });
        
    };
    
    
    /**
     * 
     * @param {type} e
     * @returns {undefined}
     */
    this.__paginator = function( e ){
        
        self.pagine_container.html('');
        
        $.each(e.pages, function(i,s){

                var _class = (s === e.page) ? 'minty-pag current':'minty-pag';
                var a = $('<span class="'+_class+'" data-value="'+s+'">'+s+'</span>');
                self.pagine_container.append(a);
                a.on('click', self.__pagevent);
            
        });
    };


    /**
     * 
     * @returns {undefined}
     */
    this.__pagevent = function(){
        
        var page = __(this).data('value');
        
        self.__refresh( page );
    };
    
    
    /**
     * 
     * @param {type} page
     * @returns {undefined}
     */
    this.__refresh = function( page ){
        
        self.offset = (page * self.limit) - self.limit;
        
        self.__load( self.__after_load );
        
    };
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.__equalizer = function(){
        
        var names = self.t.find('.minty-header tr td');
        var row = self.t.find('.minty-body tr:nth-child(1) td');
        
        $.each(names, function(i,e){
            $(row.get(i)).width( $(e).width() );
        });
        
        self.t.jScrollPane();
        
    };
    
    
    
    /**
     * 
     * @param {type} items
     * @returns {undefined}
     */
    this.__add_items = function ( items ){
        
        self.body.html('');
        
        $.each(items, function(i,e){
            if(e.length){
                var s = '<tr>';
                $.each(e,function(a,b){
                    s+= '<td>' + b + '</td>';
                });
                s+= '</tr>';
            }
            self.body.append(s);
        });
        
        self.__ajax_request( self.body.find('*[data-ajax]') );
    };
    
    
    this.__ajax_request = function( elements ){
        
        if( ! elements.length ) return false;
        
        
        elements.unbind().on('click', function(){
            
            var a = __(this);
            
            $.post(a.data('ajax'),[],function(response){
                
                a.replaceWith(response);
                self.__ajax_request( self.body.find('*[data-ajax]') );
                
            });
            
        });
        
    };
    
    
    
    /**
     * 
     * @returns {undefined}
     */
    this.__body = function(){
        
        var table = $('<table class="minty-body" />');

        self.t.append(table);
        
        table.wrap('<div class="wrap-body" />');
        
        self.body = table;
    };
    
    
    /**
     * 
     * @param {type} names
     * @returns {undefined}
     */
    this.__header = function( names ){
        
        var table = $('<table class="minty-header" />');
        var tr = $('<tr />');
            $.each(names, function(i,e){
                var td = $('<td></td>');
                tr.append(td);
                td.html(e);
            });
        table.html(tr);
            
        self.t.append(table);
        
        table.wrap('<div class="wrap-header" />');
        
    };
    
    
    
    /**
     * 
     * @param {type} callback
     * @returns {undefined}
     */
    this.__load = function( callback ){
        
        var words = self.input ? self.input.val() : '';
        
        var _data = { offset: self.offset, limit: self.limit, keywords: words };
        
        $.ajax({
            method:"POST", url:self.url, data:_data
        }).done(function( e ) { eval(callback)(e);});
    };
    
    
    
$(function(){ MintyTable.Init(); })};



var MintyTable = new MintyTableClass();