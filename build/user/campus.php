<section id="campusSelect">
	<div class="row mt-0" data-aos="fade-up">
		<div class="col-md-3"></div>
				<div class="col-md-6 mt-0">
					<div class="card cardWelcome">
						<img src="/src/app/Storage/configuration/bannerMain.png" class="bannerImage img-card-top">
						<div class="container">
							<div class="instruction alert alert-warning d-flex align-items-center mt-3" role="alert">
								<small>To ensure a successful and accurate voting process, kindly select the appropriate branch or campus for your candidates.</small>
							</div>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-md-6 mb-2">
									<a href="/candidates/sta-maria/">
										<div class="card cardCampus">
											<img src="/src/app/Storage/configuration/sta.maria.png" class="img-card-top">
										</div>
									</a>
								</div>
								<div class="col-md-6">
									<a href="/candidates/balagtas/">
										<div class="card cardCampus">
											<img src="/src/app/Storage/configuration/balagtas.png" class="img-card-top">
										</div>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="developer text-center mt-3">
						<small class="d-block">&copy; 2023 - 
							<a href="https://www.goldenmindsbulacan.com/" target="_blank" class="text-dark">
								Golden Minds Bulacan
							</a>
						</small>
					  <small>Maintain and Manage by Information System</small>
				</div>
				</div>
				<div class="col-md-3"></div>
			</div>	
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

  function preventBack() {
	 	window.history.forward(); 
	}
	setTimeout("preventBack()", 0);
	window.onunload = function(){null};

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