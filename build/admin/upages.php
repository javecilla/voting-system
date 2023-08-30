<?php session_start(); ?>

<section id="user_pages"
	style="
		background-image: url('/src/app/Storage/configuration/bg.png');
		background-size: cover;
		background-repeat: no-repeat;
		min-height: 100vh;
	">
  <div class="section-title">
    <h2>USER PAGES</h2>
  </div>

  <div class="banner_logo">
  	<img src="/src/app/Storage/configuration/LOGO3.png" alt="">
  </div>
</section>

<script src="/resources/custom/script/functions.js" defer></script>

<script>
	$(document).ready(function() {
		trackCurrentURI();
	});
</script>

<script>
  /**[Tool tips]**/
  const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

  /**[Track current page]**/
  function trackCurrentURI() {
    const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';
    const currentBaseURI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>'; 

    if (currentBaseURI === '/admin/user-pages/') { 
      $('.sb_upages').addClass('active');
    } else {
      $('.sb_upages').removeClass('active');
    }
  }
</script>