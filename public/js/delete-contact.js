$(document).ready(function() {
    // DELETE CONTACT (event handler solution)
    $('#contacts').on( "click", ".form-delete", function( e ) {
        e.preventDefault();
        var result = confirm("Are you sure that you want to delete this contact?");
        if (result) {
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "DELETE",
                url: $(this).attr('action'),
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    console.log(data);
                    window.location.href = APP_URL + 'api/contacts';
                },
                error: function (data) {
                    console.log('An error occurred.');
                    console.log(data);
                }
            });
        }
    });
});
