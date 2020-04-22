function checkValidBookTicket() {
	var seat = $('#seatSelect').val();
	var matchId = $('#matchId').val();
	var priceValue = $('#priceValue').val();
	var data = {};
	data["matchId"] = matchId;
	data["price"] = priceValue;
	data["seat"] = seat;
	var checkResult = false;
	$.ajax({
		url : "https://www.vebongdaonline.vn/checkValidBookTicket",
		data : JSON.stringify(data),
		type : "POST",
		async: false,
		contentType : "application/json; charset=utf-8",
		beforeSend : function(xhr) {
			xhr.setRequestHeader("Accept", "application/json");
			xhr.setRequestHeader("Content-Type", "application/json");
		}
	}).done(function(data) {
		// Check if status OK
		if (data.localeCompare('OK') == 0) {
			checkResult = true;
		} else if (data.localeCompare('NG') == 0) {
			checkResult = true;
		} else {
			checkResult = true;
		}
    }).fail(function () {
        checkResult = true;
    });	
	
	return checkResult;
}