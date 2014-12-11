(function($) {
	$.fn.extend({
		XLoader : function(config) {
			
			var target = location.href;
			var container = '<div id="XLoaderContainer"></div>';
			var types = ['jpg', 'png', 'gif', 'bmp'];
			
			if (config.target && $.type(config.target) === 'string') {
				target = config.target;
			}
			if (config.container && $.type(config.container === 'string')) {
				container = config.container;
			} else {
				$(this).after(container);
			}
			/*
			if (config.types && $.type(config.types) === 'array' && config.types.length > 0) {
				for (var i = 0; i < config.types.length; i ++) {
					if ($.inArray(config.types[i], types) === -1) {
						
					}
				}
			}
			*/
			
			var $XLoaderBox = $('<div id="XLoaderBox" style="display:none;"><iframe name="XLoaderFrame"></iframe><form action="' + target + '" method="post" enctype="multipart/form-data" target="XLoaderFrame"></form></div>').appendTo('body');
			
			$(this).change(function() {
				var input = $('<input type="file" name="' + $(this).attr('name') + '" multiple>').get(0);
				input.files = this.files;
				$XLoaderBox.find('form').empty().append(input).submit();
			})
			
			$.extend({
				XLoaderData : function(json) {
					var list = $.parseJSON(json);
					for (var i = 0; i < list.length; i ++) {
						$(container).append('<div><img src="' + list[i].uri + '" width="100"></div>');
					}
				}
			});
		}
	})
})(jQuery)