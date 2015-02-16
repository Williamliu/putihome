$(function(){
	  var admin_user_right = $.parseJSON(admin_user_right_str);
	  for(var key in admin_user_right) {
		  if(!admin_user_right[key]) {
			 $(":button[right='" + key + "'], a[right='" + key + "']").attr("disabled", "disabled");
		  }
	  }
})
	
