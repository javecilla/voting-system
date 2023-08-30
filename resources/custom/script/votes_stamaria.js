$(document).ready(function() {
	const RENDER_PURPOSE = 'clientSide';
	const BASE_BRANCH = "Golden Minds Colleges - Sta.Maria";

	getAllCandidatesByBranch(BASE_BRANCH);

	/**[To submit the votes] [NOT FINISH YET]**/
	$('#submitVote').on('click', (e) => {
		e.preventDefault();
		alert("submit vote [to continue tulog na ko gaiz :>]");
		closeModal('#modalVoteForm');
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
		const target = $(e.currentTarget).data('target');
		$('.filter-item').removeClass('filterActive').addClass('filterNotActive');
		$(e.currentTarget).removeClass('filterNotActive').addClass('filterActive');
	  (target === "All") ? getAllCandidatesByBranch(BASE_BRANCH) :  alert(target);
	});
});