$(document).ready(function () {
    if($('body.page-template-dashboard').length > 0){
        $('.dash-services').slick({
            dots: false,
            infinite: true,
            speed: 300,
            slidesToShow: 9,
            slidesToScroll: 1,
            responsive: [
                {
                breakpoint: 999999,
                    settings: "unslick"
                },
                {
                breakpoint: 1024,
                    settings: {
                        slidesToShow: 6,
                    }
                },
                {
                breakpoint: 750,
                    settings: {
                        slidesToShow: 3,
                    }
                },
                {
                breakpoint: 480,
                    settings: {
                        slidesToShow: 2,
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        var labels = [];
        var datasets = [];
        var tmpDatasets = [];
        var monthLabelsL = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        var monthLabelsS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        var monthLabelsS = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'];
        var monthColors = ['#a22522','#3e2d24','#d55420','#705a38','#a39043','#b15a56','#56704d','#60a379','#efc383','#5e3e7b'];


        labels = monthLabelsS;

        for (var i = 0; i < monthLabelsS.length; i++) {
            monthLabelsS[i];
            //Do something
        }

        for(var orderkey in pricing.all_orders) {
            var order = pricing.all_orders[orderkey];
            var orderDate = new Date(order.date);
            var orderMonth = orderDate.getMonth();
            var orderTotal = parseInt(order.total, 10);

            if(!tmpDatasets[order.type]){
                tmpDatasets[order.type] = {
                    label: order.type,
                    backgroundColor: monthColors.shift(),
                    data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
                };
            }
            tmpDatasets[order.type].data[orderMonth] += orderTotal;
        }

        console.log('tmpDatasets',tmpDatasets);

        for(var ordtmpDatasetsKey in tmpDatasets) {
            var tmpDataset = tmpDatasets[ordtmpDatasetsKey];
            datasets.push(tmpDataset);
        }

        console.log('datasets',datasets);

        var ctx = $("#myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            var label = tooltipItem.yLabel;
                            return '$' + label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true,
                            callback: function(value, index, values) {
                                if(parseInt(value) >= 1000){
                                    return '$' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                                } else {
                                    return '$' + value;
                                }
                            }
                        }
                    }]
                }
            }
        });

        $('.result-overlay').hide();

        $('.result-overlay').on('click', '.close-overlay', function(event){
            event.stopPropagation();
            event.preventDefault();

            $('.result-overlay').hide();

        });

        var activeCampaigns = $('#panel1 tbody').find('tr').length;
        if(activeCampaigns === 0){
            $('#example-tabs li:eq( 1 ) a').trigger('click');
        }

        var remaining_budget =  pricing.budget_cap - pricing.orders_total;
        remaining_budget = remaining_budget.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        $('.remaining-budget').text('$' + remaining_budget);

        $('.quickstart-submit').click(function(e){
            e.preventDefault();
            var formData = {};
            $(this).parents('form').find('[name]').each(function() {
                formData[this.name] = this.value;  
            })

            var resultMatches = {
                3: [],
                2: [],
                1: []
            };

            if(pricing.quick_start_setup !== ""){
                for (var key in pricing.quick_start_setup) {
                    if (!pricing.quick_start_setup.hasOwnProperty(key)) continue;
                    var obj = pricing.quick_start_setup[key];
                    var matches = 0;

                    if(formData.objective_options === obj.objective){
                        matches++;
                    } else if(obj.objective !== "any"){
                        continue;
                    }
                    if(formData.timing_options === obj.timing){
                        matches++
                    } else if(obj.timing !== "any"){
                        continue;
                    }
                    if(formData.budget_options === obj.budget){
                        matches++;
                    } else if(obj.budget !== "any"){
                        continue;
                    }

                    if(matches > 0){
                        resultMatches[matches].push({
                            name: obj.service.split('---')[0],
                            url: obj.service.split('---')[1],
                            description: obj.description,
                            image: $('.dash-services').find('a[href="'+obj.service.split('---')[1]+'"]').parents('.dash-service').find('.service-image img').attr('src')
                        });
                    }
                }

                var outputHTML = "";
                var tmpOutputHTML = "";
                var outputHTMLArray = [];
                var matchesTactics = [];

                for(var matchkey in resultMatches[3]) {
                    var match = resultMatches[3][matchkey];
                    if(!$.inArray(match.name,matchesTactics)){
                        continue;
                    }
                    matchesTactics.push(match.name);
                    tmpOutputHTML = quickStartBuildHTML(match);
                    outputHTMLArray.push(tmpOutputHTML);
                }

                for(var matchkey in resultMatches[2]) {
                    var match = resultMatches[2][matchkey];
                    if(!$.inArray(match.name,matchesTactics)){
                        continue;
                    }
                    matchesTactics.push(match.name);
                    tmpOutputHTML = quickStartBuildHTML(match);
                    outputHTMLArray.push(tmpOutputHTML);
                }

                for(var matchkey in resultMatches[1]) {
                    var match = resultMatches[1][matchkey];
                    if(!$.inArray(match.name,matchesTactics)){
                        continue;
                    }
                    matchesTactics.push(match.name);
                    tmpOutputHTML = quickStartBuildHTML(match);
                    outputHTMLArray.push(tmpOutputHTML);
                }

                outputHTML = outputHTMLArray.slice(0, 3).join("");
                outputHTML = (outputHTML !== "" ? '<div class="scroll-container">' + outputHTML + '</div>' : '<p class="close-overlay-wrap">No Matches</p>') + '<a class="close-overlay">Again?<a/>';

                $('.result-overlay').html(outputHTML);
                $('.scroll-container').height($('.result-overlay').height() - 20);
                $('.result-overlay').show();
            }

        });

        function quickStartBuildHTML(match){
            var tmpOutputHTML = "<div class='quick-start-result'>";
            tmpOutputHTML += "<div class='service-image'><img src='" + match.image + "'></div>";
            tmpOutputHTML += "<div class='service-content'><h3><a href='" + match.url + "'>" + match.name + "</a></h3>";
            tmpOutputHTML += "<a class='button success' href='" + match.url + "'>Get Started</a>";
            tmpOutputHTML += "<p>" + match.description + "</p></div>";
            tmpOutputHTML += "</div>";
            return tmpOutputHTML;
        }
    }
});