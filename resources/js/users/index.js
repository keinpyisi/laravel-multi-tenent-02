$(function () {
  var langs = window.Lang
  var currentPage = ''
  var totalPages = ''
  var totalUsers = ''

  // Select All checkbox
  $('#selectAll').on('change', function () {
    $('tbody input[type="checkbox"]').prop('checked', this.checked)
  })

  // Individual checkbox change
  $('tbody input[type="checkbox"]').change(function () {
    var allChecked =
      $('tbody input[type="checkbox"]:checked').length ===
      $('tbody input[type="checkbox"]').length
    $('#selectAll').prop('checked', allChecked)
  })

  // Search functionality
  $('#searchInput').on('keyup', function () {
    var value = $(this).val().toLowerCase()
    // Call getData for the first page on page load
    getData(1, { data: value })
  })

  // Delete button click event
  $('#delBtn').on('click', function () {
    var selectedIds = []
    $('tbody input[type="checkbox"]:checked').each(function () {
      selectedIds.push($(this).closest('tr').find('td:eq(1)').text())
    })
    if (selectedIds.length > 0) {
      Swal.fire({
        icon: 'warning',
        title: langs.delete_question2,
        showCancelButton: true,
        confirmButtonText: langs.yes_delete,
        cancelButtonText: langs.no,
        customClass: {
          confirmButton:
            'bg-red-600 text-white hover:bg-red-700 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2',
          cancelButton:
            'bg-gray-300 text-black hover:bg-gray-400 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2',
        },
      }).then(async (result) => {
        if (result.isConfirmed) {
          var data = { ids: selectedIds, _method: 'DELETE' }
          try {
            const response = await axios.post('/api/backend/admin/users', data)
            console.log(response)
            if (response.data.type === 'error') {
              var errorMessages = response.data.data
              var errorMessage = errorMessages.join('<br>')

              Swal.showValidationMessage(errorMessage.trim())
              return // Keep the modal open if validation fails
            } else {
              Swal.close()
              Swal.fire({
                icon: 'success',
                title: langs.success_title,
                text: langs.success2.replace(':attribute', langs.delete),
                confirmButtonText: 'OK',
              }).then((result) => {
                if (result.isConfirmed) {
                  // Reload the page after the user clicks "OK"
                  window.location.reload()
                }
              })
            }
          } catch (error) {
            console.error(error)
            Swal.fire(
              'Error!',
              'There was an issue with your request.',
              'error'
            )
            return // Keep the modal open in case of request failure
          }
        }
      })
    } else {
      Swal.fire({
        icon: 'warning',
        title: langs.please_choose,
        showConfirmButton: true,
        confirmButtonText: langs.yes,
      })
    }
  })

  $('#addBtn').on('click', function () {
    Swal.fire({
      icon: 'question',
      title: langs.ask_create.replace(':data', 'ユーザー'),
      html:
        '<div class="mb-4 text-left">' +
        '<label for="loginId" class="block text-sm font-medium text-gray-700 mb-2">' +
        langs.login_id +
        '</label>' +
        '<input id="loginId" type="text" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
        langs.login_id +
        '" required>' +
        '</div>' +
        '<div class="mb-4 text-left">' +
        '<label for="password" class="block text-sm font-medium text-gray-700 mb-2">' +
        langs.password +
        '</label>' +
        '<input id="password" type="password" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
        langs.password +
        '" required>' +
        '</div>' +
        '<div class="mb-4 text-left">' +
        '<label for="userName" class="block text-sm font-medium text-gray-700 mb-2">' +
        langs.user_name +
        '</label>' +
        '<input id="userName" type="text" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
        langs.user_name +
        '" required>' +
        '</div>',
      focusConfirm: false,
      showCancelButton: true,
      confirmButtonText: langs.yes,
      cancelButtonText: langs.no,
      customClass: {
        input: 'my-swal-input',
        confirmButton: 'btn btn-primary custom-confirm-button',
        cancelButton: 'btn btn-secondary',
      },
      allowOutsideClick: false,
      allowEscapeKey: false,
      preConfirm: () => {
        // This callback will return false initially, preventing the modal from closing
        return false
      },
      didOpen: () => {
        const confirmButton = Swal.getConfirmButton() // Get the confirm button
        if (confirmButton) {
          confirmButton.addEventListener('click', async () => {
            var loginId = Swal.getPopup().querySelector('#loginId').value
            var password = Swal.getPopup().querySelector('#password').value
            var userName = Swal.getPopup().querySelector('#userName').value
            // Validate inputs
            if (!loginId || !password || !userName) {
              let errorMessage = ''

              if (!loginId) {
                errorMessage +=
                  langs.no_input.replace(':data', langs.login_id) + '<br>'
              }
              if (!password) {
                errorMessage +=
                  langs.no_input.replace(':data', langs.password) + '<br>'
              }
              if (!userName) {
                errorMessage +=
                  langs.no_input.replace(':data', langs.user_name) + '<br>'
              }
              // Show validation message
              Swal.showValidationMessage(errorMessage.trim())
              return // Keep the modal open if validation fails
            }
            // If validation passes, send the API request
            var data = {
              login_id: loginId,
              password: password,
              user_name: userName,
            }
            try {
              const response = await axios.post(
                '/api/backend/admin/users',
                data
              )

              if (response.data.type === 'error') {
                var errorMessages = response.data.data
                var errorMessage = errorMessages.join('<br>')

                Swal.showValidationMessage(errorMessage.trim())
                return // Keep the modal open if validation fails
              } else {
                Swal.close()
                Swal.fire({
                  icon: 'success',
                  title: langs.success_title,
                  text: langs.success.replace(':attribute', langs.account),
                  confirmButtonText: 'OK',
                }).then((result) => {
                  if (result.isConfirmed) {
                    // Reload the page after the user clicks "OK"
                    window.location.reload()
                  }
                })
              }
            } catch (error) {
              console.error(error)
              Swal.fire(
                'Error!',
                'There was an issue with your request.',
                'error'
              )
              return // Keep the modal open in case of request failure
            }
          })
        }
      },
    })
  })
  // Attach a click event handler to all elements with the 'clickable-row' class
  $(document).on('click', '.edit_admin', function () {
    var tr = $(this).closest('tr')
    // Get the 'data-id' from the <tr> element
    var user_id = tr.data('id')
    axios
      .get('/api/backend/admin/users/' + user_id)
      .then((response) => {
        var usersData = response.data.data
        Swal.fire({
          icon: 'question',
          title: langs.ask_create.replace(':data', 'ユーザー'),
          html:
            '<div class="mb-4 text-left">' +
            '<label for="loginId" class="block text-sm font-medium text-gray-700 mb-2">' +
            langs.login_id +
            '</label>' +
            '<input id="loginId" type="text" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
            langs.login_id +
            '"  value="' +
            usersData.login_id +
            '" required>' +
            '</div>' +
            '<div class="mb-4 text-left">' +
            '<label for="password" class="block text-sm font-medium text-gray-700 mb-2">' +
            langs.password +
            '</label>' +
            '<input id="password" type="password" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
            langs.password +
            '" required>' +
            '</div>' +
            '<div class="mb-4 text-left">' +
            '<label for="userName" class="block text-sm font-medium text-gray-700 mb-2">' +
            langs.user_name +
            '</label>' +
            '<input id="userName" type="text" class="swal2-input w-64 p-2 border border-gray-300 rounded-md" placeholder="' +
            langs.user_name +
            '" value="' +
            usersData.user_name +
            '" required>' +
            '</div>',
          focusConfirm: false,
          showCancelButton: true,
          confirmButtonText: langs.yes,
          cancelButtonText: langs.no,
          customClass: {
            input: 'my-swal-input',
            confirmButton: 'btn btn-primary custom-confirm-button',
            cancelButton: 'btn btn-secondary',
          },
          allowOutsideClick: false,
          allowEscapeKey: false,
          preConfirm: () => {
            // This callback will return false initially, preventing the modal from closing
            return false
          },
          didOpen: () => {
            const confirmButton = Swal.getConfirmButton() // Get the confirm button
            if (confirmButton) {
              confirmButton.addEventListener('click', async () => {
                var loginId = Swal.getPopup().querySelector('#loginId').value
                var password = Swal.getPopup().querySelector('#password').value
                var userName = Swal.getPopup().querySelector('#userName').value
                // Validate inputs
                if (!loginId || !password || !userName) {
                  let errorMessage = ''

                  if (!loginId) {
                    errorMessage +=
                      langs.no_input.replace(':data', langs.login_id) + '<br>'
                  }
                  if (!password) {
                    errorMessage +=
                      langs.no_input.replace(':data', langs.password) + '<br>'
                  }
                  if (!userName) {
                    errorMessage +=
                      langs.no_input.replace(':data', langs.user_name) + '<br>'
                  }
                  // Show validation message
                  Swal.showValidationMessage(errorMessage.trim())
                  return // Keep the modal open if validation fails
                }
                // If validation passes, send the API request
                var data = {
                  login_id: loginId,
                  password: password,
                  user_name: userName,
                  _method: 'PUT',
                }
                try {
                  const response = await axios.post(
                    `/api/backend/admin/users/${user_id}/update`,
                    data
                  )

                  if (response.data.type === 'error') {
                    var errorMessages = response.data.data
                    var errorMessage = errorMessages.join('<br>')

                    Swal.showValidationMessage(errorMessage.trim())
                    return // Keep the modal open if validation fails
                  } else {
                    Swal.close()
                    Swal.fire({
                      icon: 'success',
                      title: langs.success_title,
                      text: langs.success.replace(':attribute', langs.account),
                      confirmButtonText: 'OK',
                    }).then((result) => {
                      if (result.isConfirmed) {
                        // Reload the page after the user clicks "OK"
                        window.location.reload()
                      }
                    })
                  }
                } catch (error) {
                  console.error(error)
                  Swal.fire(
                    'Error!',
                    'There was an issue with your request.',
                    'error'
                  )
                  return // Keep the modal open in case of request failure
                }
              })
            }
          },
        })
      })
      .catch((error) => {
        console.error('Request failed', error)
        Swal.fire('Error!', 'There was an issue with your request.', 'error')
        return // Keep the modal open in case of request failure
      })
    // Perform any action you need with the clicked ID
  })

  function addRow(id, login_id, user_name, timestamp, edited_user) {
    // Create the table row with hover effect
    const row = $(`
        <tr class="bg-white hover:bg-gray-50 transition-colors duration-200 border-b border-gray-200"></tr>
    `);

    // Checkbox cell
    const checkboxCell = $('<td class="px-4 py-3"></td>').append(
        $('<input>')
            .attr('type', 'checkbox')
            .addClass(`
                w-4 h-4
                rounded
                border-gray-300
                text-blue-600
                focus:ring-2
                focus:ring-blue-500
                focus:ring-offset-2
                transition-colors
                duration-200
                cursor-pointer
            `)
    );
    row.append(checkboxCell);

    // ID cell
    row.append(
        $('<td></td>')
            .addClass('px-6 py-3 text-sm font-medium text-gray-900')
            .text(id)
    );

    // Login ID cell (editable)
    row.append(
        $('<td></td>')
            .addClass(`
                px-6 py-3
                text-sm
                text-blue-600
                hover:text-blue-700
                cursor-pointer
                edit_admin
                font-medium
            `)
            .text(login_id)
            .attr('data-id', id)
    );

    // User Name cell
    row.append(
        $('<td></td>')
            .addClass('px-6 py-3 text-sm text-gray-700')
            .text(user_name)
    );

    // Timestamp cell
    row.append(
        $('<td></td>')
            .addClass('px-6 py-3 text-sm text-gray-600')
            .text(timestamp)
    );

    // Edited User cell
    row.append(
        $('<td></td>')
            .addClass('px-6 py-3 text-sm text-gray-600')
            .text(edited_user)
    );

    // Append the row to the table
    $('.admin_table').append(row);

    // Add click event for the entire row
    row.on('click', function(e) {
        // Don't trigger if clicking checkbox or editable cell
        if (!$(e.target).is('input[type="checkbox"], .edit_admin')) {
            const checkbox = $(this).find('input[type="checkbox"]');
            checkbox.prop('checked', !checkbox.prop('checked'));
        }
    });
}

  function getData(page, data) {
    Swal.fire({
      title: langs.loading,
      showConfirmButton: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading()
      },
    })
    var api = '/api/backend/admin/user' // Base API URL
    // Initialize an array to hold query string parts
    var queryParams = []
    // Append page number if provided
    if (page) {
      queryParams.push('page=' + page)
    }
    // Append other data parameters if provided
    if (data && typeof data === 'object') {
      for (var key in data) {
        if (data.hasOwnProperty(key)) {
          queryParams.push(
            encodeURIComponent(key) + '=' + encodeURIComponent(data[key])
          )
        }
      }
    }
    // If there are query parameters, join them with '&' and append to the URL
    if (queryParams.length > 0) {
      api += '?' + queryParams.join('&')
    }
    axios
      .get(api)
      .then((response) => {
        $('.admin_table').empty()
        // Access the response data
        var usersData = response.data.data.data // This contains the array of user data
        // Loop through the users and log each one
        usersData.forEach((user) => {
          addRow(
            user.id,
            user.login_id,
            user.user_name,
            user.updated_at,
            user?.updated_by?.user_name
          )
        })
        // Pagination details
        currentPage = response.data.data.current_page
        totalPages = response.data.data.last_page
        totalUsers = response.data.data.total
        // Render the pagination controls
        renderPaginationControls(currentPage, totalPages)
        //Close Loading
        Swal.close()
      })
      .catch((error) => {
        console.error('Request failed', error)
        Swal.fire('Error!', 'There was an issue with your request.', 'error')
        return // Keep the modal open in case of request failure
      })
  }
  // Function to render pagination controls dynamically
  function renderPaginationControls(currentPage, totalPages) {
    let paginationHTML = ''

    // Previous button
    if (currentPage > 1) {
      paginationHTML += `<button class="px-3 py-1 bg-gray-700 text-white rounded" data-page="${currentPage - 1}">Previous</button>`
    }

    // Page numbers
    if (totalPages > 1) {
      for (let page = 1; page <= totalPages; page++) {
        paginationHTML += `<button class="px-3 py-1 ${currentPage === page ? 'bg-blue-500' : 'bg-gray-700'} text-white rounded" data-page="${page}">${page}</button>`
      }
    }
    // Next button
    if (currentPage < totalPages) {
      paginationHTML += `<button class="px-3 py-1 bg-gray-700 text-white rounded" data-page="${currentPage + 1}">Next</button>`
    }

    // Append pagination controls to the container
    $('#pagination-controls').html(paginationHTML)
  }

  // Event delegation to handle pagination button clicks
  $('#pagination-controls').on('click', 'button', function () {
    const page = $(this).data('page')
    if (page !== currentPage) {
      currentPage = page
      getData(page) // Fetch the new page data
    }
  })

  // Call getData for the first page on page load
  getData()
})
