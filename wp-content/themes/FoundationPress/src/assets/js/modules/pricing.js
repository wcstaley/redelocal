import formatter from '../modules/formatter';
import formutils from '../modules/form-utils';

export default (function (pricing) {
    var overBudgetconfirm = false;

    var getCost = function(arg, tactic) {
        // var tactic = $("#tactic").val();
        var cost = 0.025;
        for (var j = 0; j < pricing.tactics.length; j++){
            if(tactic === pricing.tactics[j].tactic && arg >= pricing.tactics[j].min &&  arg <= pricing.tactics[j].max){
                // console.log('test', pricing.tactics[j].tactic);
                cost = pricing.tactics[j].price;
                break;
            }
        }
        return parseFloat(cost);
    };

    var getSASCost = function(arg1, arg2, tactic, onpack) {
        // console.log('getSASCost', arg1, arg2, tactic, onpack);
        // var tactic = $("#tactic").val();
        var cost = 0.025;
        var onPackCost = 0;

        for (var j = 0; j < pricing.tactics.length; j++){
            if(tactic === pricing.tactics[j].tactic && arg1 >= pricing.tactics[j].min &&  arg1 <= pricing.tactics[j].max){
                // console.log('test', pricing.tactics[j].tactic);
                cost = pricing.tactics[j].price;
                break;
            }
        }
        for (var j = 0; j < pricing.tactics.length; j++){
            if(onpack === pricing.tactics[j].tactic && arg2 >= pricing.tactics[j].min &&  arg2 <= pricing.tactics[j].max){
                // console.log('test', pricing.tactics[j].tactic);
                onPackCost = pricing.tactics[j].price;
                break;
            }
        }
        return {
            'shelf': parseFloat(cost),
            'onpack': parseFloat(onPackCost)
        };
    };

    var getPOSShipping = function(arg){
        var shipping =  0;

        // Hard coded default values
        if(arg < 2500){
            shipping = 50;
        } else if(arg > 2501 && arg < 5000){
            shipping = 80;
        } else if(arg > 5001 && arg < 7500){
            shipping = 110;
        } else if(arg > 7501 && arg < 10000){
            shipping = 140;
        } else if(arg > 10001 && arg < 15000){
            shipping = 190;
        } else if(arg > 15001){
            shipping = 230;         
        }

        if(pricing.variable_shipping.length > 0){
            for (var j = 0; j < pricing.variable_shipping.length; j++){
                if(tactic === pricing.variable_shipping[j].tactic && arg >= pricing.variable_shipping[j].min &&  arg <= pricing.variable_shipping[j].max){
                    // console.log('test', pricing.variable_shipping[j].tactic);
                    shipping = pricing.variable_shipping[j].price;
                    break;
                }
            }
        }
        return shipping;
    };

    var calcOnPackTotals = function(arg){
        var merch = pricing.merch;
        var fullfillment = pricing.fullfillment;
        var shipping = pricing.shipping;
        var markup = pricing.markup;
        var tacticcost = pricing.tacticcost;
        var quantity = $("#quantity").val();
        var rede_cost = pricing.rede_cost;
        tacticcost = getCost(quantity * arg, $("#tactic").val());

        if (quantity == null || quantity == 0) {
            tacticcost = 0;
            quantity = 0;
        }

        var total = 0;
        total = merch * arg; // merch cost
        total = (fullfillment * arg) + total; // fullfilment w/markup
        total = (shipping * arg) + total;
        total = (quantity * arg * tacticcost) + total;
        if($('#tactic-custom').is(':checked')){
            total += rede_cost;
        }
        total = total * markup;

        console.log({
            locations: arg,
            quantity: quantity,
            shipping: shipping,
            markup: markup,
            tacticcost: tacticcost,
            rede_cost: rede_cost,
            total: total
        });

        return total;
    };

    var runOnPackTotals = function(elEvent = false, arg = false) {
        if(arg === false){
            console.log('missing paramater 2');
        }
        var total = calcOnPackTotals(arg);
        var budget = parseInt($('#budget').val().replace('$ ','').replace(/,/g,''));

        // console.log('budget', isNaN(budget));

        if(budget === "" || isNaN(budget)){
            alert('Please enter a budget first.');
            if(elEvent !== false){
                elEvent.preventDefault();
                elEvent.stopPropagation();
            }
            formutils.clear_store_checks();
            $("#storecount").val("");
            $("#budget").focus();
            return false;
        }

        if(!overBudgetconfirm && budget < total){
            var overrideBudget = confirm('Total selected store count exceeds your budget. We will proportionately reduce your store count to reflect the budgeted amount.');
            if(!overrideBudget){
                // console.log('elEvent', $(elEvent.target));
                if(elEvent !== false){
                    elEvent.preventDefault();
                    elEvent.stopPropagation();
                    var id = $(elEvent.target).val();
                    var checkboxes = $(".p" + id);
                    checkboxes.prop("checked", false);
                    return false;
                }
            } else {
                overBudgetconfirm = true;
            }
        }

        if(budget < total && overBudgetconfirm){
            var increment = 100;
            var max = 40000;
            for (var i = 0; i <= max; i++) {
                total = calcOnPackTotals(i);
                if(total > budget){
                    arg = i-1;
                    total = calcOnPackTotals(arg);
                    $("#storecount").val(arg);
                    break;
                }
            }
        }

        $(".summary-total-stores").text(formatter.formatterstore(arg));
        $(".summary-total-cost").text(formatter.formatter(total));

        if (arg > 0) {
            $(".summary-cost-per-store").text(formatter.formatter(total / arg));
        } else {
            $(".summary-cost-per-store").text("$0");
        }

        $("#total").val(formatter.formatter(total));
        $("#costperstore").val(formatter.formatter(total / arg));
    }

    var calcPOSTotals = function(){
        var total = 0;
        var locations = $('#shippingaddress').val().length;
        var quantity = $("#dest-quantity").val();
        var shipping = getPOSShipping(quantity);
        var markup = pricing.markup;
        var tacticcost = pricing.tacticcost;
        var rede_cost = pricing.rede_cost;

        // Set default values for quantity if not set
        if (quantity == null || quantity == 0) {
            quantity = 0;
        }

        // calculate tactic costs based on number of stores
        tacticcost = getCost(quantity * locations, $("#tactic").val());

        // calculate shipping costs based on quantity
        var total_shipping = shipping;

        if($('#destination').val() === "Email"){
            total = 0;
        } else {
            total = (quantity * tacticcost * markup) + total_shipping;
        }

        if($('#tactic-custom').is(':checked')){
            total += rede_cost;
            $(".summary-design-cost").parents('tr').show();
            $(".summary-design-cost").text(formatter.formatter(rede_cost));
        } else {
            $(".summary-design-cost").parents('tr').hide();
            $(".summary-design-cost").text("");
        }

        $(".summary-total-shipping").text(formatter.formatter(total_shipping));

        console.log({
            locations: locations,
            quantity: quantity,
            shipping: shipping,
            markup: markup,
            tacticcost: tacticcost,
            rede_cost: rede_cost,
            total: total
        });

        return total;
    };

    var getShroudTotal = function(elEvent = false) {
        var base = pricing.base;
        var upgrade1Amount = pricing.upgrade1Amount;
        var upgrade2Amount = pricing.upgrade2Amount;
        var upgrade3Amount = pricing.upgrade3Amount;
        var rede_cost = pricing.rede_cost;

        var upgrade1 = 0;
        var upgrade2 = 0;
        var upgrade3 = 0;
        var storecount = $("#storecount").val();

        if ($('#upgrade-1').is(':checked')) {
            upgrade1 = upgrade1Amount;
        }

        if ($('#upgrade-2').is(':checked')) {
            upgrade2 = upgrade2Amount;
        }

        if ($('#upgrade-3').is(':checked')) {
            upgrade3 = upgrade3Amount;
        }

        var perShroud = (base) + (upgrade1) + (upgrade2);
        var __total = (storecount * base) + (storecount * upgrade1) + (storecount * upgrade2) + (upgrade3);
        if($('#tactic-custom').is(':checked')){
            __total += rede_cost;
        }
        var budget = parseInt($('#budget').val().replace('$ ','').replace(/,/g,''));

        console.log('getShroudTotal', perShroud, __total, budget, storecount);

        if(budget === "" || isNaN(budget)){
            alert('Please select a budget first.');
            if(elEvent !== false){
                elEvent.preventDefault();
                elEvent.stopPropagation();
            }
            formutils.clear_store_checks();
            if(elEvent !== false && $(elEvent.target).is("#dma-all")){
                var checkboxes = $("#segTabsFour [name=dma]");
                checkboxes.prop("checked", false);
            }
            $("#budget").focus();
            return;
        }
        if(!overBudgetconfirm && budget < __total){
            var overrideBudget = confirm('Total selected store count exceeds your budget. We will proportionately reduce your store count to reflect the budgeted amount. Distribution to be determined by your selection under Optimization Preferences.');
            if(!overrideBudget){
                if(elEvent !== false){
                    console.log('elEvent', $(elEvent.target));
                    if($(elEvent.target).hasClass('switch-input')){
                        console.log('is switch');
                        setTimeout(function(){
                            console.log('switching');
                            $(elEvent.target).siblings('.switch-paddle').trigger('click');
                            $(elEvent.target).prop("checked", false);
                        }, 500);
                        
                    }
                    if($(elEvent.target).is("#dma-all")){
                        var checkboxes = $("#segTabsFour [name=dma]");
                        checkboxes.prop("checked", false);
                    }
                    elEvent.preventDefault();
                    elEvent.stopPropagation();
                    return false;
                }
            } else {
                overBudgetconfirm = true;
            }
        }

        if(budget < __total && overBudgetconfirm){
            var maxTotal = (budget - upgrade3);
            var maxStores = Math.floor(maxTotal / perShroud);
            storecount = maxStores;

            var __total = (maxStores * base) + (maxStores * upgrade1) + (maxStores * upgrade2) + (upgrade3);
        }

        $(".summary-total-stores").text(formatter.formatterstore(storecount));
        $(".summary-per-shroud").text(formatter.formatter(perShroud));
        $(".summary-total-cost").text(formatter.formatter(__total));
        $("#storecount").val(storecount);
        $("#total").val(formatter.formatter(__total));
    };

    var calcSASAtShelfTotals = function(arg){
        var quantity = $("#quantity").val();
        var sasatshelfquantity = $('#sasatshelfquantity').val();
        var sasatshelftactic = $("#sasatshelftactic").val().split(' with ')[0];
        var tactic = $("#tactic").val();
        var tacticcost = getSASCost(sasatshelfquantity * arg, quantity * arg, sasatshelftactic, tactic);
        // console.log('tacticcost', sasatshelftactic, tactic, tacticcost);
        var sas_shelf_first = pricing.sas_shelf_first;
        var sas_shelf_add = pricing.sas_shelf_add;
        var sas_shelf_shipping = pricing.sas_shelf_shipping;
        var sas_shelf_kitting = pricing.sas_shelf_kitting;

        var sas_combo_first = pricing.sas_combo_first;
        var sas_combo_add = pricing.sas_combo_add;
        var sas_combo_add_blade = pricing.sas_combo_add_blade;
        var sas_combo_shipping = pricing.sas_combo_shipping;
        var sas_combo_kitting = pricing.sas_combo_kitting;

        var rede_cost = pricing.rede_cost;

        var markup = pricing.markup;

        if (quantity == null || quantity == 0) {
            tacticcost = 0;
            quantity = 0;
        }

        var total = 0;
        var totalDesign = 0;
        var hasOnPack = $("#sasatshelftactic").val().indexOf("on-pack");

        // Merchandising:
        // Shelf Blades and Danglers
        // $8 for one
        // $6 for each additional blade same run
        // Shipping $2
        // Kitting $2
        
        // Combo
        // $10 per store 50 IRC
        // $2 each additional 25 IRC
        // $6 for each additional blade same run
        // Shipping $2
        // Kitting $2

        if (hasOnPack < 0) {

            var bladePrinting = tacticcost.shelf * sasatshelfquantity;
            // var onpackPrinting = (tacticcost.onpack * (quantity * arg));
            if (sasatshelfquantity > 1) {
                var bladeMerch = sas_shelf_first + (sasatshelfquantity - 1) * sas_shelf_add;
            } else {
                var bladeMerch = sas_shelf_first;
            }

            var kitting = sas_shelf_kitting;
            var shipping = sas_shelf_shipping;

            var total = bladePrinting + bladeMerch + kitting + shipping;
            var total = total * arg;
        } else {
            var bladePrinting = tacticcost.shelf * sasatshelfquantity;
            var onpackPrinting = tacticcost.onpack * quantity;

            if (quantity > 50) {
                var leftover = 50 - quantity;
                var atShelfMerchCombo = sas_combo_first + Math.ceil(leftover / 25) * sas_combo_add;
            } else {
                var atShelfMerchCombo = sas_combo_first;
            }
            if (sasatshelfquantity > 1) {
                var bladeMerch = (sasatshelfquantity - 1) * sas_combo_add_blade;
            } else {
                var bladeMerch = 0;
            }

            var kitting = sas_combo_kitting;
            var shipping = sas_combo_shipping;

            var total = bladePrinting + onpackPrinting + atShelfMerchCombo + bladeMerch + kitting + shipping;
            var total = total * arg;
        }

        if($('#tactic-custom').is(':checked')){
            total += rede_cost;
            totalDesign += rede_cost;
        }

        if($('#at-shelf-tactic-custom').is(':checked')){
            total += rede_cost;
            totalDesign += rede_cost;
        }

        if(markup){
            total = total * markup;
        }

        // Add warning if quantity greater than aisles
        $(".summary-design-cost").text(formatter.formatter(totalDesign));

        console.log({
            quantity: quantity, 
            sasatshelfquantity: sasatshelfquantity,
            sasatshelftactic: sasatshelftactic,
            tactic: tactic,
            tacticcost: tacticcost,
            markup: markup,
            hasOnPack: hasOnPack,
            combo: {
                bladePrinting: bladePrinting,
                onpackPrinting: onpackPrinting,
                atShelfMerchCombo: atShelfMerchCombo,
                bladeMerch: bladeMerch,
                kitting: kitting,
                shipping: shipping
            },
            single: {
                bladePrinting: bladePrinting,
                bladeMerch: bladeMerch,
                kitting: kitting,
                shipping: shipping
            },
            totalDesign: totalDesign,
            total: total
        });

        return total;
    }

    var runPOSTotals = function(elEvent = false, arg = false) {
        if(arg === false){
            arg = $('#shippingaddress').val().length;
        }
        var total = calcPOSTotals();
        // console.log('budget', isNaN(budget));

        //$(".summary-total-stores").text(formatter.formatterstore(arg));
        $(".summary-total-cost").text(formatter.formatter(total));

        // if (arg > 0) {
        //     $(".summary-cost-per-store").text(formatter.formatter(total / arg));
        // } else {
        //     $(".summary-cost-per-store").text("$0");
        // }

        $("#total").val(formatter.formatter(total));
        // $("#costperstore").val(formatter.formatter(total / arg));
    }

    var runSASAtShelfTotals = function(elEvent = false, arg = false) {
        if(arg === false){
            if($('#storecount').val() === ""){
                arg = 0;
                $("input[name=store]:checked").each(function () {
                    storenum += parseInt($(this).attr("data-num"));
                });
            } else {
                arg = $('#storecount').val();
            }
            if($('#customstorecount').val() > 0){
                arg = $('#customstorecount').val();
            }
        }
        var total = calcSASAtShelfTotals(arg);
        var budget = parseInt($('#budget').val().replace('$ ','').replace(/,/g,''));

        // console.log('budget', isNaN(budget));

        if(budget === "" || isNaN(budget)){
            alert('Please enter a budget first.');
            if(elEvent !== false){
                elEvent.preventDefault();
                elEvent.stopPropagation();
            }
            formutils.clear_store_checks();
            $("#storecount").val("");
            $("#budget").focus();
            return false;
        }

        // if(budget < total && overBudgetconfirm){
        if(budget < total){
            var max = 40000;
            for (var i = 0; i <= max; i++) {
                total = calcSASAtShelfTotals(i);
                if(total > budget){
                    arg = i-1;
                    total = calcSASAtShelfTotals(arg);
                    $("#storecount").val(arg);
                    break;
                }
            }
        }

        $(".summary-total-stores").text(formatter.formatterstore(arg));
        $(".summary-total-cost").text(formatter.formatter(total));

        if (arg > 0) {
            $(".summary-cost-per-store").text(formatter.formatter(total / arg));
        } else {
            $(".summary-cost-per-store").text("$0");
        }

        $("#total").val(formatter.formatter(total));
        $("#costperstore").val(formatter.formatter(total / arg));
    }

    var checkBudget = function(){
        if(pricing.budget_cap && pricing.budget_cap > 0){
            if(pricing.budget_cap < pricing.orders_total){
                alert('You are over the budget cap for this account.');
                $('#budget').val('$');
            } else if(pricing.budget_cap < pricing.orders_total + budget){
                alert('This budget will put you over the budget cap for this account.');
                $('#budget').val('$');
            }
        }
    }

    return {
        getCost: getCost,
        getSASCost: getSASCost,
        runSASAtShelfTotals: runSASAtShelfTotals,
        getPOSShipping: getPOSShipping,
        calcOnPackTotals: calcOnPackTotals,
        runOnPackTotals: runOnPackTotals,
        calcPOSTotals: calcPOSTotals,
        runPOSTotals: runPOSTotals,
        getShroudTotal: getShroudTotal,
        calcSASAtShelfTotals: calcSASAtShelfTotals,
        checkBudget: checkBudget
    }

})(window.pricing);