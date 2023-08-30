<div>
  <img decoding="async" data-aos="fade-in"
  	src="/src/app/Storage/configuration/candidatesSMB.png" 
  	class="card-img-top w-100 noTouch" 
  />
  <div class="container mb-5">
	  <form id="formFilter">
	    <div class="row mt-4"> <!--data-aos="fade-up"-->
	      <div class="col-lg-12 d-flex justify-content-center ">
	        <div class="btn-group filterContainer" role="group">
	        	<div class="btn-group">
	            <button type="button" onclick="window.location.href='/select-campus/'" 
	            class="btn btn-light filterActive"><i class="fas fa-home"></i></button>
	          </div>
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
	        </div>
	      </div>
	    </div>
  	</form>
  	<div class="row row-cols-1 row-cols-md-3 g-4 mt-3" id="candidateList_card">
  		<!-- data fetch thru ajax -->
		</div>
	</div>
</div>

  <div class="modal fade" id="modalVoteForm">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header" style="background: #24382e;">
          <h6 class="modal-title text-uppercase">
          	<strong>

          	</strong>
          </h6> 
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
          <button type="button" id="submitVote"
            class="btn btn-success btnSubmitVote"> <i class="fas fa-arrow-right"></i>&nbsp;Submit Vote
          </button>
        </div>
      </div>
    </div>
  </div>

<script src="/resources/custom/script/votes_stamaria.js" defer></script>
<script src="/resources/custom/script/functions.js" defer></script>

<script defer>
	//check the uri if this contains '/admin/' print this is admin otherwise its user
	const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>';
 	const isAdmin = BASE_URI.includes('/admin/');
	if(isAdmin) {
    //console.log('This is admin');
	} else {
	  //console.log('This is user');
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