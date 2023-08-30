<section id="voting_records">
  <div class="section-title">
    <h2>Voting Records</h2>
  </div>
</section>

<script type="text/javascript" defer>
  $(document).ready(function() {
    $(window).on('load', function() {
      const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';

      if(currentPage === 'voting-records') {
        $('.sb_vrecords').addClass('active');
      } else {
        $('.sb_vrecords').removeClass('active');
      }
    });
  });   
</script>