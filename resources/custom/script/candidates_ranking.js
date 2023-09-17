jQuery(document).ready(function() {
  trackCurrentURI();
  getCandidatesRank();

  // to fetch data ranking by branch and category
  $('#filterCategory').on('change', (e) => {
    e.preventDefault();
    const categoryActive = $(e.currentTarget).val();
    (isEmpty(categoryActive)) ? getCandidatesRank() : getCandidatesRankByCategory(categoryActive);
  });
});