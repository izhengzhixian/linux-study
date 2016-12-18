//textarea插入函数
function insertTag(topen,tclose,id){
var themess = document.getElementById(id);//编辑对象:修改textarea:ID
themess.focus();
if (document.selection) {//如果是否ie浏览器
   var theSelection = document.selection.createRange().text;//获取选区文字
   //alert(theSelection);
   if(theSelection){
		document.selection.createRange().text = theSelection = topen+theSelection+tclose;//替换
   }else{
		document.selection.createRange().text = topen+tclose;
   }
   theSelection='';

}else{//其他浏览器

   var scrollPos = themess.scrollTop;
   var selLength = themess.textLength;
   var selStart = themess.selectionStart;//选区起始点索引，未选择为0
   var selEnd = themess.selectionEnd;//选区终点点索引
   if (selEnd <= 2)
   selEnd = selLength;

   var s1 = (themess.value).substring(0,selStart);//截取起始点前部分字符
   var s2 = (themess.value).substring(selStart, selEnd)//截取选择部分字符
   var s3 = (themess.value).substring(selEnd, selLength);//截取终点后部分字符

   themess.value = s1 + topen + s2 + tclose + s3;//替换

   themess.focus();
   themess.selectionStart = newStart;
   themess.selectionEnd = newStart;
   themess.scrollTop = scrollPos;
   return;
}
}

//链接form提交
function formSubmit(id) {
    document.getElementById(id).submit();
}