<div class="area">
	<ul class="circles">
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>
		<li></li>	
	</ul>
	<center>
		<img src="/src/app/Storage/configuration/heroOne.png" class="noTouch card-img-top p-0"/>
	</center>
	<nav class="navbar sticky-top bg-light pt-0" style="border-top: 3px solid #f1e4d5;">
    <img src="/src/app/Storage/configuration/headerTwo.png"  alt="Logo" class="card-img-top">
	</nav>

  <div class="container mb-5">

	  <form id="formFilter">
	    <div class="row mt-4"> <!--data-aos="fade-up"-->
	      <div class="col-lg-12 d-flex justify-content-center ">
	        <div class="btn-group filterContainer" role="group">
	         	<div class="btn-group">
	            <button type="button" data-target="All" class="filter-item btn btn-light filterActive">All</button>
	          </div>
	          <div class="btn-group">
	          	<button type="button" data-target="Lakan" class="filter-item btn btn-light filterNotActive">Lakan</button>
	          </div>
	          <div class="btn-group" role="group">
	            <button type="button" data-target="Lakanbini" class="filter-item btn btn-light filterNotActive">Lakanbini</button>
	          </div>
	          <div class="btn-group" role="group">
	            <button type="button" data-target="Lakandyosa" class="filter-item btn btn-light filterNotActive">Lakandyosa</button>
	          </div>
	          <input type="hidden" value="" id="categoryFilterItemList"/>
	        </div>
	      </div>
	      <div class="col-lg-12 d-flex justify-content-center ">
	      	<center class="mt-3">
		      	<div class="input-group mb-3 displayNone" id="searchInput">
						  <span class="input-group-text filterActive"><i class="fas fa-search text-dark fw-2"></i></span>
						  <div class="form-floating">
						    <input type="text" class="form-control" id="searchCandidate" placeholder="Search">
						    <label for="searchCandidate" >Type candidate name/no</label>
						  </div>
						</div>
					</center>
	      </div>
	    </div>
  	</form>
  	 <div class="instruction alert alert-warning alert-dismissible fade show" role="alert">
  		<strong><i class="fas fa-info-circle"></i> Important: </strong> <span>Please carefully choose your preferred candidates to ensure an accurate and smooth voting process.</span>
  		<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
  	<div class="row row-cols-1 row-cols-md-3 g-4 mt-3" id="candidateList_card">
  		<!-- data fetch thru ajax -->
		</div>
	</div>
</div>
	
	<form id="submitVoteMForm">
	  <div class="modal fade" id="modalVoteForm">
	    <div class="modal-dialog modal-dialog-scrollable modal-lg">
	      <div class="modal-content">
	        <div class="modal-header" style="background: #24382e;">
	          <h6 class="modal-title text-uppercase"><strong></strong></h6> 
	          <button type="button" class="btn text-white" onclick="closeModal('#modalVoteForm')">
	            <i class="fa-solid fa-xmark closeModalIcon"></i>
	          </button>
	        </div>
	        <div class="modal-body" id="mbodyCandidatesClientView">
	          <!-- data fetch thru ajax -->
	        </div>
	        <div class="modal-footer">
	          <button type="button" onclick="closeModal('#modalVoteForm')" 
	            class="btn btn-secondary"> Close</button>
	          <button type="submit" id="submitVote"
	            class="grecaptcha btn btn-success btnSubmitVote"> <i class="fas fa-arrow-right"></i>&nbsp;Submit Vote
	          </button>
	        </div>

	      </div>
	    </div>
	  </div>
	</form>

 	<nav class="soc_icons">
    <ul>
    	<li><a href="https://portal.goldenmindsbulacan.com/auth/login/"
        target="_blank" 
        data-bs-toggle="tooltip" 
        data-bs-placement="right" 
       data-bs-title="Online Portal | Golden Minds Website"><i class="fa-solid fa-user-gear"></i><span></span></a></li>
      <li><a href="https://www.goldenmindsbulacan.com/"
        target="_blank" 
        data-bs-toggle="tooltip" 
        data-bs-placement="right" 
        data-bs-title="Golden Minds Website"><i class="fas fa-globe"></i><span></span></a></li>
      <li><a href="https://www.facebook.com/gmcstamaria2015"
        target="_blank" 
        data-bs-toggle="tooltip" 
       	data-bs-placement="right" 
       	data-bs-title="Facebook"><i class="fab fa-facebook-f"></i><span></span></a></li>
      <li><a href="mailto:info@goldenmindsbulacan.com"
        target="_blank" 
        data-bs-toggle="tooltip" 
       	data-bs-placement="right" 
       	data-bs-title="Mail"><i class="fa-solid fa-envelope"></i><span></span></a></li>  
      <li><a href="https://www.youtube.com/@goldenmindscolleges7588" 
        target="_blank"
        data-bs-toggle="tooltip" 
       	data-bs-placement="right" 
       	data-bs-title="Youtube"><i class="fab fa-youtube"></i><span></span></a>
      </li>
    </ul>
  </nav>
  <!-- back to top -->
  <div id="back-to-top">
    <a class="p-0 btn btt_btn" id="top" href="#top">
      <i class="fa-solid fa-arrow-up btt_icon"></i>
    </a>
  </div>
	<script src="/resources/custom/script/votes_stamaria.js" defer></script>
	<script src="/resources/custom/script/functions.js" defer></script>

	<script defer>
    jQuery(document).ready(function() {
      const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
      const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

      $(window).scroll(function() {
        if ($(this).scrollTop() > 150) {
          $('#back-to-top').fadeIn();
        } else {
          $('#back-to-top').fadeOut();
        }
      });

      $('#back-to-top').click(function(e) {
        e.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 'slow');
      });

     	//check the uri if this contains '/admin/' print this is admin otherwise its user
			const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>';
		 	if(BASE_URI === "/buwan-ng-wikang-pambansa-2023-lakan-lakanbini-lakandyosa/candidates/") {
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
    });
</script>