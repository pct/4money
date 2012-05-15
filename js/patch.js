function hide_menu(){
	$('#sidebar').css('display','none');
	$('#main').css('width','95%');
	$('#hide_menu').text('顯示選單')
}
$(function() {
	$('#hide_menu').click(function(){
		var t=$('#hide_menu');
		if(t.text()=="隱藏選單"){
			hide_menu()
		}else{
			$('#sidebar').css('display','');
			$('#main').css('width','77%');
			t.text('隱藏選單')
		}
	})
})