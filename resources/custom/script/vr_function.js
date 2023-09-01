  /**[Fetch ALl Votes Records]**/
  function getAllVoteRecords() {
    $.ajax({
      url: "../../src/app/Actions/HClientVotes.php",
      method: "GET",
      dataType: "html",
      data: { action: 'read', task: 'allRecords'},
      success: (data) => {
        $('#tbodyVotesRecords').html(data);
      }
    }).done((response) => {
      console.log(response);
    }).fail((xhr, status, error) => {
      console.log(xhr, status, error);
    });
  }