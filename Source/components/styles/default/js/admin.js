$(document).ready(function()
{
	$("#settings_form").submit(function()
	{
		console.log($(this).serialize());
		$.ajax(
		{
			method: "post",
			data: $(this).serialize(),
			url: $("#settings_form").attr("action")
		})
		.done(function(data)
		{
			var obj = $.parseJSON(data);
		
			if (obj.success == true)
			{
				$("#profile_success").show("slow");
				$("#profile_success").delay(2000).fadeOut("slow");
			}
			
			if (obj.errors != null)
			{
				$("#errors_occured").html("")
				for (var i in obj.errors)
					$("#errors_occured").append("<li>" + obj.errors[i] + "</li>");
					
				$("#errors_occured_box").show("slow");
			}
			else
				$("#errors_occured_box").hide("slow");
		});
		return false;
	});
});
