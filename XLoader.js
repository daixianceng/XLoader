(function($) {
	$.fn.extend({
		XLoader : function(config) {
			
			var $this = $(this);
			var $container = $('<div id="XLoaderContainer"></div>');
			var target = location.href;
			var registerStyle = true;
			var tableOptions = {
				// The table id
				id : 'XLoaderTable'
			};
			var columnOptions = {
				// The number column
				number : {
					text : 'No.',
					class : 'table-col-1'
				},
				// The image column
				image : {
					text : 'Picture',
					class : 'table-col-2'
				},
				// The image file name column
				name : {
					text : 'Name',
					class : 'table-col-2'
				},
				// The image file size column
				size : {
					text : 'Size',
					class : 'table-col-1'
				},
				// The textarea column
				textarea: {
					text : 'Description',
					class : 'table-col-2'
				}
			};
			var imageOptions = {};
			var textareaOptions = {
				name : 'descriptions[]'
			};
			var hiddenFieldName = 'imageNames[]';
			
			// Merge two object property, this function will modify the first object.
			var objectMerge = function(obj1, obj2) {
				for (var key in obj2) {
					if ($.isPlainObject(obj1[key]) && $.isPlainObject(obj2[key])) {
						objectMerge(obj1[key], obj2[key]);
					} else {
						obj1[key] = obj2[key];
					}
				}
			}
			
			if (typeof config.container !== 'undefined') {
				$container = $(config.container);
			} else {
				$this.after($container);
			}
			if (typeof config.target !== 'undefined') {
				target = config.target;
			}
			if (config.registerStyle === false) {
				registerStyle = false;
			}
			if ($.isPlainObject(config.tableOptions)) {
				objectMerge(tableOptions, config.tableOptions);
			}
			if ($.isPlainObject(config.columnOptions)) {
				(function() {
					for (var key in columnOptions) {
						if ($.isPlainObject(config.columnOptions[key])) {
							objectMerge(columnOptions[key], config.columnOptions[key]);
						} else {
							// If the key is not in the config.columnOptions, than delete the columnOptions[key].
							delete columnOptions[key];
						}
					}
				})()
			}
			if ($.isPlainObject(config.imageOptions)) {
				objectMerge(imageOptions, config.imageOptions);
			}
			if ($.isPlainObject(config.textareaOptions)) {
				objectMerge(textareaOptions, config.textareaOptions);
			}
			if (typeof config.hiddenFieldName !== 'undefined') {
				hiddenFieldName = config.hiddenFieldName;
			}
			
			if (registerStyle) {
				$('body').append('<style type="text/css">#XLoaderTable{} #XLoaderTable .table-col-1{width:100px;} #XLoaderTable .table-col-2{width:200px;} #XLoaderTable img{max-width:200px;} #XLoaderTable td{text-align:center;} #XLoaderTable textarea{resize:none;width:180px;height:100px;padding:5px;}</style>');
			}
			
			// The hidden iframe and form.
			var $XLoaderBox = $('<div style="display:none;"><iframe name="XLoaderFrame"></iframe><form action="' + target + '" method="post" enctype="multipart/form-data" target="XLoaderFrame"></form></div>').appendTo('body');
			var $table = $('<table><thead><tr></tr></thead><tbody></tbody></table>');
			
			$this.change(function() {
				if (this.files.length === 0) return;
				
				var fileInput = $('<input type="file" name="' + $this.attr('name') + '" multiple>').get(0);
				fileInput.files = this.files;
				$XLoaderBox.find('form').empty().append(fileInput).submit();
			});
			
			$this.parents('form').submit(function() {
				$this.attr('disabled', true);
			});
			
			(function() {
				for (var key in tableOptions) {
					$table.attr(key, tableOptions[key]);
				}
				
				for (var column in columnOptions) {
					var $th = $('<th></th>');
					for (var key in columnOptions[column]) {
						if (key === 'text') {
							$th.text(columnOptions[column][key]);
						} else {
							$th.attr(key, columnOptions[column][key]);
						}
					}
					$table.find('thead tr').append($th);
				}
			})();
			
			(function() {
				var isRender = false;
				var imageCount = 0;
				$.extend({
					XLoaderData : function(json) {
						var list = $.parseJSON(json);
						if (!isRender) {
							$container.append($table);
							isRender = true;
						}
						
						for (var i = 0; i < list.length; i ++) {
							imageCount ++;
							var $tr = $('<tr></tr>');
							
							for (var column in columnOptions) {
								
								switch (column) {
									case 'number' :
										$tr.append('<td>' + imageCount + '</td>');
										break;
									case 'image' :
										var $td = $('<td><img src="' + list[i].uri + '"></td>');
										for (var key in imageOptions) {
											if (key === 'src') continue;
											$td.find('img').attr(key, imageOptions[key]);
										}
										$tr.append($td);
										break;
									case 'name' :
										$tr.append('<td>' + list[i].name + '<input type="hidden" name="' + hiddenFieldName + '" value="' + list[i].name + '"></td>');
										break;
									case 'size' :
										$tr.append('<td>' + list[i].size + '</td>');
										break;
									case 'textarea' :
										var $td = $('<td><textarea></textarea></td>');
										for (var key in textareaOptions) {
											$td.find('textarea').attr(key, textareaOptions[key]);
										}
										$tr.append($td);
										break;
								}
								$table.find('tbody').append($tr);
							}
						}
					}
				});
			})()
		}
	})
})(jQuery)