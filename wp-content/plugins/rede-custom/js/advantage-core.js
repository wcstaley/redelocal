jQuery(document).ready(function($) {

function runOnPackTotals(elEvent = false, arg = false) {
        if(arg === false){
            arg = redeGetStoreCount();
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
            clear_store_checks();
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

        $(".summary-total-stores").text(formatterstore(arg));
        $(".summary-total-cost").text(formatter(total));

        if (arg > 0) {
            $(".summary-cost-per-store").text(formatter(total / arg));
        } else {
            $(".summary-cost-per-store").text("$0");
        }

        $("#total").val(formatter(total));
        $("#costperstore").val(formatter(total / arg));
    }

});