<section>
	<h1>balagtas candidates list goes heree...</h1>
</section>


<script>
  //check the uri if this contains '/admin/' print this is admin otherwise its user
  const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>';
  const isAdmin = BASE_URI.includes('/admin/');
  if (isAdmin) {
    console.log('This is admin');
  } else {
    console.log('This is user');
   $('.admin_mobile_nav').hide();
   $('.sidebar_admin').hide();
   $('.adminContent').hide();
  }

	document.onkeydown = function(e) {
		if(e.keyCode == 123) {
		  return false;
		}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){
		  return false;
		}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){
		  return false;
		}
		if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){
		  return false;
		}
		if(e.ctrlKey && e.shiftKey && e.keyCode == 'C'.charCodeAt(0)){
		 	return false;
		}      
 	}
</script>