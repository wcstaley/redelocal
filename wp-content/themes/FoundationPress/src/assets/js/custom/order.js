import pricing from '../modules/pricing';
import formatter from '../modules/formatter';
import formutils from '../modules/form-utils';

$(document).ready(function () {
    var is_onpack = false;
    var is_ooo = false;
    var is_shroud = false;
    var is_coupon = false;
    var is_mobile = false;
    var is_social = false;
    var is_sampling = false;
    var is_sas_at_shelf = false;
    var is_pos = false;
    var calculate_budget = false;
    var hasErrors = false;
    var startEdit = false;
    var isform = false;
    var processingOrder = false;

    var orderInit = function(){
        setupRedeOrderVars();
        setupUnloadConfirmation();
        setupOrderCookies();
        maybePrefill(); 
    }

    var maybePrefill = function(){
        if(typeof orderDetails !== "undefined" && typeof orderDetails.ID !== "undefined"){

            jQuery('#revision').val(orderDetails.ID);

            // Manually prepopulate data
            jQuery.each(orderDetails, function(key, value){
                console.log(key, value);

                switch(key){
                    case 'fileguid':
                    case 'fileguidgeography':
                    case 'customlistguid':
                    case 'customshopperguid':
                    case 'fileguidgeproductbeauty':
                    case 'fileguidgeproductshot':
                    case 'fileguidgeproductlogo':
                        break;

                    case 'pfid':
                        $('#pf-preview').attr('src', 'http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetBitMap.aspx?DocID='+value+'&UserName=dalim20151');
                        $('.pf-preview-container a').attr('href', "http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?DocID="+value+"&UserName=dalim20151");
                        $('#pfid').val(value);
                        break;

                    case 'filename':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileupload').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'filenamesku':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadsku').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'fileguidupgrade2':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileupload2').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'fileguidupgrade3':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileupload3').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'filenamegeography':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadgeography').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'customlistname':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadlist').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'customshoppername':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadshopper').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'filenameproductbeauty':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadproductbeauty').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'filenameproductshot':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadproductshot').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'filenameproductlogo':
                        var fileLink = '<a href="'+value+'" target="_blank">' + value.split('/').pop() + '</a>';
                        jQuery('#fileuploadproductlogo').parent().find(".fileinfo").html(fileLink);
                        break;

                    case 'tactic-custom':
                        jQuery( '#tactic-custom' ).prop('checked', true);
                        break;

                    case 'productcoupon':
                    case 'productdistribution':
                    case 'productsupplies':
                    case 'productcollateral':
                        var valuesArray = value.split(',');
                        jQuery.each(valuesArray, function(subkey, subval){
                            if(subval === "0"){
                                return true;
                            }
                           jQuery( "[name='"+key+"'][value='"+subval+"']" ).prop('checked', true);
                        });
                        break;

                    case 'store':
                        var valuesArray = value.replace(/, /g, '^');
                        valuesArray = valuesArray.replace(/,/g, '*');
                        valuesArray = valuesArray.replace(/\^/g, ', ');
                        valuesArray = valuesArray.split('*');
                        console.log('store valuesArray', valuesArray);
                        jQuery.each(valuesArray, function(subkey, subval){
                            if(subval === "0"){
                                return true;
                            }
                           jQuery( "[name='store'][value='"+subval+"']" ).prop('checked', true);
                        });
                        break;

                    case 'storedepartment':
                        var valuesArray = value.replace(/, /g, '^');
                        valuesArray = valuesArray.replace(/,/g, '*');
                        valuesArray = valuesArray.replace(/\^/g, ', ');
                        valuesArray = valuesArray.split('*');
                        jQuery.each(valuesArray, function(subkey, subval){
                            if(subval === "0"){
                                return true;
                            }
                        jQuery( "[name='storedepartment'][value='"+subval+"']" ).prop('checked', true);
                        });
                        break;

                    case 'upgrade-1':
                        jQuery('#upgrade-1').trigger('click');
                        break;
                    
                    case 'upgrade-2':
                        jQuery('#upgrade-2').trigger('click');
                        break;

                    case 'upgrade-3':
                        jQuery('#upgrade-3').trigger('click');
                        break;

                     case 'redecollateral':
                        jQuery('#redecollateral').trigger('click');
                        break;

                    case 'dma':
                        var valuesArray = value.replace(/, /g, '^');
                        valuesArray = valuesArray.replace(/,/g, '*');
                        valuesArray = valuesArray.replace(/\^/g, ', ');
                        valuesArray = valuesArray.split('*');
                        console.log('dma valuesArray', valuesArray);
                        jQuery.each(valuesArray, function(subkey, subval){
                           jQuery( "[value='"+subval+"']" ).prop('checked', true);
                        });
                        break;

                    case 'demographics':
                        var valuesArray = value.replace(/,9/g, '#');
                        valuesArray = valuesArray.replace(/,0/g, '@');
                        valuesArray = valuesArray.replace(/,/g, '^');
                        valuesArray = valuesArray.replace(/\#/g,',9');
                        valuesArray = valuesArray.replace(/\@/g, ',0');
                        valuesArray = valuesArray.split('^');
                        console.log('demographics valuesArray', valuesArray);
                        jQuery.each(valuesArray, function(subkey, subval){
                           jQuery( "[value='"+subval+"']" ).prop('checked', true);
                        });
                        break;

                    case 'marketdate':
                        if(jQuery('#type').val() === 'Security Shroud'){
                            $( "option[value='"+value+"']" ).select();
                        } else {
                            jQuery( "[name='"+key+"']" ).val(value);
                        }
                        break;

                    default:
                        jQuery( "[name='"+key+"']" ).val(value);
                        break;
                }

            });

            $("#budget").trigger('change');

            if(is_onpack){
                var storenum = redeGetStoreCount();
                pricing.runOnPackTotals(false, storenum);
            }
            if(is_pos){
                var storenum = redeGetStoreCount();
                if($('#destination').val() === "Email"){
                    $('.hider-email').show();
                    $('.hider-shipping').hide();
                    $('.padding-bottom-30').last().find('h4').text('8. Confirm and Proceed to Checkout');
                    $('.summary-print-quantity').parents('tr').hide();
                    $('.summary-total-shipping').parents('tr').hide();
                } else {
                    $('.hider-email').hide();
                    $('.hider-shipping').show();
                    $('.padding-bottom-30').last().find('h4').text('9. Confirm and Proceed to Checkout');
                    $('.summary-print-quantity').parents('tr').show();
                    $('.summary-total-shipping').parents('tr').show();
                }
                pricing.runPOSTotals(false);
            }
            if(is_ooo){
                storeBillboardCheck();
                dmaBillboardCheck();
            }
            if(is_shroud){
                pricing.getShroudTotal(false);
            }
            if(is_sas_at_shelf){
                var sasatshelftactic = $("#sasatshelftactic").val();
                if(sasatshelftactic.indexOf("on-pack") > 0){
                    $('.form-combo').show();
                } else {
                    $('.form-combo').hide();
                }
                pricing.runSASAtShelfTotals(false);
            }
        }
    }

    var setupRedeOrderVars = function(){
        // Custom template actions
        if($('body').hasClass('page-template-service-on-pack')){ // On pack
            is_onpack = true;
            isform = true;

            $('.datepicker').datepicker({
                minDate: '+21d',
                beforeShowDay: function(date){
                    //console.log('date', date.getDay());
                    if(date.getDay() === 1){
                        return [true, ""];
                    } else {
                        return [false, ""];
                    }
                }
            }); 

        } else if($('body').hasClass('page-template-service-pos-materials')){ // PoS Materials
            is_pos = true;
            isform = true;

            $("#marketdate").datepicker({
                minDate: '+14d'
            });
            
            $('.summary-design-cost').parents('tr').hide();
            
            if($('#destination').val() === "Email"){
                $('.hider-email').show();
                $('.hider-shipping').hide();
                $('.padding-bottom-30').last().find('h4').text('8. Confirm and Proceed to Checkout');
                $('.summary-print-quantity').parents('tr').hide();
                $('.summary-total-shipping').parents('tr').hide();
                
            } else {
                $('.hider-email').hide();
                $('.hider-shipping').show();
                $('.padding-bottom-30').last().find('h4').text('9. Confirm and Proceed to Checkout');
                $('.summary-print-quantity').parents('tr').show();
                $('.summary-total-shipping').parents('tr').show();
            }

        } else if($('body').hasClass('page-template-service-coupon')){ // Mobile media
            calculate_budget = true;
            isform = true;
            is_coupon = true;

            $("#marketdate").datepicker({
                minDate: '+7d'
            });

            $("#marketdate").change(function () {             
                var _t = formatter.addDays($('#marketdate').val(), 7);
                $("#enddate").datepicker('destroy');
                $("#enddate").datepicker({
                    minDate: (_t.getMonth() + 1) + '/' + _t.getDate() + '/' + _t.getFullYear()
                });
            });

        } else if($('body').hasClass('page-template-service-mobile-media')){ // Mobile media
            calculate_budget = true;
            isform = true;
            is_mobile = true;

            $("#marketdate").datepicker({
                minDate: '+7d'
            });

            $("#marketdate").change(function () {             
                var _t = formatter.addDays($('#marketdate').val(), 7);
                $("#enddate").datepicker('destroy');
                $("#enddate").datepicker({
                    minDate: (_t.getMonth() + 1) + '/' + _t.getDate() + '/' + _t.getFullYear()
                });
            });
        } else if($('body').hasClass('page-template-service-social')){ // Social
            calculate_budget = true;
            isform = true;
            is_social = true;

            $("#marketdate").datepicker({
                minDate: '+7d'
            });

            $("#marketdate").change(function () {             
                var _t = formatter.addDays($('#marketdate').val(), 7);
                $("#enddate").datepicker('destroy');
                $("#enddate").datepicker({
                    minDate: (_t.getMonth() + 1) + '/' + _t.getDate() + '/' + _t.getFullYear()
                });
            });
        } else if($('body').hasClass('page-template-service-out-of-home')){ // Out of home
            calculate_budget = true;
            isform = true;
            is_ooo = true;

            $("#marketdate").datepicker({
                minDate: '+2d'
            });

            $("#marketdate").change(function () {             
                var _t = formatter.addDays($('#marketdate').val(), 7);
                $("#enddate").datepicker('destroy');
                $("#enddate").datepicker({
                    minDate: (_t.getMonth() + 1) + '/' + _t.getDate() + '/' + _t.getFullYear()
                });
            });

        } else if($('body').hasClass('page-template-service-sampling')){ // Sampling
            calculate_budget = true;
            isform = true;
            is_sampling = true;

            $("#marketdate").datepicker({
                minDate: '+56d'
            });

            $("#marketdate2").datepicker({
                minDate: '+56d'
            });

            $("#marketdate").change(function () {
                var d = new Date($("#marketdate").val());
                // console.log('date', d, d.getDay());
                if(d.getDay() === 0){
                    $('#timestart').val('12:30 PM');
                    $('#timeend').val('6:30 PM');
                } else {
                    $('#timestart').val('10:30 AM');
                    $('#timeend').val('4:30 PM');
                }
            });
         

        } else if($('body').hasClass('page-template-service-shroud')){ // Shroud
            calculate_budget = true;
            isform = true;
            is_shroud = true;

            cleanDateSelect();
        } else if($('body').hasClass('page-template-service-sas-at-shelf')){ // Shroud
            isform = true;
            is_sas_at_shelf = true;

            $('.form-combo').hide();

            $("#marketdate_out_cycle").datepicker({
                minDate: '+28d',
                beforeShowDay: function(date){
                    //console.log('date', date.getDay());
                    if(date.getDay() === 1){
                        return [true, ""];
                    } else {
                        return [false, ""];
                    }
                }
            }); 

            cleanDateSelect();

        }
    }

    var setupUnloadConfirmation = function(){
        // Check if user closes page after page edits have been made
        window.addEventListener("beforeunload", function (e) {
            var confirmationMessage = "\o/";
            if(isform && startEdit && !processingOrder){
                (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                return confirmationMessage;   
            }                         //Webkit, Safari, Chrome
        });
    }

    var setupOrderCookies = function(){
        // Check for pageflex docid and change preview if available
        if(Cookies.get("docid") !== undefined){
            if (location.hostname === "localhost" || location.hostname === "127.0.0.1"){
                Cookies.remove('docid', { path: '/rede' });
            } else if (location.hostname === "www.rede-marketing.com"){
                Cookies.remove('docid', { path: '/', domain: '.www.rede-marketing.com' });
            } else {
                Cookies.remove('docid', { path: '/', domain: '.redemarketing.staging.wpengine.com' });
            }
        }
        var pfInterval = setInterval(function(){
            // console.log('test interval', Cookies.get("docid"));
            if(Cookies.get("docid") !== undefined){
                $('#pf-preview').attr('src', 'http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetBitMap.aspx?DocID='+Cookies.get("docid")+'&UserName=dalim20151');
                $('.pf-preview-container a').attr('href', "http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?DocID="+Cookies.get("docid")+"&UserName=dalim20151");
                $('#pfid').val(Cookies.get("docid"));
                clearInterval(pfInterval);
            }
        }, 1000);
    }

    var cleanDateSelect = function(){
        var $dateSelect = $('.date-select').find('optgroup');
        $dateSelect.each(function(i,el){
            var $optGroup = $(el);
            $optGroup.find('option').each(function(i2,el2){
                var $option = $(el2);
                // console.log('clean', $optGroup.attr('label'), $option.val());
                var selectDate = $option.val();
                selectDate = selectDate.trim().split('(');
                selectDate = selectDate[1].trim().split('-');
                selectDate = selectDate[0].trim();

                var d1 = new Date();
                d1.setDate(d1.getDate() + 7);    
                var d2 = new Date(selectDate);
                var pastDate = d1.getTime() > d2.getTime();

                if(pastDate){
                    $option.remove();
                    // console.log('clean', selectDate, d1, d2, pastDate);
                }
                
            });
        });
    }

    function redeGetStoreCount($display = true){
        var storenum = 0;
        var customstorecount = $('#customstorecount').val();

        if(customstorecount !== "" && customstorecount > 0){
            storenum = customstorecount;

        } else {
            $("input[name=store]:checked").each(function () {
                storenum += parseInt($(this).attr("data-num"));
                //console.log('test', storenum, $(this).parent().text().trim());
            });
        }
        if($display){
            $("#storecount").val(storenum);
            $(".summary-total-stores").text(formatter.formatterstore(storenum));
        }
        return storenum;
    }

    function dmaBillboardCheck(){
        var dmas = [];
        $("input.dma-check:checked").each(function () {
            if(parseInt($(this).val()) !== 0){
                dmas.push($(this).val());
            }
        });

        if(dmas.length > 0){
            var formdata = new FormData();
            formdata.append('action', 'dma_billboard_count');
            formdata.append('security', jQuery('#billboards-nonce').val());
            formdata.append('dmas', dmas.join('/'));

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('Got this from the server: ', data);
                    if(data.status !== 'error'){
                        $('.summary-billboards').html(formatter.formatterstore(data.data));
                        $('#billboardcount').val(data.data);
                    } else {
                        $('.summary-billboards').html('N/A');
                        $('#billboardcount').val(0);
                    }
                }
            });
        } else {
            $('.summary-billboards').html(0);
            $('#billboardcount').val(0);
        }

        console.log('dmas', dmas);
    }

    function storeBillboardCheck(){ 
        var stores = [];
        $("input.parent-store-check:checked").each(function () {
            if(parseInt($(this).val()) !== 0){
                stores.push($(this).val());
            }
        });

        if(stores.length > 0){
            var formdata = new FormData();
            formdata.append('action', 'billboard_count');
            formdata.append('security', jQuery('#billboards-nonce').val());
            formdata.append('stores', stores);

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: formdata,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function(data, textStatus, jqXHR) {  
                    console.log('Got this from the server: ', data);
                    if(data.status !== 'error'){
                        $('.summary-billboards').html(formatter.formatterstore(data.data));
                        $('#billboardcount').val(data.data);
                    } else {
                        $('.summary-billboards').html('N/A');
                        $('#billboardcount').val(0);
                    }
                },
                error: function( jqXHR, textStatus, errorThrown ){
                    console.log('billboard errors', textStatus);
                }
            });
        } else {
            $('.summary-billboards').html(0);
            $('#billboardcount').val(0);
        }

        console.log('stores', stores);
    }

    function processOrder(success_action = 'default'){
        // Start building ajax payload
        var dataFields = {
            'action': 'create_order',
            'security': jQuery('#order-nonce').val()
        };

        // Add form fields to payload
        $.each($('form').serializeArray(), function(_, kv) {
          if (dataFields.hasOwnProperty(kv.name)) {
            dataFields[kv.name] = $.makeArray(dataFields[kv.name]);
            dataFields[kv.name].push(kv.value);
          }
          else {
            dataFields[kv.name] = kv.value;
          }
        });

        // Convert ajax payload to FormData object so we can send files
        var formdata = new FormData();
        for (var key in dataFields) {
            formdata.append(key, dataFields[key]);
        }

        // Add creative files
        if($("#fileupload").length > 0){
            var files = $("#fileupload").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("creative", files[i]);
            }
        }

        // Add segment files
        if($("#fileuploadseg").length > 0){
            var files = $("#fileuploadseg").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("segment", files[i]);
            }
        }

        // Add segment files
        if($("#fileuploadlist").length > 0){
            var files = $("#fileuploadlist").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("customlist", files[i]);
            }
        }

        // Add shopper files
        if($("#fileuploadshopper").length > 0){
            var files = $("#fileuploadshopper").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("customshopper", files[i]);
            }
        }

        // Shroud upgrade 2
        if($("#fileupload2").length > 0){
            var files = $("#fileupload2").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("upgrade2", files[i]);
            }
        }

        // Shroud upgrade 3
        if($("#fileupload3").length > 0){
            var files = $("#fileupload3").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("upgrade3", files[i]);
            }
        }

        // Add audience files
        if($("#fileuploadaudience").length > 0){
            var files = $("#fileuploadaudience").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("audience", files[i]);
            }
        }

        // Add geography files
        if($("#fileuploadgeography").length > 0){
            var files = $("#fileuploadgeography").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("geography", files[i]);
            }
        }

        // Add geography files
        if($("#fileuploadsku").length > 0){
            var files = $("#fileuploadsku").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("sku", files[i]);
            }
        }

        // Add product beauty
        if($("#fileuploadproductbeauty").length > 0){
            var files = $("#fileuploadproductbeauty").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("productbeauty", files[i]);
            }
        }

        // Add product shot
        if($("#fileuploadproductshot").length > 0){
            var files = $("#fileuploadproductshot").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("productshot", files[i]);
            }
        }

        // Add product logo
        if($("#fileuploadproductlogo").length > 0){
            var files = $("#fileuploadproductlogo").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("productlogo", files[i]);
            }
        }

        // Add at-shelf creative
        if($("#fileuploadsasatshelf").length > 0){
            var files = $("#fileuploadsasatshelf").get(0).files;
            for (var i = 0; i < files.length; i++) {
                formdata.append("atshelfcreative", files[i]);
            }
        }


        // for(var pair of formdata.entries()) {
        //    console.log(pair[0]+ ': '+ pair[1]); 
        // }

        // display Foundations overlay when button is clicked to prevent multiple clicks
        $('.reveal-overlay').show();
        processingOrder = true;

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: formdata,
            cache: false,
            dataType: 'json',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR) {  
                console.log('Got this from the server: ', data);
                if(data.status !== 'error'){
                    console.log('Posted created with id ', data.data.id, success_action);
                    $('.reveal-overlay').hide();

                    if(success_action === 'form'){
                        var formUrl = window.location.pathname.replace(/\/$/, "").split('/').pop();
                        window.location.href = baseurl + '/dashboard/'+formUrl+'?order-id=' + data.data.id;
                    } else if(success_action === 'default'){
                        window.location.href = baseurl + '/order-confirm/?order-id=' + data.data.id;
                    } else if(success_action === 'review'){
                        window.location.href = baseurl + '/review-center/?order-id=' + data.data.id;
                    }
                    
                } else {
                    hasErrors = true;
                    var errorHTML = "";
                    var firstKey = '';
                    for (var key in data.data) {
                        if(firstKey === ''){
                            firstKey = key;
                        }
                        // console.log(key, data.data[key]);
                        $('#' + key).parent().addClass('has-error');
                        errorHTML += '<li>'+data.data[key]+'</li>';
                    }
                    // Hide Foundations overlay
                    $('.reveal-overlay').hide();
                    processingOrder = false;

                    // Find error's element so we can scroll to it
                    if(firstKey === "storecount"){
                        var scrollTo = $( "input[name*='store']" ).first().closest('.padding-bottom-30').offset().top;
                    } else {
                        var scrollTo = $('#' + firstKey).closest('.padding-bottom-30').offset().top;
                    }
                    $("html, body").animate({ scrollTop: scrollTo }, "fast");

                    //Display errors in a Foundation Reveal modal
                    $('#errorModal').find('h4').text('Your Order Is Missing Important Information');
                    $('#errorModal').find('p').text('Please review your order and correct the following issues');
                    $('#errorModal').find('.error-list').html(errorHTML);
                    $('#errorModal').foundation('open');
                }
            }
        });

        return false;
    }
    $('input').focus(function(){
        $(this).parent().removeClass('has-error');
    });

    $('input,select,textarea').change(function(){
        startEdit = true;
    });

    $('#upgrade-1').change(function(event) {
        var storenum = redeGetStoreCount();
        if(is_shroud){
            pricing.getShroudTotal(event);
        }
    });  
    $('#tactic-custom').click(function(){
        if(is_pos){
            pricing.runPOSTotals(false);
        }
        if(is_onpack){
            var storenum = redeGetStoreCount();
            pricing.runOnPackTotals(false, storenum);
        }
        if(is_shroud){
            pricing.getShroudTotal(false);
        }
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
        if ($(this).is(':checked')) {
            $('#redecreativemodal').foundation('open');
        }
    });
    $('#storecount').change(function(){
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
    });
    $('#at-shelf-tactic-custom').click(function(){
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
        if ($(this).is(':checked')) {
            $('#redeatshelfcreativemodal').foundation('open');
        }
    });

    $('#aislequantity').change(function(){
        if ($(this).val() > $('#sasatshelfquantity').val()) {
            $('#aislequantitymodal').foundation('open');
        }
    });

    $('#upgrade2-custom').click(function(){
        if ($(this).is(':checked')) {
            $('#redeupgrade2modal').foundation('open');
        }
    });

    $('#upgrade3-custom').click(function(){
        if ($(this).is(':checked')) {
            $('#redeupgrade3modal').foundation('open');
        }
    });
    

    $('#upgrade-2').change(function(event) {
        var storenum = redeGetStoreCount();
        if(is_shroud){
            pricing.getShroudTotal(event);
        }
        if(this.checked) {
            $(this).closest('.input-container').find('.nav-tabs-horizontal').show();
        } else {
            $(this).closest('.input-container').find('.nav-tabs-horizontal').hide();
        }
    });

    $('#upgrade-3').change(function(event) {
        var storenum = redeGetStoreCount();
        if(is_shroud){
            pricing.getShroudTotal(event);
        }
        if(this.checked) {
            $(this).closest('.input-container').find('.nav-tabs-horizontal').show();
        } else {
            $(this).closest('.input-container').find('.nav-tabs-horizontal').hide();
        }
    });

    $('#tactic').change(function() {
        if(is_pos){
            pricing.runPOSTotals(false);
        }
        if(is_onpack){
            var storenum = redeGetStoreCount();
            pricing.runOnPackTotals(false, storenum);
        }
        if(is_shroud){
            pricing.getShroudTotal(false);
        }
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
        if(typeof rede_tactics !== "undefined"){
            console.log('vanlla', this.value);
            var pfURL = baseurl + '/?pfid=' + rede_tactics[this.value][0] + '&pageflexredirect';
            var prevURL = 'http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetBitMap.aspx?DocID='+rede_tactics[this.value][1]+'&UserName=dalim20151';
            var prevLink = 'http://digitalprint.alliedprinting.com/ShopperMarketingHub/PMGetPdfProof.aspx?DocID='+rede_tactics[this.value][1]+'&UserName=dalim20151';
            $('#exampleTabsOne').find('.button.success').attr('href', pfURL);
            $('#exampleTabsOne').find('#pf-preview').attr('src', prevURL);
            $('#exampleTabsOne').find('.pf-preview-container a').attr('href', prevLink);
        }
    });

    $('#sasatshelftactic').change(function() {
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
    });

    $('#quantity').change(function() {
        if(is_onpack){
            var storenum = redeGetStoreCount();
            pricing.runOnPackTotals(false, storecount);
        }
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
    });

    $('#sasatshelfquantity').change(function() {
        if(is_sas_at_shelf){
            pricing.runSASAtShelfTotals(false);
        }
    });

    $('#dest-quantity').change(function() {
        pricing.runPOSTotals(false);
        $(".summary-print-quantity").text(formatter.formatterstore($('#dest-quantity').val()))
    });

    $('#destination').change(function() {
        if($('#destination').val() === "Email"){
            $('.hider-email').show();
            $('.hider-shipping').hide();
            $('.padding-bottom-30').last().find('h4').text('8. Confirm and Proceed to Checkout');
            $('.summary-print-quantity').parents('tr').hide();
            $('.summary-total-shipping').parents('tr').hide();
            $('.summary-design-cost').parents('tr').hide();
        } else {
            $('.hider-email').hide();
            $('.hider-shipping').show();
            $('.padding-bottom-30').last().find('h4').text('9. Confirm and Proceed to Checkout');
            $('.summary-print-quantity').parents('tr').show();
            $('.summary-total-shipping').parents('tr').show();
            $('.summary-design-cost').parents('tr').show();
        }
    });

    $('#budget').inputmask("numeric", {
        radixPoint: ".",
        groupSeparator: ",",
        digits: 2,
        autoGroup: true,
        prefix: '$ ', //Space after $, this will not truncate the first character.
        rightAlign: false,
        oncleared: function () { $('#budget').val(''); }
    });

    $("#budget").change(function () {

        var budget = parseInt($('#budget').val().replace('$ ','').replace(/,/g,''));

        pricing.checkBudget();

        if(calculate_budget && !is_shroud){
            $("#total").val($("#budget").val());
            $(".summary-total-cost").text($("#budget").val());
        }
    });

    $('.btnsubmit').click(function() {
        console.log('submitting');
        $('#confirm-form').submit();
    });

    $(".button.save").click(function(e) {
        e.preventDefault();
        processOrder('form');
    });

    $(".button.update").click(function(e) {
        e.preventDefault();
        processOrder('review');
    });

    $(".button.cancel" ).click(function(e){
        e.preventDefault();
        startEdit = false;
        window.location = baseurl + '/dashboard'
    });

    $(".btnproceed").click(function(e) {
        e.preventDefault();
        processOrder('default');
    });

    $("#upload").click(function () {
        $("#fileupload").click();
    });

    $("#upload2").click(function () {
        $("#fileupload2").click();
    });

    $("#upload3").click(function () {
        $("#fileupload3").click();
    });

    $("#uploadaudience").click(function () {
        $("#fileuploadaudience").click();
    });

    $("#uploadgeography").click(function () {
        $("#fileuploadgeography").click();
    });

    $("#uploadseg").click(function () {
        $("#fileuploadseg").click();
    });

    $("#uploadproductbeauty").click(function () {
        $("#fileuploadproductbeauty").click();
    });

    $("#uploadproductshot").click(function () {
        $("#fileuploadproductshot").click();
    });

    $("#uploadproductlogo").click(function () {
        $("#fileuploadproductlogo").click();
    });

    $("#uploadshopper").click(function () {
        $("#fileuploadshopper").click();
    });

    $("#uploadsasatshelf").click(function () {
        $("#fileuploadsasatshelf").click();
    });

    $("#uploadlist").click(function () {
        console.log('clicked', $("input[name=store]:checked").length, $("input.dma-check:checked").length);
        if( $("input[name=store]:checked").length > 0){
            var override = confirm("By clicking OK you confirm you would like to use a custom store list instead of the previously selected stores for your audience.");
            if(override){
                formutils.clear_store_checks();
                $("#fileuploadlist").click();
            } else {
                $("#fileuploadlist").val('');
            }
        } else if( $("input.dma-check:checked").length > 0){
            var override = confirm("By clicking OK you confirm you would like to use a custom store list instead of the previously selected DMAs for your audience.");
            if(override){
                formutils.clear_dma_checks();
                $("#fileuploadlist").click();
            } else {
                $("#fileuploadlist").val('');
            }
        } else {
            $("#fileuploadlist").click();
        }
    });

    $("#uploadsku").click(function () {
        $("#fileuploadsku").click();
    });

    $(".btnaddaddress").click(function (e) {
        e.preventDefault();
        $('#shippingaddress').append($('<option>', {
            value: $("#addshippingaddress").val(),
            text: $("#addshippingaddress").val(),
            selected:'selected'
        }));
        $("#addshippingaddress").val("");
        pricing.runPOSTotals(false);
    });

    $('#shippingaddress,#destination').change(function(){
        pricing.runPOSTotals(false);
    });

    $('input[type="file"]').change(function (event) {
        // Test if this is a store list
        if($(this).parent().find('#fileuploadlist').length > 0){
            var budget = parseInt($('#budget').val().replace('$ ','').replace(/,/g,''));
            if(budget === "" || isNaN(budget)){
                alert('Please enter a budget first.');
                event.preventDefault();
                event.stopPropagation();
                formutils.clear_store_checks();
                $("#storecount").val("");
                $("#budget").focus();
                $('#fileuploadlist').val('');
                $('#fileuploadlist').parent().find(".fileinfo").html('');
                return false;
            }

            var checkboxes = $(".dma-check");
            checkboxes.prop("checked", false);
            formutils.clear_store_checks();
            $(".summary-total-stores").text('0');
            $(".summary-cost-per-store").text('$0');
            $(".summary-per-shroud").text('$0');
            $(".summary-total-cost").text(formatter.formatter(budget));
            $("#storecount").val('');
            $("#total").val(formatter.formatter(budget));

        }
        // console.log($(this));
        var files = $(this).get(0).files;
        if(typeof files[0] !== "undefined"){
            // console.log('files', $(this), files[0].name);
            $(this).parent().find(".fileinfo").html(files[0].name);
        }
        
    });

    $("#dma-national").click(function(event){
        var isChecked = $(this).is(':checked');
        var checkboxes = $("[name=dma]").not("[value='National']");

        if (isChecked) {
            checkboxes.prop("checked", false);
            $("#dma-all").prop("checked", false);
        }
    });

    $("#dma-all").click(function (event) {
        var isChecked = $(this).is(':checked');
        var checkboxes = $("[name=dma]").not("[value='National']");

        console.log('dma', checkboxes);

        if ($(this).is(':checked')) {
            $("#dma-national").prop("checked", false);
            checkboxes.prop("checked", true);

        } else {
            checkboxes.prop("checked", false);
        }

    });

    $("#topdmas .dma-check").click(function (event) {
        if(!$(event.target).is("#dma-national")){
             console.log('dma event', event);
            $("#dma-national").prop("checked", false);
        }
       
        if(is_mobile || is_social || is_coupon){
            if( $("input[name=store]:checked").length > 0){
                var override = confirm("By clicking OK you confirm you would like to use DMAs instead of Stores for your audience.");
                if(override){
                    formutils.clear_store_checks();
                } else {
                    formutils.clear_dma_checks();
                }
            }
            if($('#fileuploadlist').get(0).files.length > 0){
                var override = confirm("By clicking OK you confirm you would like to use DMAs instead of a custom store list for your audience.");
                if(override){
                    $('#fileuploadlist').val('');
                    $('#fileuploadlist').parent().find(".fileinfo").html('');
                } else {
                    formutils.clear_dma_checks();
                }
            }
        }
        if(is_ooo){
            if( $("input[name=store]:checked").length > 0){
                var override = confirm("By clicking OK you confirm you would like to use DMAs instead of Stores for your audience.");
                if(override){
                    formutils.clear_store_checks();
                } else {
                    formutils.clear_dma_checks();
                }
            }
            if($('#fileuploadlist').get(0).files.length > 0){
                var override = confirm("By clicking OK you confirm you would like to use DMAs instead of a custom store list for your audience.");
                if(override){
                    $('#fileuploadlist').val('');
                    $('#fileuploadlist').parent().find(".fileinfo").html('');
                } else {
                    formutils.clear_dma_checks();
                }
            }
            dmaBillboardCheck();
        }
    });
    $("#sasatshelftactic").change(function (event) {
        pricing.runSASAtShelfTotals(false);
        var sasatshelftactic = $(this).val();
        console.log('sasatshelftactic', sasatshelftactic);
        if(sasatshelftactic.indexOf("on-pack") > 0){
            $('.form-combo').show();
        } else {
            $('.form-combo').hide();
        }
    });


    $(".dmas .checkbox-primary input").click(function (event) {
        // var id = $(this).val();
        var isChecked = $(this).is(':checked')
        var checkboxes = $(this).closest('.panel-body').find('.checkbox-secondary input');

        console.log('dma', checkboxes);

        if ($(this).is(':checked')) {
            checkboxes.prop("checked", true);

        } else {
            checkboxes.prop("checked", false);
        }


        var storenum = redeGetStoreCount();
        if(is_shroud){
            pricing.getShroudTotal(event);
        }

    });

    $('#customstorecount').change(function(elEvent){
            if(is_onpack){
                var storenum = redeGetStoreCount();
                pricing.runOnPackTotals(elEvent, storenum);
            }
            if(is_sas_at_shelf){
                pricing.runSASAtShelfTotals(false);
            }
    });

    $("input[name=store]").click(function (elEvent) {
        if(is_ooo){
            var id = $(this).data('parent');
        } else {
            var id = $(this).val();
        }

        if($(this).hasClass('parent-store-check')){
            var isChecked = $(this).is(':checked')
            var checkboxes = $(".p" + id);
            if ($(this).is(':checked')) {
                checkboxes.prop("checked", true);
            } else {
                checkboxes.prop("checked", false);
            }
        }

        var storenum = redeGetStoreCount();

        if(is_onpack){
            if($('#fileuploadlist').get(0).files.length > 0){
                var override = confirm("By clicking OK you confirm you would like to use Stores instead of a custom store list for your audience.");
                if(override){
                    $('#fileuploadlist').val('');
                    $('#fileuploadlist').parent().find(".fileinfo").html('');
                } else {
                    formutils.clear_store_checks();
                }
            }
            pricing.runOnPackTotals(elEvent, storenum);
        }
        if(is_ooo){
            if( $("input.dma-check:checked").length > 0){
                var override = confirm("By clicking OK you confirm you would like to use Stores instead of DMAs for your audience.");
                if(override){
                    formutils.clear_dma_checks();
                } else {
                    formutils.clear_store_checks();
                }
            }
            if($('#fileuploadlist').get(0).files.length > 0){
                var override = confirm("By clicking OK you confirm you would like to use Stores instead of a custom store list for your audience.");
                if(override){
                    $('#fileuploadlist').val('');
                    $('#fileuploadlist').parent().find(".fileinfo").html('');
                } else {
                    formutils.clear_store_checks();
                }
            }
            storeBillboardCheck();
        }

        if(is_social || is_mobile || is_coupon){
            if( $("input.dma-check:checked").length > 0){
                var override = confirm("By clicking OK you confirm you would like to use Stores instead of DMAs for your audience.");
                if(override){
                    formutils.clear_dma_checks();
                } else {
                    formutils.clear_store_checks();
                }
            }
            if($('#fileuploadlist').get(0).files.length > 0){
                var override = confirm("By clicking OK you confirm you would like to use Stores instead of a custom store list for your audience.");
                if(override){
                    $('#fileuploadlist').val('');
                    $('#fileuploadlist').parent().find(".fileinfo").html('');
                } else {
                    formutils.clear_store_checks();
                }
            }
        }

        if(is_shroud){
            pricing.getShroudTotal(elEvent);
        }

        if(is_sas_at_shelf){
            // Change max store count based on selected stores
            var arg = redeGetStoreCount();
            if(arg > 0){
                $('#selected-store-count').text(arg);
                pricing.runSASAtShelfTotals(false);
            } else {
                $('#selected-store-count').text(0);
            }
            
        }

    });

    $("#marketdate").change(function () {
        $("#marketdate_out_cycle").val('');
        $(".summary-date").text($("#marketdate").val());
    });

    $("#marketdate_out_cycle").change(function() {
        $("#marketdate").val('');
        $(".summary-date").text($("#marketdate_out_cycle").val());
    })

    orderInit();
});