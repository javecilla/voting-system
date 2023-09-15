jQuery(document).ready(function() {
	const RENDER_PURPOSE = 'clientSide';
	const BASE_BRANCH = "Golden Minds Colleges - Sta.Maria";

	getAllCandidatesByBranch(BASE_BRANCH);

	/**[To submit the votes] **/ 
	$('#submitVote').on('click', (e) => {
		e.preventDefault();
		const REGX_EMAIL = /^([a-zA-z]+)([0-9]+)?(@)([a-zA-Z]{5,10}(.)([a-zA-Z]+))$/i;
		const maxLength = parseInt(13);
		let selectedAmtPayment = $('#selectPayment').val();
		let equivalentVotePoints = $('#equivalentVotePoints').val();
		let referrenceNumber = $('#referrenceNumber').val();
		let votersEmail = $('#votersEmail').val();
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
			const formData = new FormData();
			formData.append('sid', $('#vSID').val());
			formData.append('amtPayment', selectedAmtPayment);
			formData.append('votePoints', equivalentVotePoints);
			formData.append('referrenceNumber', referrenceNumber);
			formData.append('votersEmail', votersEmail);
			formData.append('action', 'create');
			
			// formData.forEach((value, key) => {
    	// 	console.log(`${key}: ${value}`);
			// });

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
        									cancelButtonText: "Done and exit",
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
		}
	});

	/**[To process ammount payment]**/
	$(document).on('change', '#selectPayment', (e) => {
    e.preventDefault();
    const selectedPayment = $(e.currentTarget).val();
    //define payment options with their amounts, points and qrcode
    const paymentOptions = {
      '10': { amount: '10', points: '10', qrcode: '/src/app/Storage/qrcodes/sample_qr_payment=10.jpg' },
      '50': { amount: '50', points: '75', qrcode: '/src/app/Storage/qrcodes/sample_qr_payment=50.png' },
      '100': { amount: '100', points: '200', qrcode: '/src/app/Storage/qrcodes/sample_qr_payment=100.png' }
   	};
    //check if yung selected payment option exists doon paymentOptions object
    if(paymentOptions.hasOwnProperty(selectedPayment)) {
    	//get payment details (amount, points, qrcode)
      const payment = paymentOptions[selectedPayment];
      const { amount, points, qrcode } = payment;

      //console.log(`${amount} pesos for ${points} vote points`);
      $('#equivalentVotePoints').val(points);
      $('#qrCodeImage').attr('src', qrcode);
   	} else { 
      //console.log('Unknown payment selected');
      $('#equivalentVotePoints').val('');
      $('#qrCodeImage').attr('src', '');
   	}
	});

	/**[To render data in modal of candidate by id]**/
	$(document).on('click', '.voteBtn', (e) => {
		e.preventDefault();
		openModal('#modalVoteForm');
		// const dataId = $('.candidateVoteSid').val();
		const candidatesid = $(e.currentTarget).data('id');
		const candidateCategory = $('.candidateBranch').val();
		const candidateSbranch = $('.candidateCategory').val();
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
			getAllCandidatesByBranch(BASE_BRANCH);
			$('#searchInput').addClass('displayNone');
			$('#categoryFilterItemList').val('');
		} else {
			getCandidateDataByCategoryBranch(category, BASE_BRANCH, RENDER_PURPOSE);
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
      getCandidateDataByCategoryBranch(category, BASE_BRANCH, RENDER_PURPOSE);
    } else {
    	searchCandidateCategoryBranch(searchQuery, category, BASE_BRANCH);
    }
	});
});