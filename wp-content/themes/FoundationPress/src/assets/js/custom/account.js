$(document).ready(function () {
    $(".btn-update-account").click(function () {
        var dataFields = {
            'action': 'update_account',
            'security': jQuery('#account-nonce').val()
        };

        $.each($('form').serializeArray(), function(_, kv) {
          if (dataFields.hasOwnProperty(kv.name)) {
            dataFields[kv.name] = $.makeArray(dataFields[kv.name]);
            dataFields[kv.name].push(kv.value);
          }
          else {
            dataFields[kv.name] = kv.value;
          }
        });

        jQuery.post(ajaxurl, dataFields, function(orderId) {
            console.log('Got this from the server: ', orderId);
        }, 'json');
        return false;
    });
});