<?php
session_start();

if(!isset($_SESSION['currentUser'])) {
  header('Location: http://127.0.0.1:8080/auth/login/');
  exit();
}
?>

<nav class="bg-light nav_breadcrumb" aria-label="breadcrumb" >
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Candidates Rankings</li>
  </ol>
</nav>

<section id="voting_records">
  <div class="section-title"><h2>Candidates Ranking</h2></div>
  <div id="filter" class="mt-4">
    <div class="row mt-4"> <!--data-aos="fade-up"-->
      <div class="col-lg-8">
        <ul class="nav nav-pills">
          <li class="nav-item mr-4">
            <button class="filter-item nav-link btn btn-primary filterBtnActive"
            type="button" 
            aria-current="page"
            data-value="Golden Minds Colleges - Sta.Maria">Sta.Maria 
          </li>
          <li class="nav-item">
            <button class="filter-item nav-link btn position-relative filterBtnNotActive"
            id="balagtas"
            type="button"
            data-value="Golden Minds Colleges - Balagtas">Balagtas 
          </button>
          </li>
          <li class="nav-item">
            <div class="input-group mb-3">
          <select class="form-select cpointer" id="filterCategory">
            <option value="" selected>All</option>
            <option value="Lakan">Lakan</option>
            <option value="Lakanbini">Lakanbini</option> 
            <option value="Lakandyosa">Lakandyosa</option>
          </select>
          <label class="input-group-text" for="filterCategory">Category</label>
        </div>
          </li>
          <input type="hidden" id="filterActiveValue" value="Golden Minds Colleges - Sta.Maria">
        </ul>
      </div> 

    </div>
  </div>
  <div class="content_body table-responsive card mt-4">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">CID</th>
          <th scope="col">Photo</th>
          <th scope="col">Candidate Name</th>
          <th scope="col">Category</th>
          <th scope="col">Current Vote Points</th>
          <th scope="col">Number of Voters</th>
        </tr>
      </thead>
      <tbody id="tbodyCandidatesRanking"><!--data fetch thru ajax-->
        
      </tbody>
    </table>
  </div>
</section>


<script src="/resources/custom/script/candidates_ranking.js"></script>
<script src="/resources/custom/script/functions.js" defer></script>

<script defer>
  /**[Tool tips]**/
  const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
  /**[Track current page]**/
  function trackCurrentURI() {
    //const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';
    const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>'; 

    if (BASE_URI === '/admin/candidates-ranking/') { 
      $('.sb_crankings').addClass('active');
    } else {
      $('.sb_crankings').removeClass('active');
    }
  }
</script>