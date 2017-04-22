$(document).ready(function ()
{
	$('#collapse_userinfo').click(function ()
	{
		if ($('#header_userinfo').is(":hidden"))
		{
			$('#header_userinfo').show();
			$('#collapse_userinfo').attr('src', m3_images_url + '/up.png');
		}
		else
		{
			$('#header_userinfo').hide();
			$('#collapse_userinfo').attr('src', m3_images_url + '/down.png');
		}
	});
});