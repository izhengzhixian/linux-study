function chk_gbook(theForm)
{
  if (theForm.nickname.value == "")
  {
    alert("请填写昵称！");
    theForm.nickname.focus();
    return (false);
  }
  if (theForm.nickname.value.length<3)
  {
    alert("昵称至少应为3个字符！");
    theForm.nickname.focus();
    return (false);
  }
  if(theForm.email.value!=""){
              var email1 = theForm.email.value;
              var pattern = /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+(\.[a-zA-Z0-9_-])+/; 
              flag = pattern.test(email1); 
              if(!flag){
              alert("邮件地址格式不对！");
     theForm.email.focus();
           return false;}
  }

  if (theForm.content.value == "")
  {
    alert("留言内容不能空！");
    theForm.content.focus();
    return (false);
  }
  if (theForm.content.value.length<5)
  {
    alert("留言内容最少5个字符！");
    theForm.content.focus();
    return (false);
  }
   return (true);
}

function chk_blog(theForm)
{
  if (theForm.title.value == "")
  {
    alert("请填写文章标题");
    theForm.title.focus();
    return (false);
  }
  if (theForm.title.value.length<3)
  {
    alert("文章标题至少应为3个字符！");
    theForm.title.focus();
    return (false);
  }

  if (theForm.content.value == "")
  {
    alert("内容不能空！");
    theForm.content.focus();
    return (false);
  }
  if (theForm.content.value.length<5)
  {
    alert("内容最少5个字符！");
    theForm.content.focus();
    return (false);
  }
   return (true);
}