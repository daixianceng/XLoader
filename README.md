XLoader
=======

简单的jQuery多图片上传插件

该插件仅适用于HTML5

使用方式
=======
1.引入jQuery
2.创建表单：
*****************************************************
<form action="upload.php" method="post" enctype="multipart/form-data">
  <input id="file" type="file" name="images[]" accept="image/*" multiple>
  <button type="submit">Upload</button>
</form>
*****************************************************
3.激活多图片上传功能：
*****************************************************
<script type="text/javascript">
$('#file').XLoader({
	target : 'target.php' // 多图片上传目标脚本（必须）
});
</script>
*****************************************************

参数说明
=======
当激活XLoader时，需要传递一个数组对象，以配置参数，参数列表如下：
1.target : 多图片上传目标脚本（必须），图片上传通过此脚本进行
2.container : 图片列表容器，默认在图片选择按钮下面展示
3.registerStyle : 是否注册自带样式，默认注册
4.tableOptions : 表格属性设置，默认：{id : 'XLoaderTable'}
5.columnOptions : 表格列设置
6.imageOptions : 图片属性设置，默认：{}
7.textareaOptions : 输入框设置，默认：{name : 'descriptions[]'}
8.hiddenFieldName : 图片文件名隐藏域的名称，默认：'imageNames[]'
