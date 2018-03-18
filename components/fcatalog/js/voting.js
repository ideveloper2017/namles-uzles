function doRate(kvote, kitem_id){
	$("#voting"+kitem_id).html("<div>Отправка...</div>");
	$("#voting"+kitem_id).load("/components/fcatalog/ajax/rating.php", {item_id: kitem_id, vote: kvote});
}
