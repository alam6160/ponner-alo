function change_password()
{
	var data = {
		'cpass': document.getElementById("write_cpass").value,
		'npass': document.getElementById("write_npass").value,
		'rpass': document.getElementById("write_rpass").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/change-password';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_name()
{
	var data = {
		'title': document.getElementById("write_title").value,
		'fname': document.getElementById("write_fname").value,
		'lname': document.getElementById("write_lname").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-name';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_email()
{
	var data = {
		'email': document.getElementById("write_email").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-email';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_phone()
{
	var data = {
		'contact': document.getElementById("write_contact").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-phone';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_raddress()
{
	var data = {
		'address': document.getElementById("write_raddress").value,
		'pin_code': document.getElementById("write_rpincode").value,
		'state_id': document.getElementById("write_rstate").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-raddress';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_baddress()
{
	var data = {
		'title': document.getElementById("write_btitle").value,
		'fname': document.getElementById("write_bfname").value,
		'lname': document.getElementById("write_blname").value,
		'email': document.getElementById("write_bemail").value,
		'contact': document.getElementById("write_bcontact").value,
		'address': document.getElementById("write_baddress").value,
		'pin_code': document.getElementById("write_bpincode").value,
		'state_id': document.getElementById("write_bstate").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-baddress';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}

function update_saddress()
{
	var data = {
		'title': document.getElementById("write_stitle").value,
		'fname': document.getElementById("write_sfname").value,
		'lname': document.getElementById("write_slname").value,
		'email': document.getElementById("write_semail").value,
		'contact': document.getElementById("write_scontact").value,
		'address': document.getElementById("write_saddress").value,
		'pin_code': document.getElementById("write_spincode").value,
		'state_id': document.getElementById("write_sstate").value
	};

	var xhr = new XMLHttpRequest();
	var ajax_url = base_url + 'user/account/update-saddress';

	xhr.open("POST", ajax_url);
	xhr.setRequestHeader("Content-Type", "application/json");
	xhr.send(JSON.stringify(data));

	xhr.onreadystatechange = function()
	{
		if(xhr.readyState == 4 && xhr.status == 200)
   		{
   			var response = JSON.parse(xhr.responseText);

   			if(response['status'] == 1)
   			{
   				swal("Done!", response['message'], "success").then(()=>
				{
					window.location.reload();
				});
   			}else{
   				swal("Oops!", response['message'], "warning");
   			}
   		}
	};
}
