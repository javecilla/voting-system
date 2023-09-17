jQuery(document).ready(function() {
  trackCurrentURI();
  getAllVoteRecords();
  getTotalPendingVotes();

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
                getAllVoteRecords();
                getTotalPendingVotes(); 
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
                          getAllVoteRecords();
                          getTotalPendingVotes();
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

  /*[To search filter the votes records]*/
  $('#searchInput').on('keyup', (e) => {
    const searchQuery = $(e.currentTarget).val();
    (isEmpty(searchQuery)) ? getAllVoteRecords() : getAllVoteRecordsBySearch(searchQuery);
  });
});  