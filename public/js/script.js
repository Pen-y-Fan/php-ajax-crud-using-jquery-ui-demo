(function ($) {


  function load_data() {
    $.get({
      url: "api/index.php",
      success: function (data, status) {
        var output = "";
        if (status === 'success') {
          // var results = data;
          if (data.length > 0 && data !== false) {
            data.forEach(function (row) {
              output += `
          <tr>
            <td>${row['first_name']}</td>
            <td>${row['last_name']}</td>
            <td>
              <button
                type="button"
                name="edit"
                class="btn btn-primary btn-xs edit"
                id="${row['id']} ">Edit</button>
            </td>
            <td>
              <button
                type="button"
                name="delete"
                class="btn btn-danger btn-xs delete"
                id=" ${row['id']}">Delete</button>
            </td>
          </tr>
        `
            });
          } else {
            output += `
        <tr>
          <td colspan="4" class="text-center">Data not found</td>
        </tr>
        `;
          }
        } else {
          output += `
        <tr>
          <td colspan="4" class="text-center">Something went wrong, status ${status}</td>
        </tr>
        `;
        }
        $('tbody').html(output);
      },
      dataType: "json",
    });
  }

  $("#user_dialog").dialog({
    autoOpen: false,
    width: 400
  });

  $('#add').on('click', function () {
    $('#action').val('insert');
    $('#user_form')[0].reset();

    var $formAction = $('#form_action');
    $formAction.val('Insert');
    $formAction.attr('disabled', false);

    $('#first_name').css('border-color', '');
    $('#last_name').css('border-color', '');
    $('#error_first_name').text('');
    $('#error_last_name').text('');

    var $userDialog = $('#user_dialog');
    $userDialog.dialog('option', 'title', 'Add Data');
    $userDialog.dialog('open');
  });

  $('#user_form').on('submit', function (event) {
    event.preventDefault();
    var error_first_name;
    var error_last_name;
    var $firstName = $('#first_name')
    if ($firstName.val() === '') {
      error_first_name = 'First Name is required';
      $('#error_first_name').text(error_first_name);
      $firstName.css('border-color', '#cc0000');
    } else {
      error_first_name = '';
      $('#error_first_name').text(error_first_name);
      $firstName.css('border-color', '');
    }
    var $lastName = $('#last_name')
    if ($lastName.val() === '') {
      error_last_name = 'Last Name is required';
      $('#error_last_name').text(error_last_name);
      $lastName.css('border-color', '#cc0000');
    } else {
      error_last_name = '';
      $('#error_last_name').text(error_last_name);
      $lastName.css('border-color', '');
    }

    if (error_first_name !== '' || error_last_name !== '') {
      return false;
    } else {
      $('#form_action').prop("disabled", true);
      var form_data = $(this).serialize();
      var action = $('#action').val();
      var api = action === "update" ? "/api/update/index.php" : "/api/store/index.php";
      $.ajax({
        url: api,
        method: "POST",
        data: form_data,
        success: function (data) {
          $('#user_dialog').dialog('close');
          var $actionAlert = $('#action_alert')
          $actionAlert.html(data);
          $actionAlert.dialog('open');
          load_data();
          $('#form_action').prop('disabled', false);
        },
        error: function (request, textStatus, errorThrown) {
          displayError(request, textStatus, errorThrown);
        },
      });
    }
  });

  $('#action_alert').dialog({
    autoOpen: false
  });

  $(document).on('click', '.edit', function () {
    var id = $(this).attr('id');
    $.ajax({
      url: "/api/show/index.php",
      method: "GET",
      data: {id: id},
      dataType: "json",
      success: function (data) {

        var $firstName = $('#first_name');
        $firstName.val(data[0].first_name);
        $firstName.css('border-color', '');
        $('#error_first_name').text('');

        var $lastName = $('#last_name');
        $lastName.val(data[0].last_name);
        $lastName.css('border-color', '');
        $('#error_last_name').text('');

        $('#action').val('update');
        $('#hidden_id').val(id);
        $('#form_action').val('Update');

        var $userDialog = $('#user_dialog');
        $userDialog.dialog('option', 'title', 'Edit Data');
        $userDialog.dialog('open');
      },
      error: function (request, textStatus, errorThrown) {
        displayError(request, textStatus, errorThrown);
      },
    });
  });

  function displayError(request, textStatus, errorThrown) {
    console.log(request, textStatus, errorThrown);
    var error = JSON.parse(request.responseText)
    var $actionAlert = $('#action_alert')
    $actionAlert.html(error.error);
    $actionAlert.dialog('open');
    load_data();
  }

  $('#delete_confirmation').dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      "Yes": function () {
        var id = $(this).data('id');
        var action = 'delete';
        $.ajax({
          url: "/api/delete/index.php",
          method: "POST",
          data: {id: id, action: action},
          success: function (data) {
            $('#delete_confirmation').dialog('close');
            var $actionAlert = $('#action_alert')
            $actionAlert.html(data);
            $actionAlert.dialog('open');
            load_data();
          },
          error: function (request, textStatus, errorThrown) {
            displayError(request, textStatus, errorThrown);
          },
        });
      },
      "Cancel": function () {
        $(this).dialog('close');
      }
    }
  });

  $(document).on('click', '.delete', function () {
    var id = $(this).attr("id");
    $('#delete_confirmation').data('id', id).dialog('open');
  });

  load_data();

})(jQuery);
