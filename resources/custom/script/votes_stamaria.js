jQuery(document).ready(function() {
	const RENDER_PURPOSE = 'clientSide';
	const BASE_BRANCH = "Golden Minds Colleges - Sta.Maria";

	//getAllCandidatesByBranch(BASE_BRANCH);
	getAllCandidatesData();

	/**[To submit the votes] **/ 
	$(document).on('submit', '#submitVoteMForm', (e) => {
		e.preventDefault();
		const REGX_EMAIL = /^([a-zA-z]+)([0-9]+)?(@)([a-zA-Z]{5,10}(.)([a-zA-Z]+))$/i;
		const maxLength = parseInt(13);
		let selectedAmtPayment = $('#paymentSelectedHidden').val();
		let equivalentVotePoints = $('#equivalentVotePoints').val();
		let votersEmail = $('#votersEmail').val();
		let referrenceNumber = $('#referrenceNumber').val();
		//alert("payment: " + selectedAmtPayment + " at sa vote points: " + equivalentVotePoints);
		//start validate input
		if(isEmpty(selectedAmtPayment) || isEmpty(equivalentVotePoints)  || isEmpty(referrenceNumber) || isEmpty(votersEmail)) {
			Swal.fire({
        title: '',
        html: '<h5>All fields is required</h5>',
        icon: 'info',
        confirmButtonText: 'Okay'
      });
		} else if(referrenceNumber.length !== maxLength || referrenceNumber.length > maxLength) {
			Swal.fire({
        title: '',
        html: '<h5>Reference number should have exactly 13 characters!</h5>',
        icon: 'info',
        confirmButtonText: 'Okay'
      });
		} else if(!REGX_EMAIL.test(votersEmail)) {
			Swal.fire({
        title: '',
        html: '<h5>Please enter a valid email address!</h5><small>example: "javecilla@gmail.com"</small>',
        icon: 'info',
        confirmButtonText: 'Okay'
      });
		} else {
			//send request to google api:recaptcha
			grecaptcha.ready(function() {
				grecaptcha.execute('6LcyI_snAAAAAHW5adG9Y-MzsIzUTNEFUP35gZXj', {
          action: 'submit'
        }).then(function(token){
					const formData = new FormData();
					formData.append('sid', $('#vSID').val());
					formData.append('amtPayment', selectedAmtPayment);
					formData.append('votePoints', equivalentVotePoints);
					formData.append('referrenceNumber', referrenceNumber);
					formData.append('votersEmail', votersEmail);
					formData.append('ctoken', token);
					formData.append('gaction', 'submit');
					formData.append('action', 'create');
				
					Swal.fire({
		       	title: "Confirm Your Vote",
		    		text: "Please take a moment to verify your vote and ensure that you have selected your intended candidate.",
		        icon: "info",
		        showConfirmButton: true,
		        confirmButtonText: "Okay",
		        confirmButtonColor: "#5f76e8",
		        showCancelButton: true,
		        cancelButtonText: "Cancel",
		        showLoaderOnConfirm: true,
		        allowEscapeKey : false,
		        allowOutsideClick: false,
		        preConfirm: (response) => { 
		          if(!response) {
		            return false;
		          } else {
		            return new Promise(function(resolve) { 
		              setTimeout(function () { 
		              	$.ajax({
											url: "../../src/app/Actions/HClientVotes.php",
											method: "POST",
											data: formData,
											dataType: "JSON",
											contentType: false,
							        processData: false,
											success: (response) => {
												try	{
													const serverResponse = JSON.parse(JSON.stringify(response));
													if(serverResponse.success) { //voting submit request successfully proccess
														Swal.fire({
							                title: '',
							                html: `<h5>${serverResponse.message}</h5>`,
							                icon: 'success',
							                confirmButtonText: 'Vote again this candidate',
							                showCancelButton: true,
		        									cancelButtonText: "Done and exit"
							              }).then((result) => {
							              	$('#selectPayment').val('');
							                $('#equivalentVotePoints').val('');
							                $('#referrenceNumber').val('');
							                $('#votersEmail').val('');
		      										$('#qrCodeImage').attr('src', '');
							              	(result.isConfirmed) ? openModal('#modalVoteForm') : closeModal('#modalVoteForm');
							              });
													} else { //something wrong upon proccessing ...voting request
														Swal.fire({
							                title: '',
							                html: `<h5>${serverResponse.message}</h5>`,
							                icon: 'error',
							                focusConfirm: false,
							                confirmButtonColor: '#880808',
							                confirmButtonText: 'Okay'
							              }).then((result) => { return result.isConfirmed ? false : undefined; });
													}
												} catch(error) {
													Swal.fire({
							              title: '',
							              html: `<h5>${error}</h5>`,
							              icon: 'error',
							              focusConfirm: false,
							              confirmButtonColor: '#880808',
							              confirmButtonText: 'Okay'
							            }).then((result) => { return result.isConfirmed ? false : undefined; });
												}
											},
											error(xhr, staus, error) {
												Swal.fire({
							            title: '',
							            html: `<h5>${xhr.responseText}<br/>${error.responseText}</h5>`,
							            icon: 'error',
							            focusConfirm: false,
							            confirmButtonColor: '#880808',
							            confirmButtonText: 'Okay'
							          }).then((result) => { return result.isConfirmed ? false : undefined; });
											}
										});
		                        
		              }, 2000);
		            });
		          }
		        },
		      	allowOutsideClick: () => !Swal.isLoading()
		      });

       }); //end callback execute
			}); //end grecaptcha>ready
		}
	});


	
	$(document).on('click', '.list-group-item[data-action="payment"]', (e) => {
	  e.preventDefault();
	  const selectedButton = $(e.currentTarget);
	  const selectedAmount = selectedButton.data('amount');
	  
	  const paymentOptions = {
	    '10': { amount: '10', points: '10', qrcode: '/src/app/Storage/qrcodes/IMG-qr=10.PNG' },
	    '20': { amount: '20', points: '40', qrcode: '/src/app/Storage/qrcodes/IMG-qr=10.PNG' },
	    '50': { amount: '50', points: '75', qrcode: '/src/app/Storage/qrcodes/IMG-qr=50.PNG' },
	    '100': { amount: '100', points: '200', qrcode: '/src/app/Storage/qrcodes/IMG-qr=100.PNG' }
	  };
	  
	  const payment = paymentOptions[selectedAmount];
	  const { amount, points, qrcode } = payment;

	  $('#equivalentVotePoints').val(points);
	  $('#paymentSelectedHidden').val(amount);
	  $('#qrCodeImage').attr('src', qrcode);
	});

	$(document).on('click', '.btn-next-step[data-confirm="proceed"]', (e) => {
		e.preventDefault();
		$('#firstStep').hide();
		$('#secondStep').show();
		$('#confirmButton').hide();
		$('#guidStepText').text('To complete your vote submission, please enter the reference number provided by gcash and your contact number, and then click the "Submit Vote" button');
	});	


	/**[To render data in modal of candidate by id]**/
	$(document).on('click', '.voteBtn', (e) => {
		e.preventDefault();
		openModal('#modalVoteForm');
		const candidatesid = $(e.currentTarget).data('id');
		const candidateSbranch = $(e.currentTarget).data('branch');
		const candidateCategory = $(e.currentTarget).data('category');
		const view = "view";
		$('.modal-title').text(`${candidateCategory} | ${candidateSbranch}`);
		getCandidateDataById(candidatesid, view, RENDER_PURPOSE);
	});

	/**[To filter data by category]**/
	$('.filter-item').on('click', (e) => {
		e.preventDefault();
		const category = $(e.currentTarget).data('target');
		$('.filter-item').removeClass('filterActive').addClass('filterNotActive');
		$(e.currentTarget).removeClass('filterNotActive').addClass('filterActive');
		if(category === "All") {
			//getAllCandidatesByBranch(BASE_BRANCH);
			getAllCandidatesData();
			$('#searchInput').addClass('displayNone');
			$('#categoryFilterItemList').val('');
		} else {
			getCandidateDataByCategory(category, RENDER_PURPOSE);
			$('#searchInput').removeClass('displayNone');
			$('#categoryFilterItemList').val(category);
		}
	});

	/**[To search the candidate] **/ 
	$('#searchCandidate').on('keyup', (e) => {
		e.preventDefault();
		let searchQuery = $(e.currentTarget).val();
		let category = $('#categoryFilterItemList').val();
		//alert(category);
		if(isEmpty(searchQuery)) {
      //back the original data
      getCandidateDataByCategory(category, RENDER_PURPOSE);
    } else {
    	searchCandidateCategory(searchQuery, category);
    }
	});
});