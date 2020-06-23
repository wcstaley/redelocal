export default (function () {

    var formatter = function (n) {
        return "$" + parseFloat(n).toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    };

    var formatterstore = function (n) {
        n = parseFloat(n);
        return n.toFixed(1).replace(/(\d)(?=(\d{3})+\.)/g, "$1,").replace(".0", "");
    };

    var addDays = function (date, days) {
        var result = new Date(date);
        result.setDate(result.getDate() + days);
        return result;
    }

    return {
        formatter: formatter,
        formatterstore: formatterstore,
        addDays: addDays
    }

})();