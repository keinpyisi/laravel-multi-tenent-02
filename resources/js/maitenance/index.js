$(function () {
  var langs = window.Lang
  var currentPage = ''
  var totalPages = ''
  var totalUsers = ''

  flatpickr('#maintenance_term-datepicker', {
    mode: 'range',
    enableTime: true,
    dateFormat: 'Y-m-d H:i:S',
    time_24hr: true,
    defaultDate: [
      $('#maintenance_term-datepicker').data('start'),
      $('#maintenance_term-datepicker').data('end'),
    ],
  })
  $(document).on('click', '.edit_tenant', function () {
    var tr = $(this).closest('tr')
    // Get the 'data-id' from the <tr> element
    var user_id = tr.data('id')
    axios
      .get('/api/backend/admin/maitenances/' + user_id)
      .then((response) => {
        var data = response.data.data
        var jsonData = response.data.data.data
        Swal.fire({
          icon: 'question',
          title: langs.ask_create.replace(':data', '各サイトのメンテナンス'),
          html: data.modal_html,
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
            var frontSiteChecked =
              jsonData?.front_site === 'frontend' ? true : false
            var backSiteChecked =
              jsonData?.back_site === 'backend' ? true : false
            var maintenanceMode = jsonData?.maintenance_0
            var maintenanceTermStart =
              jsonData?.maintenance_term?.maintanance_term_start || ''
            var maintenanceTermEnd =
              jsonData?.maintenance_term?.maintanance_term_end || ''
            var allowIp = jsonData?.allow_ip?.join('\n') // Join IPs with newline separator
            var frontMessage = jsonData?.front_main_message || ''
            var backMessage = jsonData?.back_main_message || ''
            console.log([
              frontSiteChecked,
              backSiteChecked,
              maintenanceMode,
              maintenanceTermStart,
              maintenanceTermEnd,
              allowIp,
              frontMessage,
              backMessage,
            ])
            // Collect form data from the modal
            // Set checkbox values based on boolean
            $('input[name="front_site_modal"]').prop(
              'checked',
              frontSiteChecked
            )
            $('input[name="back_site_modal"]').prop('checked', backSiteChecked)
            $(
              'input[name="maintenance_0_modal"][value="' +
                maintenanceMode +
                '"]'
            ).prop('checked', true)
            // Set the textarea values
            $('textarea[name="allow_ip_modal"]').val(allowIp)
            $('textarea[name="front_main_message_modal"]').val(frontMessage)
            $('textarea[name="back_main_message_modal"]').val(backMessage)

            // If you want to populate a label or another element:
            $('#maintenance_term_modal').text(
              maintenanceTermStart + ' to ' + maintenanceTermEnd
            )
            flatpickr('#maintenance_term_modal', {
              mode: 'range',
              enableTime: true,
              dateFormat: 'Y-m-d H:i:S',
              time_24hr: true,
              defaultDate: [
                $('#maintenance_term_modal').data('start'),
                $('#maintenance_term_modal').data('end'),
              ],
            })
            const confirmButton = Swal.getConfirmButton()
            if (confirmButton) {
              confirmButton.addEventListener('click', async () => {
                // Collect form data from the modal
                var frontSiteChecked = $(
                  'input[name="front_site_modal"]:checked'
                ).val()
                var backSiteChecked = $(
                  'input[name="back_site_modal"]:checked'
                ).val()
                var maintenanceMode = $(
                  'input[name="maintenance_0_modal"]:checked'
                ).val()
                var maintenanceTerm = $(
                  'input[name="maintenance_term_modal"]'
                ).val()
                var allowIp = $('textarea[name="allow_ip_modal"]').val()
                var frontMessage = $(
                  'textarea[name="front_main_message_modal"]'
                ).val()
                var backMessage = $(
                  'textarea[name="back_main_message_modal"]'
                ).val()

                // Create FormData object
                var formData = new FormData()
                // Add other form fields
                formData.append('front_site', frontSiteChecked)
                formData.append('back_site', backSiteChecked)
                formData.append('maintenance_0', maintenanceMode)

                formData.append('maintenance_term', maintenanceTerm)
                formData.append(`allow_ip`, allowIp)

                formData.append('front_main_message', frontMessage)
                formData.append('back_main_message', backMessage)
                formData.append('tenant', user_id)
                formData.append('_method', 'PUT')

                try {
                  const response = await axios.post(
                    `/api/backend/admin/maitenances/${user_id}/update`,
                    formData
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
                // AJAX form submission
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

  function addRow(account_name, status, mode_datetime) {
    // Create the table row
    var row = $(
      '<tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700"></tr>'
    )

    // Create and append the other table cells
    row
      .append(
        $('<td></td>')
          .addClass('p-3 text-sm text-blue-800 cursor-pointer edit_tenant')
          .text(account_name)
      )
      .attr('data-id', account_name) // Add data-id attribute to the td
    row.append(
      $('<td></td>')
        .addClass(
          'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'
        )
        .text(status)
    )
    row.append(
      $('<td></td>')
        .addClass(
          'px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white'
        )
        .text(mode_datetime)
    )
    // Append the newly created row to the table body
    $('.tenant_table').append(row)
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
    var api = '/api/backend/admin/maitenance' // Base API URL
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
        $('.tenant_table').empty()
        // Access the response data
        var usersData = response.data.data.data // This contains the array of user data
        // Loop through the users and log each one
        usersData.forEach((user) => {
          addRow(user.account_name, 'ON', '2022/22/22')
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
