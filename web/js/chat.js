$(document).ready(function(){
	$('.time').each(function(index, data){
		var time = parseInt(
			$(data).html().replace('at ', '')
		);
		$(data).html(
			'at ' + convertTimestamp(time)
		);
	});
	$('.messages').scrollTop(1E10);
});