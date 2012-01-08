/* Author: Jonathan Stanton

*/

var gallery = function() {
	
	//private functions

	//public functions	
	return {

		init : function() {

			this.size_viewport();

		},

		size_viewport : function(){
			var width = 0;

			if($("#viewport > div").length > 0){
				var converter = new Showdown.converter();

				$("#viewport > div").each(function(){
					
					var text = $(this).html();
					var html = converter.makeHtml(text);

					$(this).html(html);

				});
			}

			$("#viewport div,#viewport img").each(function(){
				width += $(this).outerWidth( true );
			});

			$("#viewport").width(width);
		}
	}
}();

$(document).ready(function(){
	gallery.init();
});