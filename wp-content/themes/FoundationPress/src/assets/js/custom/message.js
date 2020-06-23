$(document).ready(function () {
    if($('body.page-template-message-center').length > 0){
        
        var $creativeupload = $('#creativeupload');
        $("#upload").click(function () {
            $creativeupload.click();
        });

        $creativeupload.change(function (event) {
            if ($creativeupload.val() === ""){
                return false;
            }

            var files = $creativeupload.get(0).files;
            var formdata = new FormData();
            formdata.append("action", "media_upload");
            formdata.append("type", jQuery('#review-upload').val());
            formdata.append("security", jQuery('#media-nonce').val());
            formdata.append("order-id", jQuery('#order-id').val());
            for (var i = 0; i < files.length; i++) {
                formdata.append("creative", files[i]);
            }
            $(".fileinfo").html('Uploading file ...');
            $("#fileguid").val("");
            $("#filename").val("");
            $creativeupload.val("");

            // for(var pair of formdata.entries()) {
            //    console.log('formdata', pair[0]+ ', '+ pair[1]); 
            // }

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('media success', data);
                    if(typeof data.data['order-status'] !== "undefined"){
                        // $('.order-status').html('[' + data.data['order-status'] + ']');
                        window.location.reload();
                    }
                    $(".fileinfo").html('Finished Upload. Page will refresh automatically.');
                    $("#fileguid").val(data.data.uploaded_file.file);
                    $("#filename").val(data.data.uploaded_file.url);
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log('upload errors', textStatus);
                }
            });

        });

        $("#approveCreative").on("click", function (e) {
            e.preventDefault();

            var formdata = new FormData();
            formdata.append("action", "change_status");
            formdata.append("type", 'approve');
            formdata.append("security", jQuery('#approve-nonce').val());
            formdata.append("order-id", jQuery('#order-id').val());
            formdata.append("comment", jQuery('#approveComment').val());

            // console.log('Form values'); 
            // for(var pair of formdata.entries()) {
            //    console.log(pair[0]+ ', '+ pair[1]); 
            // }
            // return;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('approve success', data);
                    if(data.status !== 'error'){
                        window.location.reload();
                    }
                }
            });
        });

        $("#conditionalCreative").on("click", function (e) {
            e.preventDefault();

            var formdata = new FormData();
            formdata.append("action", "change_status");
            formdata.append("type", 'conditional');
            if(jQuery('#impressions').length > 0){
                formdata.append("impressions", jQuery('#impressions').val());
            }
            if(jQuery('#cpm').length > 0){
                formdata.append("cpm", jQuery('#cpm').val());
            }
            formdata.append("comment", jQuery('#conditionalComment').val());
            formdata.append("security", jQuery('#conditional-nonce').val());
            formdata.append("order-id", jQuery('#order-id').val());

            // console.log('Form values'); 
            // for(var pair of formdata.entries()) {
            //    console.log(pair[0]+ ', '+ pair[1]); 
            // }
            // return;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('approve success', data);
                    if(data.status !== 'error'){
                        window.location.reload();
                    }
                }
            });
        });

        $("#denyCreative").on("click", function (e) {
            e.preventDefault();

            var formdata = new FormData();
            formdata.append("action", "change_status");
            formdata.append("type", 'deny');
            formdata.append("comment", jQuery('#denyCreativeComment').val());
            formdata.append("security", jQuery('#deny-nonce').val());
            formdata.append("order-id", jQuery('#order-id').val());

            // console.log('Form values'); 
            // for(var pair of formdata.entries()) {
            //    console.log(pair[0]+ ', '+ pair[1]); 
            // }
            // return;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('approve success', data);
                    if(data.status !== 'error'){
                        window.location.reload();
                    }
                }
            });
        });


        function profileprogressHandler(event) {
            var percent = (event.loaded / event.total) * 100;
            $(".progress-bar").width(percent + "%");
        }

        function profilecompleteHandler(event) {
            var data = JSON.parse(event.target.response);

            if (data.status == 0) {
                $(".fileinfo").html(data.filename);
                $("#fileguid").val(data.fileguid);
                $("#filename").val(data.filename);
                location.reload();
            }
        };

        function proofcompleteHandler(event) {
            var data = JSON.parse(event.target.response);

            if (data.status == 0) {
                $(".fileinfo").html(data.filename);
                $("#fileguid2").val(data.fileguid);
                $("#filename2").val(data.filename);
                location.reload();
            }
        }

        function profileerrorHandler(event) {
            //console.log("error", event);
        };

        function profileabortHandler(event) {
            //console.log("abort", event);
        };
    }
});