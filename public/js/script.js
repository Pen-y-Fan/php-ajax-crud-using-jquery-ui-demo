(function ($) {

  load_data();

  function load_data() {
    $.ajax({
      url: "api/index.php",
      method: "GET",
      success: function (data) {
        $('#user_data').html(data);
      }
    });
  }

  $("#user_dialog").dialog({
    autoOpen: false,
    width: 400
  });

  $('#add').click(function () {
    $('#user_dialog').attr('title', 'Add Data');
    $('#action').val('insert');
    $('#form_action').val('Insert');
    $('#user_form')[0].reset();
    $('#form_action').attr('disabled', false);
    $("#user_dialog").dialog('open');
  });

  $('#user_form').on('submit', function (event) {
    event.preventDefault();
    var error_first_name = '';
    var error_last_name = '';
    if ($('#first_name').val() == '') {
      error_first_name = 'First Name is required';
      $('#error_first_name').text(error_first_name);
      $('#first_name').css('border-color', '#cc0000');
    } else {
      error_first_name = '';
      $('#error_first_name').text(error_first_name);
      $('#first_name').css('border-color', '');
    }
    if ($('#last_name').val() == '') {
      error_last_name = 'Last Name is required';
      $('#error_last_name').text(error_last_name);
      $('#last_name').css('border-color', '#cc0000');
    } else {
      error_last_name = '';
      $('#error_last_name').text(error_last_name);
      $('#last_name').css('border-color', '');
    }

    if (error_first_name != '' || error_last_name != '') {
      return false;
    } else {
      $('#form_action').prop( "disabled", true );
      var form_data = $(this).serialize();
      var action = $('#action').val();
      var api = action == "update" ? "/api/update/index.php" : "/api/store/index.php";
      $.ajax({
        url: api,
        method: "POST",
        data: form_data,
        success: function (data) {
          $('#user_dialog').dialog('close');
          $('#action_alert').html(data);
          $('#action_alert').dialog('open');
          load_data();
          $('#form_action').prop('disabled', false);
        }
      });
    }

  });

  $('#action_alert').dialog({
    autoOpen: false
  });

  $(document).on('click', '.edit', function () {
    var id = $(this).attr('id');
    var action = 'fetch_single';
    $.ajax({
      url: "/api/show/index.php",
      method: "GET",
      data: {id: id},
      dataType: "json",
      success: function (data) {
        $('#first_name').val(data[0].first_name);
        $('#last_name').val(data[0].last_name);
        $('#user_dialog').attr('title', 'Edit Data');
        $('#action').val('update');
        $('#hidden_id').val(id);
        $('#form_action').val('Update');
        $('#user_dialog').dialog('open');
      }
    });
  });

  $('#delete_confirmation').dialog({
    autoOpen: false,
    modal: true,
    buttons: {
      Ok: function () {
        var id = $(this).data('id');
        var action = 'delete';
        $.ajax({
          url: "/api/delete/index.php",
          method: "POST",
          data: {id: id, action: action},
          success: function (data) {
            $('#delete_confirmation').dialog('close');
            $('#action_alert').html(data);
            $('#action_alert').dialog('open');
            load_data();
          }
        });
      },
      Cancel: function () {
        $(this).dialog('close');
      }
    }
  });

  $(document).on('click', '.delete', function () {
    var id = $(this).attr("id");
    $('#delete_confirmation').data('id', id).dialog('open');
  });

})(jQuery);
