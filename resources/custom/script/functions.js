    /**[Fetch ALl Candidate List]**/
  function getAllCandidateData() {
    $.ajax({
      url: "../../src/app/Actions/HCandidateManagement.php",
      method: "GET",
      dataType: "html",
      data: { action: 'read', category: 'all'},
      success: (data) => {
        $('#tbodyCandidates').html(data);
      }
    }).done((response) => {
      console.log(response);
    }).fail((xhr, status, error) => {
      console.log(xhr, status, error);
    });
  }

  /**[Fetch ALl Candidate By branch] [NOT FINISH YET]**/ 
  function getAllCandidatesByBranch(branchname) {
    $.ajax({
      url: "../../src/app/Actions/HCandidateManagement.php",
      method: "GET",
      dataType: "html",
      data: { 
        action: 'read', 
        target: branchname,
        category: 'byBranchFilter'
      },
      success: (data) => {
        $('#candidateList_card').html(data);
      }
    });
  }

  /**[Fetch ALl Candidate By Category]**/
  function getCandidateDataByCategory(target) {
    $.ajax({
      url: "../../src/app/Actions/HCandidateManagement.php",
      method: "GET",
      dataType: "html",
      data: { action: 'read', target: target, category: 'byCategoryFilter'},
      success: (data) => {
        $('#tbodyCandidates').html(data);       
      }
    });
  }

  /**[Fetch ALl Candidate By Category and Branch/Campus]**/
  function getCandidateDataByCategoryBranch(firstQuery, secondQuery, purpose) {
    $.ajax({
      url: "../../src/app/Actions/HCandidateManagement.php",
      method: "GET",
      dataType: "html",
      data: { 
        action: 'read', 
        fcategory: firstQuery,
        scategory: secondQuery, 
        purpose: purpose, 
        category: 'byCategoryBranchFilter'
      },
      success: (data) => {
        //$('#tbodyCandidates').html(data);      
        (purpose === 'adminSide') ? $('#tbodyCandidates').html(data) : $('#candidateList_card').html(data); 
      }
    });
  }


  /**[Fetch Specific Data of Candidate]**/
  function getCandidateDataById(sid, mode, renderPurpose) {
    $.ajax({
      url: "../../src/app/Actions/HCandidateManagement.php",
      method: "GET",
      dataType: "html",
      data: { 
        action: 'read', 
        category: 'byId', 
        sid: sid, 
        mode: mode,
        purpose: renderPurpose 
      },
      success: (data) => {
        if (mode === 'view') {
          if(renderPurpose === 'clientSide') {
            $('#mbodyCandidatesClientView').html(data);
          } else {
            $('#mbodyCandidatesView').html(data);
          }
          
        } else if (mode === 'edit') {
          $('#mbodyCandidatesEdit').html(data);
        }
      }
    }).done((response) => {
      console.log(response);
    }).fail((xhr, status, error) => {
      console.log(xhr, status, error);
    });
  }


  function openModal(modalId) {
    $(modalId).attr('data-bs-backdrop', 'static').modal('show');
  }

  function closeModal(modalId) {
    $(modalId).modal('hide');
  }

  function uploadImage(input) {
    if(input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        //add form
        $('.image-upload-wrap').hide();
        $('.file-upload-image').attr('src', e.target.result);
        $('.file-upload-content').show();     
        $('.browse_file').hide();
        $('.image_upload_name').text(input.files[0].name);
        $('.remove_file').show();
        //edit form
        $('.file-upload-image-modal').attr('src', e.target.result);
        $('.browse_file_modal').hide();
        $('.image_upload_name_modal').text(input.files[0].name);
        $('.remove_file_modal').show();
      };
      reader.readAsDataURL(input.files[0]);
    }  
  }

  function removeImage(input) {
    // Reset form fields
    $('.image-upload-wrap').show();
    $('.file-upload-image').attr('src', '');
    $('.file-upload-image-modal').attr('src', '');
    $('.file-upload-content').hide();
    $('.browse_file').show();
    $('#image_upload').val('');
    $('.remove_file').hide();
  }

  function isFileSelected(inputFile) {
    return inputFile.length > 0;
  }

  function isEmpty(field) {
    return field === "";
  }