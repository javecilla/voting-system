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
    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
  </ol>
</nav>

<section id="dashboard">
  <div class="section-title">
    <h2>Dashboard</h2>
  </div>
  <div class="container" data-aos="fade-in">
    <div class="row">
      <div class="col-md-6 col-xl-4">
        <div class="card bg-c-blue order-card">
          <div class="card-block">
            <h4 class="m-b-20 "><b>Pending Votes</b></h4>
            <h2 class="text-left"><i class="fas fa-user-clock fs-1"></i>
              <span class="f-right" id="totalAllPending">
                
              </span>
            </h2>
            <p class="m-b-0">Sta.Maria
              <span class="f-right" id="pendingStamaria">
                
              </span>
            </p>
            <p class="m-b-0">Balagtas
              <span class="f-right" id="pendingBalagtas">
                
              </span>
            </p>
          </div>
        </div>
      </div>
        
      <div class="col-md-6 col-xl-4">
        <div class="card bg-c-blue order-card">
          <div class="card-block">
            <h4 class="m-b-20"><b>Total Voters</b></h4>
            <h2 class="text-left"><i class="fas fa-users fs-1"></i>
              <span class="f-right" id="totalVotersAll"></span>
            </h2>
            <p class="m-b-0">Sta.Maria
              <span class="f-right" id="totalVotersStamaria"></span>
            </p>
            <p class="m-b-0">Balagtas
              <span class="f-right" id="totalVotersBalagtas"></span>
            </p>
          </div>
        </div>
      </div>
        
      <div class="col-md-6 col-xl-4">
        <div class="card bg-c-blue order-card">
          <div class="card-block">
            <h4 class="m-b-18"><b>Total Amount</b></h4>
            <h2 class="text-left"><span class="fs-1">₱</span>
              <span class="f-right" id="totalAmmountAll"></span>
            </h2>
            <p class="m-b-0">Sta.Maria
              <span class="f-right" id="totalAmmountStamaria"></span>
            </p>
            <p class="m-b-0">Balagtas
              <span class="f-right" id="totalAmmountBalagtas"></span>
            </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script type="text/javascript">
  jQuery(document).ready(function() {
    const WALA = "";
    const STA_MARIA = "Golden Minds Colleges - Sta.Maria";
    const BALAGTAS = "Golden Minds Colleges - Balagtas";

    trackCurrentURI();

    getPendingVotes(WALA);
    getPendingVotes(STA_MARIA);
    getPendingVotes(BALAGTAS);

    getTotalVoters(WALA);
    getTotalVoters(STA_MARIA);
    getTotalVoters(BALAGTAS);

    getTotalAmmount(WALA);
    getTotalAmmount(STA_MARIA);
    getTotalAmmount(BALAGTAS);
  });
</script>

<script src="/resources/custom/script/functions.js" defer></script>

<script defer>
  function getTotalAmmount(branchname) {
    $.ajax({
      url: "../../src/app/Actions/HDashboard.php",
      method: "GET",
      dataType: "html",
      data: { 
        task: 'getTotalAmmountPayment',
        action: 'read',
        branchname: branchname
      },
      success: (data) => {
        if(branchname === "Golden Minds Colleges - Sta.Maria") {
          $('#totalAmmountStamaria').text(data);
        } else if(branchname === "Golden Minds Colleges - Balagtas"){
          $('#totalAmmountBalagtas').text(data);
        } else {
          $('#totalAmmountAll').text(data);
        } 
      }
    });
  }

  function getTotalVoters(branchname) {
    $.ajax({
      url: "../../src/app/Actions/HDashboard.php",
      method: "GET",
      dataType: "html",
      data: { 
        task: 'getNumberOfVoters',
        action: 'read',
        branchname: branchname
      },
      success: (data) => {
        if(branchname === "Golden Minds Colleges - Sta.Maria") {
          $('#totalVotersStamaria').text(data);
        } else if(branchname === "Golden Minds Colleges - Balagtas"){
          $('#totalVotersBalagtas').text(data);
        } else {
          $('#totalVotersAll').text(data);
        } 
      }
    });
  }

  function getPendingVotes(branchname) {
    $.ajax({
      url: "../../src/app/Actions/HDashboard.php",
      method: "GET",
      dataType: "html",
      data: { 
        task: 'getPendingVotes',
        action: 'read',
        branchname: branchname
      },
      success: (data) => {
        if(branchname === "Golden Minds Colleges - Sta.Maria") {
          $('#pendingStamaria').text(data);
        } else if(branchname === "Golden Minds Colleges - Balagtas"){
          $('#pendingBalagtas').text(data);
        } else {
          $('#totalAllPending').text(data);
        } 
      }
    });
  }


  /**[Tool tips]**/
  const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

  /**[Track current page]**/
  function trackCurrentURI() {
    //const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';
    const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>'; 

    if (BASE_URI === '/admin/dashboard/') { 
      $('.sb_dashboard').addClass('active');
    } else {
      $('.sb_dashboard').removeClass('active');
    }
  }
</script>

