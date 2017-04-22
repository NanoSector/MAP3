$(document).ready(function() 
{
	$("#sortable_categories").sortable(
	{
		placeholder: "ui-state-highlight",
		start: function (event, ui)
		{
			$(".ui-state-highlight").height(ui.item.height() - 20);
		},
		update: function(event, ui)
		{
			var order = [];
			$("#sortable_categories li").each(function(e)
			{
				order.push($(this).attr("id"));
			});
			var positions = order.join(",");
			$.ajax(
			{
				method: "post",
				data: "category_order=" + positions,
				url: m3_script_url + "?act=forum&saveorder"
			});
		}
	});
	$( "#sortable_categories" ).disableSelection();
});

function reorder(orderArray, elementContainer)
{
	$.each(orderArray, function(key, val)
	{
		elementContainer.append($("#"+val));
	});
}