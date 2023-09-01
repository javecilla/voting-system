<nav class="bg-light nav_breadcrumb" aria-label="breadcrumb" >
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active">Voting Records</li>
  </ol>
</nav>

<section id="voting_records">
  <div class="section-title"><h2>Voting Records</h2></div>
  <div id="filter" class="mt-4">
    <div class="row mt-4"> <!--data-aos="fade-up"-->
      <div class="col-lg-4">
        <ul class="nav nav-pills">
          <li class="nav-item mr-4">
            <button class="filter-item nav-link btn btn-primary filterBtnActive"
            type="button" 
            aria-current="page"
            data-value="All"
            >All</button>
          </li>
          <li class="nav-item  mr-4" id="pendingVoteStaMaria">
          </li>
          <li class="nav-item" id="pendingVoteBalagtas">
          </li>
          <input type="hidden" id="filterActiveValue">
        </ul>
      </div>
 
      <div class="col-lg-8">
        <div class="input-group">
          <input type="text" class="form-control"
            id="searchInput" 
            list="candidateSuggestions"
            placeholder="Type candidate name or referrence number..." 
            aria-label="Search Candidate"
            autocomplete="off" 
          />
          <datalist id="candidateSuggestions">
            <option value="Jerome Avecilla">
            <option value="halimaw mag selos">
            <option value="kaka code mo yan">
          </datalist>
          <button type="button" class="input-group-text" id="searchBtn">
            <i class="fas fa-search"></i>&nbsp;<span class="d-none d-sm-block"> Search</span>
          </button>
        </div>
      </div>  
    </div>
  </div>
  <div class="content_body card mt-4">
    <table class="table table-responsive table-striped">
      <thead>
        <tr>
          <th scope="col">VID</th>
          <th scope="col">SID</th>
          <th scope="col">Amount Payment</th>
          <th scope="col">Points</th>
          <th scope="col">Referrence no.</th>
          <th scope="col">Voters Email</th>
          <th scope="col">Status</th>
          <th scope="col">Datetime</th>
          <th scope="col" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="tbodyVotesRecords"><!--data fetch thru ajax-->
        
      </tbody>
    </table>
  </div>
</section>


<script src="/resources/custom/script/votes_records.js"></script>

<script src="/resources/custom/script/functions.js" defer></script>


<script defer>
  /**[Tool tips]**/
  const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
  /**[Track current page]**/
  function trackCurrentURI() {
    //const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';
    const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>'; 

    if (BASE_URI === '/admin/voting-records/') { 
      $('.sb_vrecords').addClass('active');
    } else {
      $('.sb_vrecords').removeClass('active');
    }
  }
</script>