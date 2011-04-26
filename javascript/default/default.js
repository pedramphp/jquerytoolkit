$(document).ready(function(){
	
	core.activateLinks()
		.tweets();

});

var core = {
		activateLinks: function(){
			
			$('.js-link').click(function(){
				window.location.href = $(this).data('url');
			}).css('cursor','pointer');
			return this;
		},		
		tweets: function(){
			
			var articles = $('#twitter article'),
				articleLength = articles.length,
				
				generateLinks = function(){
					var replace,
						match,
						twitterSearch = "http://twitter.com/#!/search?q=";
					
					articles.each(function(){
						$(this).html(function(index, text){
	
							var matches = text.match(/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/gi);
							
							if(matches !== null){
								for( var i=0; i < matches.length; i++ ){
									
									match = matches[i];
									replace =  "<a href='"+ match  + "'>" + match + "</a>";
									text = text.replace(match,replace );							
								}
							}
							
							matches = text.match(/#[\w]+/gi);
							if(matches !== null){
								for( var i=0; i < matches.length; i++ ){
									match = matches[i];
									replace =  "<a href='"+ twitterSearch+ encodeURIComponent("#") + match.split("#")[1]  + "'>" + match + "</a>";
									text = text.replace(match,replace );							
								}
							}
							return text;
						});
					});
				}();
			
			function next( e ){
				e.preventDefault();
				$('#twitter').find('article:visible').fadeOut(500,function(){
					var that = $(this);
					var $elem = ( articleLength - 1 == articles.index(that) ) ? articles.first() : that.next();
					$elem.fadeIn( 500 );
				});
			}
			
			function prev( e ){
				e.preventDefault();
				$('#twitter').find('article:visible').fadeOut(500,function(){
					var that = $(this);
					var $elem = ( 0 == articles.index(that) ) ? articles.last() : that.prev();
					$elem.fadeIn( 500 );
				});				
			}
			
			$('#twitter .arrow-right').click( next );
			$('#twitter .arrow-left').click( prev );
			
		}
};


