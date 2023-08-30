<?php session_start(); ?>


<section id="dashboard">
  <div class="section-title">
    <h2>Dashboard</h2>
  </div>
</section>

<script type="text/javascript" defer>
  $(document).ready(function() {
    $(window).on('load', function() {
      const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';

      if(currentPage === 'dashboard') {
        $('.sb_dashboard').addClass('active');
      } else {
        $('.sb_dashboard').removeClass('active');
      }
    });
  });   
</script>