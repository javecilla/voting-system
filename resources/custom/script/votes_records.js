jQuery(document).ready(function() {
  const STA_MARIA = "Golden Minds Colleges - Sta.Maria";
  const BALAGTAS = "Golden Minds Colleges - Balagtas";
  trackCurrentURI();
  getAllVoteRecords();
  getTotalPendingVotes(STA_MARIA);
  getTotalPendingVotes(BALAGTAS);

  /*[To fetch vote status by branch]*/
  $(document).on('click', '.filter-item', (e) => {
    e.preventDefault();
    const branch = $(e.currentTarget).data('value');
    $('.filter-item').removeClass('filterBtnActive').addClass('filterBtnNotActive');
    $(e.currentTarget).removeClass('filterBtnNotActive').addClass('filterBtnActive');

    if(branch === "All") {
      getAllVoteRecords();
      $('#filterActiveValue').val('');
    } else {
      getAllVoteRecordsByBranch(branch);
      $('#filterActiveValue').val(branch);
    }

    getTotalPendingVotes(STA_MARIA);
    getTotalPendingVotes(BALAGTAS);
  });

  /*[To search filter the votes records]*/
  $('#searchInput').on('keyup', (e) => {
    const searchQuery = $(e.currentTarget).val();
    let branchActive = $('#filterActiveValue').val();

    (isEmpty(searchQuery)) ? getAllVoteRecords() : getAllVoteRecordsBySearch(searchQuery, branchActive);
  });

  /*[To update vote status]*/
  $(document).on('click', '#removeVote', (e) => {
    e.preventDefault();
    const filterActiveValue = $('#filterActiveValue').val();
    Swal.fire({
      title: "Are you sure to delete this?",
      text: "After deleting this you will not be able to recover this vote records.",
      icon: "info",
      showConfirmButton: true,
      confirmButtonText: "Okay",
      confirmButtonColor: "#5f76e8",
      showCancelButton: true,
      cancelButtonText: "Cancel",
      showLoaderOnConfirm: true,
      allowEscapeKey : false,
      allowOutsideClick: false,
      preConfirm: (response) => { 
        if(!response) {
          return false;
        } else {
          return new Promise(function(resolve) { 
            setTimeout(function () { 
              const formData = new FormData();
              formData.append('vid', $(e.currentTarget).closest('tr').find('button[data-id]').data('id'));
              formData.append('action', 'delete');
              $.ajax({
                url: "../../src/app/Actions/HClientVotes.php",
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
                          $('#stamaria').removeClass('filterBtnActive').addClass('filterBtnNotActive');
                          $('#balagtas').removeClass('filterBtnActive').addClass('filterBtnNotActive');

                          if(isEmpty(filterActiveValue)) {
                            getTotalPendingVotes(STA_MARIA);
                            getTotalPendingVotes(BALAGTAS);
                            getAllVoteRecords();
                          } else if (filterActiveValue === STA_MARIA) {
                            $('#stamaria').removeClass('filterBtnNotActive').addClass('filterBtnActive');
                          } else {
                            $('#balagtas').removeClass('filterBtnNotActive').addClass('filterBtnActive');
                          }

                          getTotalPendingVotes(STA_MARIA);
                          getTotalPendingVotes(BALAGTAS);
                          getAllVoteRecordsByBranch(filterActiveValue);
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
                error(xhr, status, error) {
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
            }, 1000);
          });
        }
      },
      allowOutsideClick: () => !Swal.isLoading()
    });
  });

  /*[To update vote status]*/
  $(document).on('click', '#updateVoteStatus', (e) => {
    e.preventDefault();
    const filterActiveValue = $('#filterActiveValue').val();
    const formData = new FormData();
    formData.append('vid', $(e.currentTarget).closest('tr').find('button[data-id]').data('id'));
    formData.append('vstatus', $(e.currentTarget).closest('tr').find('button[data-value]').data('value'));
    formData.append('action', 'update');

    $.ajax({
      url: "../../src/app/Actions/HClientVotes.php",
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
                if(isEmpty(filterActiveValue)) {
                  getTotalPendingVotes(STA_MARIA);
                  getTotalPendingVotes(BALAGTAS);
                  getAllVoteRecords();
                } else {
                  getTotalPendingVotes(STA_MARIA);
                  getTotalPendingVotes(BALAGTAS);
                  getAllVoteRecordsByBranch(filterActiveValue);
                  $('.filter-item').removeClass('filterBtnActive').addClass('filterBtnNotActive');
                  $(`[data-value="${filterActiveValue}"]`).removeClass('filterBtnNotActive').addClass('filterBtnActive');
                }

                getTotalPendingVotes(STA_MARIA);
                getTotalPendingVotes(BALAGTAS);  
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
      error(xhr, status, error) {
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

});  