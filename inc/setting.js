
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
		document.frm_eemail_display.action="admin.php?page=add_admin_menu_email_compose&AC=DEL&DID="+id;
		document.frm_eemail_display.submit();
	}
}	


function _subscriberdealdelete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.form_subscriber.action="admin.php?page=add_admin_menu_view_subscriber&AC=DEL&DID="+id;
		document.form_subscriber.submit();
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
	window.location = "admin.php?page=add_admin_menu_email_compose";
}


function SetAllCheckBoxes(FormName, FieldName, CheckValue)
{
	
	if(!document.forms[FormName])
		return;
	var objCheckBoxes = document.forms[FormName].elements[FieldName];
	if(!objCheckBoxes)
		return;
	var countCheckBoxes = objCheckBoxes.length;
	if(!countCheckBoxes)
		objCheckBoxes.checked = CheckValue;
	else
		// set the check value for all check boxes
		for(var i = 0; i < countCheckBoxes; i++)
			objCheckBoxes[i].checked = CheckValue;
}
