  jQuery(document).ready(function() {
    trackCurrentURI();
    getCandidateByRank($('#filterActiveValue').val(), $('#filterCategory').val());

    //to fetch data ranking only by branch
    $('.filter-item').on('click', (e) => {
      e.preventDefault();
      const branchActive = $(e.currentTarget).data('value');
      const categoryActive = $('#filterCategory').val();

      $('#filterActiveValue').val(branchActive);
      $('.filter-item').removeClass('filterBtnActive').addClass('filterBtnNotActive');
      $(e.currentTarget).removeClass('filterBtnNotActive').addClass('filterBtnActive');
      //if(isEmpty(categoryActive))
      getCandidateByRank(branchActive, categoryActive);
    });

    // to fetch data ranking by branch and category
    $('#filterCategory').on('change', (e) => {
      e.preventDefault();
      const categoryActive = $(e.currentTarget).val();
      const branchActive = $('#filterActiveValue').val();
      getCandidateByRank(branchActive, categoryActive);
    });
  });