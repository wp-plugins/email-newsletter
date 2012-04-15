// JScript File
//################################################################################
//###### Project   : Email newsletter										######
//###### Author    : Gopi                        							######
//################################################################################

var xmlHttp
function GetXmlHttpObject(handler)
{ 
	var objXmlHttp=null
	if (navigator.userAgent.indexOf("Opera")>=0)
	{
		alert("This page doesn't work in Opera") 
		return 
	}
	if (navigator.userAgent.indexOf("MSIE")>=0)
	{ 
		var strName="Msxml2.XMLHTTP"
		if (navigator.appVersion.indexOf("MSIE 5.5")>=0)
		{
			strName="Microsoft.XMLHTTP"
		} 
		try
		{ 
			objXmlHttp=new ActiveXObject(strName)
			objXmlHttp.onreadystatechange=handler 
			return objXmlHttp
		} 
		catch(e)
		{ 
			alert("Error. Scripting for ActiveX might be disabled") 
			return 
		} 
	} 
	if (navigator.userAgent.indexOf("Mozilla")>=0)
	{
		objXmlHttp=new XMLHttpRequest()
		objXmlHttp.onload=handler
		objXmlHttp.onerror=handler 
		return objXmlHttp
	}
} 
function eemail_submit_ajax(siteurl)
{
	txt_email_newsletter=document.getElementById("eemail_txt_email");
	
    if(txt_email_newsletter.value=="")
    {
        alert("Please enter the email address");
        txt_email_newsletter.focus();
        return false;    
    }
	if(txt_email_newsletter.value!="" && (txt_email_newsletter.value.indexOf("@",0)==-1 || txt_email_newsletter.value.indexOf(".",0)==-1))
    {
        alert("Please enter valid email")
        txt_email_newsletter.focus();
        txt_email_newsletter.select();
        return false;
    }
	document.getElementById("eemail_msg").innerHTML="loading...";
	var date_now=new Date()
    var mynumber=Math.random()
	var url=siteurl+"/eemail_subscribe.php?txt_email_newsletter="+ txt_email_newsletter.value + "&timestamp=" + date_now + "&action=" + mynumber;
    xmlHttp=GetXmlHttpObject(newchanged_ncc)
    xmlHttp.open("GET", url , true)
    xmlHttp.send(null)
	
}

function newchanged_ncc() 
{ 
	//alert(xmlHttp.readyState);
	//alert(xmlHttp.responseText);
	if (xmlHttp.readyState==4 || xmlHttp.readyState=="complete")
	{ 
		if((xmlHttp.responseText).trim()=="succ")
		{
			document.getElementById("eemail_msg").innerHTML="Subscribed successfully.";
			document.getElementById("eemail_txt_email").value="";
		}
		else if((xmlHttp.responseText).trim()=="exs")
		{
		    document.getElementById("eemail_msg").innerHTML="Email already exist.";
		}
		else
		{
			document.getElementById("eemail_msg").innerHTML="Please try after some time.";
			document.getElementById("eemail_txt_email").value="";
		}
	} 
} 

String.prototype.trim = function() {
	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
