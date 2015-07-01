// Include scripts with Codekit <https://incident57.com/codekit/help.html#javascript>
// 
// @codekit-prepend "../bower_components/jquery.repeater/jquery.repeater.js"


// Include scripts with gulp-inlcude <https://www.npmjs.com/package/gulp-include>
// 
//= include ../bower_components/jquery.repeater/jquery.repeater.js


(function($){
	
	$(document).ready(function(){
		
		$('.repeater').repeater();

	});
	
})(jQuery);
