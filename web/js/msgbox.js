$(document).on('click', '.delete-btn', function() {
    var id = $(this).attr('id');
   if ($(this).hasClass('cant-delete')) {
       swal({
          type: 'error',
          title: 'Oops...',
          text: 'Someone already answered this survey, you cannot delete it.'
        });
   } else {
        swal({
          title: 'Are you sure?',
          text: "You won't be able to revert this!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Yes, delete it!',
          cancelButtonText: 'No, cancel!',
          reverseButtons: true
        }).then((result) => {
          if (result) {
              $.ajax({
                type: 'POST',
                url: '/survey/delete/' + id,
                success: function (url) {
                    if (url) {
                        window.location = url;
                    }
                },
            });
          }
        });
   }
});
