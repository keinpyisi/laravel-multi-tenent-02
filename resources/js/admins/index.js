$(function () {
  var langs = window.Lang
  $('.delete_btn').on('click', function (e) {
    e.preventDefault() // Prevent the form from submitting immediately

    // Perform an action when the button is clicked
    Swal.fire({
      title: langs.confirmed,
      text: langs.delete_question,
      icon: 'warning',
      showCancelButton: true, // Show cancel button for confirmation
      confirmButtonText: langs.yes,
      cancelButtonText: langs.no,
    }).then((result) => {
      if (result.isConfirmed) {
        // If the user clicks 'Yes', submit the form
        $(this).closest('form').submit() // Submit the form manually
      }
    })
  })
})
