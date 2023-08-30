    $(document).ready(function() {
      var renderPurpose = 'adminSide';
    //call needed function 
    trackCurrentURI();
    getAllCandidateData();

    /**[Filter ALl]**/
    $('.filterAll').on('click', () => {
      getAllCandidateData();
      $('#searchInput').val('');
      $('#formFilter')[0].reset();
      $('#filterBranch').attr('disabled', true);
    });

    /**[Filter Candidates by Branch]**/
    $('#filterCategory').on('change', (e) => {
      e.preventDefault();
      const selectedCategory = $(e.currentTarget).val();
      let selectedBranch = $('#filterBranch').val();
      
      if(isEmpty(selectedCategory)) {
        getAllCandidateData();
        $('#filterBranch').attr('disabled', true);
      }  

      if(!isEmpty(selectedBranch)) {
        getCandidateDataByCategoryBranch(selectedCategory, selectedBranch, renderPurpose);
      } else {
        $('.counter_main_text').text(selectedCategory);
        $('#filterBranch').attr('disabled', false);
        getCandidateDataByCategory(selectedCategory);
      } 
    });

    /**[Filter Candidates by Branch]**/
    $('#filterBranch').on('change', (e) => {
      e.preventDefault();    
      const selectedBranch = $(e.currentTarget).val();
      let selectedCategory = $('#filterCategory').val();
      isEmpty(selectedBranch) ? getCandidateDataByCategory(selectedCategory) : getCandidateDataByCategoryBranch(selectedCategory, selectedBranch, renderPurpose);
    });

    /**[Search Candidate]**/
    $('#searchInput').on('keyup', (e) => {
      let searchQuery = $(e.currentTarget).val();
      if(isEmpty(searchQuery)) {
        //back the original data
        getAllCandidateData();
        $('#formFilter')[0].reset();
      } else {
        $.ajax({
          url: "../../src/app/Actions/HCandidateManagement.php",
          method: "GET",
          dataType: "html",
          data: { searchQuery:searchQuery, action: 'read', category: 'searchFilter' },
          success: (data) => {
            $('#tbodyCandidates').html(data);
          }
        }).done((response) => {
          console.log(response);
        }).fail((xhr, status, error) => {
          console.log(xhr, status, error);
        });;
      }
    });

    /**[Add New Candidate]**/
    $('#addNewCandidate').on('click', (e) => {
      //alert("test");
      let imageUpload = $('#image_upload')[0].files;
      let category = $('#selectCategory').val();
      let schoolBranch = $('#selectBranch').val();
      let candidateName = $('#inputCandidateName').val();
      let candidateNo = $('#inputCandidateNo').val();

      if(isEmpty(category) || isEmpty(schoolBranch) || isEmpty(candidateName) || isEmpty(candidateNo)) {
        Swal.fire({
          title: '',
          text: 'All fields is required!',
          icon: 'info',
          confirmButtonText: 'Okay'
        });
      }  else if(!isFileSelected(imageUpload)) {           
        Swal.fire({
          title: '',
          text: 'Image/file is required!',
          icon: 'info',
          confirmButtonText: 'Okay'
        });
      } else {
        const formData = new FormData();
        formData.append('imageUpload', imageUpload[0]);
        formData.append('selectedCategory', category);
        formData.append('selectedBranch', schoolBranch);
        formData.append('candidateName', candidateName);
        formData.append('candidateNo', candidateNo);
        formData.append('action', 'create');
        $.ajax({
          url: "../../src/app/Actions/HCandidateManagement.php",
          method: "POST",
          data: formData,
          dataType: "JSON",
          contentType: false,
          processData: false,
          success: (response) => {
            try {
              const serverResponse = JSON.parse(JSON.stringify(response));
              if(serverResponse.success) {
                Swal.fire({
                  title: '',
                  html: `<h5>${serverResponse.message}</h5>`,
                  icon: 'success',
                  confirmButtonText: 'Okay'
                }).then((result) => {
                  if(result.isConfirmed) {
                    $('#addDataForm')[0].reset();
                    $('#formFilter')[0].reset();
                    $('#filterBranch').attr('disabled', true);
                    removeImage('#image_upload'); 
                    closeModal('#addNewCandidateModal');
                    getAllCandidateData();
                  }
                });
              } else {
                Swal.fire({
                  title: '',
                  html: `<h5>${serverResponse.message}</h5>`,
                  icon: 'error',
                  focusConfirm: false,
                  confirmButtonColor: '#880808',
                  confirmButtonText: 'Okay'
                }).then((result) => { return result.isConfirmed ? false : undefined; });
              }
            } catch(error) {
              Swal.fire({
                title: '',
                html: `<h5>${error}</h5>`,
                icon: 'error',
                focusConfirm: false,
                confirmButtonColor: '#880808',
                confirmButtonText: 'Okay'
              }).then((result) => { return result.isConfirmed ? false : undefined; });
            }
          },
          error: (xhr, status, error) => {
            Swal.fire({
              title: '',
              html: `<h5>${xhr.responseText}<br/>${error.responseText}</h5>`,
              icon: 'error',
              focusConfirm: false,
              confirmButtonColor: '#880808',
              confirmButtonText: 'Okay'
            }).then((result) => { return result.isConfirmed ? false : undefined; });
          }
        }); 
      }
    });

    /**[View Data Candidate in Modal]**/
    $('#tbodyCandidates').on('click', '.viewCandidate, .editCandidate', (e) => {
      e.preventDefault();
      const action = $(e.currentTarget).data('action');
      const sid = $(e.currentTarget).closest('td').find('.sid').val();
      const cid = $(e.currentTarget).closest('td').find('.cid').val();
      if(action === 'view') {
        openModal('#viewDataCandidateModal');
        $('.candidatesid').text(sid);
        getCandidateDataById(sid, 'view', renderPurpose);
      } else if (action === 'edit') {
        openModal('#editDataCandidateModal');
        $('.candidatesid').text(sid);
        getCandidateDataById(sid, 'edit', renderPurpose);
      }
    });

    /**[Update Data Candidate]**/
    $('#editDataCandidate').on('click', (e) => {
      e.preventDefault();
      //alert("test");
      let imageUploadModal = $('#image_upload_modal')[0].files;
      const formData = new FormData();
      formData.append('candidateSID', $('#csidModal').val());
      formData.append('imageUploadModal', imageUploadModal[0]);
      formData.append('candidateNameModal', $('#candidateNameModal').val());
      formData.append('candidateNoModal', $('#candidateNoModal').val());
      formData.append('selectedCategoryModal', $('#selectCategoryModal').val());
      formData.append('selectedBranchModal', $('#selectBranchModal').val());
      formData.append('action', 'update');
      $.ajax({
        url: "../../src/app/Actions/HCandidateManagement.php",
        method: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: (response) => {
          try {
            const serverResponse = JSON.parse(JSON.stringify(response));
            if(serverResponse.success) {
              Swal.fire({
                title: '',
                html: `<h5>${serverResponse.message}</h5>`,
                icon: 'success',
                confirmButtonText: 'Okay'
              }).then((result) => {
                if(result.isConfirmed) {
                  getAllCandidateData();
                  $('#formFilter')[0].reset();
                  $('#filterBranch').attr('disabled', true);
                  closeModal('#editDataCandidateModal');
                }
              });
            } else {
              Swal.fire({
                title: '',
                html: `<h5>${serverResponse.message}</h5>`,
                icon: 'error',
                focusConfirm: false,
                confirmButtonColor: '#880808',
                confirmButtonText: 'Okay'
              }).then((result) => { return result.isConfirmed ? false : undefined; });
            }
          } catch(error) {
            Swal.fire({
              title: '',
              html: `<h5>${error}</h5>`,
              icon: 'error',
              focusConfirm: false,
              confirmButtonColor: '#880808',
              confirmButtonText: 'Okay'
            }).then((result) => { return result.isConfirmed ? false : undefined; });
          }
        },
        error: (xhr, status, error) => {
          Swal.fire({
            title: '',
            html: `<h5>${xhr.responseText}<br/>${error.responseText}</h5>`,
            icon: 'error',
            focusConfirm: false,
            confirmButtonColor: '#880808',
            confirmButtonText: 'Okay'
          }).then((result) => { return result.isConfirmed ? false : undefined; });
        }
      }); 
    });

    /**[Delete Candidate Records]**/
    $('#tbodyCandidates').on('click', '#deleteDataCandidate', (e) => {
      e.preventDefault();
      const dataForm = {
        'sid': $(e.currentTarget).closest('td').find('.sid').val(),
        'action': 'delete'
      };
      // console.table(dataForm);
      Swal.fire({
        title: '',
        html: '<h4>Are you sure to delete this record?</h4><small>(After deleting this you will not able to recover this record)</small>',
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel'
      }).then((userResponse) => {
        if(userResponse.isConfirmed) {
          $.ajax({
            url: "../../src/app/Actions/HCandidateManagement.php",
            method: "POST",
            data: dataForm,
            success: (result) => {
              const serverResponse = JSON.parse(JSON.stringify(result));
              try {
                if(serverResponse.success) {
                  Swal.fire({
                    title: '',
                    html: `<h5>${serverResponse.message}</h5>`,
                    icon: 'success',
                    confirmButtonText: 'Okay'
                  }).then((result) => {
                    if(result.isConfirmed) {
                      getAllCandidateData();
                    }
                  });
                } else {
                   Swal.fire({
                    title: '',
                    html: `<h5>${serverResponse.message}</h5>`,
                    icon: 'error',
                    focusConfirm: false,
                    confirmButtonColor: '#880808',
                    confirmButtonText: 'Okay'
                  }).then((result) => { return result.isConfirmed ? false : undefined; });
                }
              } catch(error) {
                Swal.fire({
                  title: '',
                  html: `<h5>${error}</h5>`,
                  icon: 'error',
                  focusConfirm: false,
                  confirmButtonColor: '#880808',
                  confirmButtonText: 'Okay'
                }).then((result) => { return result.isConfirmed ? false : undefined; });
              }
            },
            error: (xhr, status, error) => {
                Swal.fire({
                title: '',
                html: `<h5>${xhr.responseText}<br/>${error.responseText}</h5>`,
                icon: 'error',
                focusConfirm: false,
                confirmButtonColor: '#880808',
                confirmButtonText: 'Okay'
              }).then((result) => { return result.isConfirmed ? false : undefined; });
            }
          });
        } else {
          return false;
        }
      });
    });
  });



