var LoginClass = function(){
    
    
    this.Success = function( e ){
        
        window.location.href = site_url;
        
    };
    
    
    this.Error = function( e ){
        
        console.log(e.message);
        
    };
    
    
};


var Login = new LoginClass();