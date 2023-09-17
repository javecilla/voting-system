<?php
session_start();

if(!isset($_SESSION['currentUser'])) {
  header('Location: http://127.0.0.1:8080/auth/login/');
  //header('Location: https://portal.goldenmindsbulacan.com/auth/login/');
  exit();
}
?>

<nav class="bg-light nav_breadcrumb" aria-label="breadcrumb" >
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="#">Admin</a></li>
    <li class="breadcrumb-item active" aria-current="page">Candidate Management</li>
  </ol>
</nav>

<section id="candidate_management" class="portfolio">
  <div class="section-title"><h2>Candidate Management</h2></div>
  <div class="content_header">
    <div class="input-group">
      <button type="button" class="input-group-text" id="searchBtn">
        <i class="fas fa-search"></i>&nbsp;<span class="d-none d-sm-block"> Search</span>
      </button>
      <input type="text" class="form-control"
        id="searchInput" 
        list="candidateSuggestions"
        placeholder="Type candidate name or number..." 
        aria-label="Search Candidate"
        autocomplete="off" 
      />
      <datalist id="candidateSuggestions">
        <option value="Jerome Avecilla">
        <option value="halimaw mag selos">
        <option value="kaka code mo yan">
      </datalist>

      <button class="input-group-text" type="button" onclick="openModal('#addNewCandidateModal')">
        <i class="fas fa-user-plus"></i> <span class="d-none d-sm-block">&nbsp; Candidate</span>
      </button>
    </div>
  </div>

  <form id="formFilter">
    <div class="row mt-4"> <!--data-aos="fade-up"-->
      <div class="col-lg-12 d-flex justify-content-center ">
        <div class="btn-group" role="group">
          <span class="btn filterList noEvents"><i class="fas fa-filter"></i>&nbsp;Filter</span>
          <!-- filter by category -->
          <div class="btn-group" role="group">
            <select id="filterCategory">
              <option value="" selected>All</option>
              <option value="Lakan">Lakan</option>
              <option value="Lakanbini">Lakanbini</option> 
              <option value="Lakandyosa">Lakandyosa</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </form>

  <div class="content_body table-responsive card mt-4">
    <table class="table">
      <thead>
        <tr>
          <th scope="col">SID</th>
          <th scope="col">CID</th>
          <th scope="col">Photo</th>
          <th scope="col">Name</th>
          <th scope="col">Category</th>
          <th scope="col" class="text-center">Actions</th>
        </tr>
      </thead>
      <tbody id="tbodyCandidates"><!--data fetch thru ajax--></tbody>
    </table>
  </div>

  <!--================= [Modal Content] =================-->
  <!--[Add New Candidate Modal] -->
  <div class="modal fade" id="addNewCandidateModal">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title text-uppercase"><strong>Add New Candidate</strong></h6> 
          <button type="button" class="btn text-white" onclick="closeModal('#addNewCandidateModal')">
            <i class="fa-solid fa-xmark closeModalIcon"></i>
          </button>
        </div>
        <div class="modal-body ">
          <div id="form_container">
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" 
              enctype="multipart/form-data" id="addDataForm">
              <center class="candidate_image_upload">
                <div class="file-upload">
                  <div class="image-upload-wrap">
                    <input class="file-upload-input" type='file' id="image_upload"
                      onchange="uploadImage(this);" accept="image/jpeg, jpg, png" 
                    />
                    <div class="drag-text"><h3>Drag and drop a photo or select browse image</h3></div>
                  </div>
                  <div class="file-upload-content">
                    <img class="file-upload-image img-responsive img-thumbnail mt-4" src="" 
                      alt="your image" width="200"/>
                  </div>
                </div>
                <button type="button" onclick="$('.file-upload-input').click()"
                  class="btn btn-light browse_file">
                  <i class="fa-solid fa-image"></i>&nbsp; Browse Image
                </button>
                <button type="button" onclick="removeImage('#image_upload')" 
                  class="btn btn-danger remove_file" style="display: none;">
                  <i class="fa-solid fa-minus"></i>&nbsp; 
                  <span class="image_upload_name"></span>
                </button>
              </center>
              <div class="container mt-3">
                <div class="row ">
                  <div class="col-md-6">
                    <label for="selectCategory" class="col-sm-4 col-form-label">Category: 
                      <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="selectCategory">
                      <option value="" selected>-- SELECT --</option>
                      <option value="Lakan">Lakan</option>
                      <option value="Lakanbini">Lakanbini</option>
                      <option value="Lakandyosa">Lakandyosa</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="selectBranch" class="col-sm-4 col-form-label">Branch:
                      <span class="text-danger">*</span>
                    </label>
                    <select class="form-select" id="selectBranch">
                      <option value="" selected>-- SELECT --</option>
                      <option value="Golden Minds Colleges - Sta.Maria">Golden Minds Colleges - Sta.Maria</option>
                      <option value="Golden Minds Colleges - Balagtas">Golden Minds Colleges - Balagtas</option>
                    </select>
                  </div>
                  <div class="col-md-6">
                    <label for="inputCandidateName" class="col-sm-4 col-form-label">Name: 
                      <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="inputCandidateName" class="form-control" 
                    placeholder="Candidate name..."/>
                  </div>
                  <div class="col-md-6">
                    <label for="inputCandidateNo" class="col-sm-4 col-form-label">CID: 
                      <span class="text-danger">*</span>
                    </label>
                    <input type="text" id="inputCandidateNo" class="form-control" 
                    placeholder="Candidate no..."/>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer mt-3">
          <button type="button" onclick="closeModal('#addNewCandidateModal')" 
            class="btn btn-secondary"> Cancel</button>
          <button  type="button" id="addNewCandidate"
            class="btn btn-primary">
            <i class="fas fa-spinner fa-spin loading-spinner" style="display: none;"></i> 
            <i class="fa-solid fa-arrow-up-from-bracket upload_icon"></i>&nbsp;
            Upload
          </button>
        </div>
      </div>
    </div>
  </div>
  <!--[End Add New Candidate Modal] -->

  <!--[View Data Candidate Modal] -->
  <div class="modal fade" id="viewDataCandidateModal">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title text-uppercase"><strong>Candidate SID: 
            <span style="font-size: 25px!important" class="candidatesid"></span></strong>
          </h6> 
          <button type="button" class="btn text-white" onclick="closeModal('#viewDataCandidateModal')">
            <i class="fa-solid fa-xmark closeModalIcon"></i>
          </button>
        </div>
        <div class="modal-body" id="mbodyCandidatesView">
          <!-- data fetch thru ajax -->
        </div>
        <div class="modal-footer">
          <button type="button" onclick="closeModal('#viewDataCandidateModal')" 
            class="btn btn-secondary"> Close</button>
        </div>
      </div>
    </div>
  </div>
  <!--[End View Data Candidate Modal] -->

  <!--[Update Data Candidate Modal] -->
  <div class="modal fade" id="editDataCandidateModal">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title text-uppercase"><strong>Update Candidate SID: 
            <span style="font-size: 25px!important" class="candidatesid"></span></strong>
          </h6> 
          <button type="button" class="btn text-white" onclick="closeModal('#editDataCandidateModal')">
            <i class="fa-solid fa-xmark closeModalIcon"></i>
          </button>
        </div>
        <div class="modal-body" id="mbodyCandidatesEdit">
          <!-- data fetch thru ajax -->
        </div>
        <div class="modal-footer">
          <button type="button" onclick="closeModal('#editDataCandidateModal')" 
            class="btn btn-secondary"> Cancel</button>
          <button  type="button" id="editDataCandidate"
            class="btn btn-primary"> <i class="fas fa-save"></i>&nbsp;Update
          </button>
        </div>
      </div>
    </div>
  </div>
  <!--[End Update Data Candidate Modal] -->
  <!--================= [End Modal Content] =================-->
</section>

<script src="/resources/custom/script/candidate_management.js"></script>
<script src="/resources/custom/script/functions.js" defer></script>

<script>
  /**[Tool tips]**/
  const tooltipTriggerList = $('[data-bs-toggle="tooltip"]');
  const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));

  /**[Track current page]**/
  function trackCurrentURI() {
    //const currentPage = '<?= basename($_SERVER['REQUEST_URI']);?>';
    const BASE_URI = '<?= parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH); ?>'; 

    if (BASE_URI === '/admin/candidate-management/') { 
      $('.sb_cmanagement').addClass('active');
    } else {
      $('.sb_cmanagement').removeClass('active');
    }
  }
</script>

