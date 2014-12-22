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
				},
				modify: {
					text : 'Modify',
					class : 'table-col-1'
				}
			};
			var imageOptions = {};
			var textareaOptions = {
				name : 'descriptions[]'
			};
			var deleteLinkOptions = {
				text : 'Delete',
				class : 'XLoaderDeleteLink'
			};
			var hiddenFieldName = 'imageNames[]';
			var resourcesUrl = '';
			
			// Merge two object property, this function will modify the first object.
			var objectMerge = function(obj1, obj2) {
				for (var key in obj2) {
					if ($.isPlainObject(obj1[key]) && $.isPlainObject(obj2[key])) {
						objectMerge(obj1[key], obj2[key]);
					} else {
						obj1[key] = obj2[key];
					}
				}
			};
			
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
			if ($.isPlainObject(config.deleteLinkOptions)) {
				objectMerge(deleteLinkOptions, config.deleteLinkOptions);
			}
			if (typeof config.hiddenFieldName !== 'undefined') {
				hiddenFieldName = config.hiddenFieldName;
			}
			if (typeof config.resourcesUrl !== 'undefined') {
				resourcesUrl = config.resourcesUrl;
			}
			
			if (registerStyle) {
				$('body').append('<style type="text/css">#XLoaderTable{font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#333;background:#eee;border-spacing:1px}#XLoaderTable .table-col-1{width:100px}#XLoaderTable .table-col-2{width:200px}#XLoaderTable th{font-size:14px;font-weight:200;color:#ddd;background:#333;height:40px}#XLoaderTable img{max-width:180px}#XLoaderTable tr{background:#fff}#XLoaderTable td{text-align:center;padding:10px}#XLoaderTable textarea{resize:none;width:178px;height:100%;padding:5px;font-family:Arial,Helvetica,sans-serif;font-size:13px;color:#333;border:1px dashed #ccc;outline:none}#XLoaderTable textarea:hover,#XLoaderTable textarea:focus{border-color:#333}.XLoaderDeleteLink{color:#fff;background-color:#d9534f;border-color:#d43f3a;padding:5px 12px;font-weight:bold;line-height:18px;cursor:default;-webkit-background-clip:border-box;background-clip:border-box;border-radius:2px;-webkit-box-shadow:none;box-shadow:none;text-shadow:0 1px rgba(0, 0, 0, .1);display:inline-block;text-decoration:none;}.XLoaderDeleteLink:hover{text-shadow:0 1px rgba(0, 0, 0, .3);-webkit-box-shadow:0 1px 1px rgba(0, 0, 0, .2);box-shadow:0 1px 1px rgba(0, 0, 0, .2);border-color:#ac2925;background-color:#c13e2c;}.XLoaderDeletedRow{color:#a94442;background-color:#f2dede;text-shadow:0 1px 0 rgba(255, 255, 255, .5);}</style>');
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
					XLoaderData : function(json, source) {
						var list = $.parseJSON(json);
						
						if (!list || list.length === 0) return;
						
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
										
										if (list[i].description) {
											$td.find('textarea').val(list[i].description);
										}
										
										$tr.append($td);
										break;
									case 'modify' :
										var $td = $('<td><a href="#"></a></td>');
										for (var key in deleteLinkOptions) {
											if (key === 'text') {
												$td.find('a').text(deleteLinkOptions[key]);
											} else {
												$td.find('a').attr(key, deleteLinkOptions[key]);
											}
										}
										
										(function() {
											var $row = $tr;
											var filename = list[i].name;
											var url = '';
											var loading = false;
											
											if (source === 'target') {
												url = target;
											} else if (source === 'resources') {
												url = resourcesUrl;
											}
											
											$td.find('a').click(function() {
												if (loading) return false;
												loading = true;
												$.ajax({
													url : url,
													type : 'post',
													data : {filename : filename, action : 'delete'},
													dataType : 'json',
													success : function(json) {
														if (json.error === 'no') {
															$row.empty().html('<td class="XLoaderDeletedRow" colspan="' + Object.keys(columnOptions).length + '">The "' + filename + '" has been deleted.</td>');
														}
													},
													error : function(XMLHttpRequest, textStatus, errorThrown) {
														alert(textStatus);
													},
													complete : function(XMLHttpRequest, textStatus) {
														loading = false;
													}
												});
												return false;
											});
										})();
										
										$tr.append($td);
										break;
								}
							}
							
							$table.find('tbody').append($tr);
						}
					}
				});
			})();
			
			(function() {
				if (resourcesUrl !== '') {
					$.ajax({
						url : resourcesUrl,
						type : 'post',
						timeout : 30000,
						data : {action : 'load'},
						dataType : 'text',
						success : function(data) {
							$.XLoaderData(data, 'resources');
						},
						error : function(XMLHttpRequest, textStatus, errorThrown) {
							alert(textStatus);
						}
					});
				}
			})();
		}
	})
})(jQuery)