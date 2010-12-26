
function eemail_submit()
{
	if(document.form_eemail.eemail_subject.value=="")
	{
		alert("Please enter the email subject.")
		document.form_eemail.eemail_subject.focus();
		return false;
	}
	else if(document.form_eemail.eemail_content.value=="")
	{
		alert("Please enter the email body.")
		return false;
	}
	else if(document.form_eemail.eemail_status.value=="")
	{
		alert("Please select the display status.")
		document.form_eemail.eemail_status.focus();
		return false;
	}
}

function _eemail_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_eemail_display.action="options-general.php?page=email-newsletter/email-compose.php&AC=DEL&DID="+id;
		document.frm_eemail_display.submit();
	}
}	

function send_email_submit()
{
	if(document.form_eemail.eemail_subject_drop.value=="")
	{
		alert("Please select the email subject.")
		return false;
	}
}

function _eemail_redirect()
{
	window.location = "options-general.php?page=email-newsletter/email-compose.php";
}