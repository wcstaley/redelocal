
export default (function () {
    
    function clear_store_checks(){
        var checkboxes = $('input[name=store]');
        checkboxes.prop("checked", false);
    }

    function clear_dma_checks(){
        var checkboxes = $('input.dma-check');
        checkboxes.prop("checked", false);
    }

    return {
        clear_store_checks: clear_store_checks,
        clear_dma_checks: clear_dma_checks,
    }

})();