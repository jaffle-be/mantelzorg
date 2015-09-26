(function ($, app, Morris, moment) {

    'use strict';

    moment.locale('nl');

    var pagerQuestion = 1,
        pagerTerm = false,
        pagerPage = false;

    function getDonut(element) {
        $('#' + element).append($('<div>', {id: element + '-chart'}));
        return Morris.Donut({
            element: element + '-chart',
            data: [{
                label: 'loading',
                value: ''
            }],
            resize: true
        });
    }

    function getBar(element) {
        $('#' + element).append($('<div>', {id: element + '-chart'}));
        return Morris.Bar({
            element: element + '-chart',
            data: [],
            xkey: 'key',
            ykeys: [
                'doc_count',
            ],
            labels: ['Aantal'],
        }).on('click', function (i, term, c) {
            showTerm(term, 1);
        });
    }

    function toggleMultipleChoiseChart(chart, response) {
        var holder = $("#question-multiple-choise");

        if (response.multiple_choise)
        {
            holder.show();
            chart.setData(response.answers);
        }
        else
        {
            holder.hide();
        }
    }

    function toggleRegularChart(chart, response) {
        var holder = $("#question-regular");

        if (response.terms.buckets.length > 0)
        {
            holder.show();
            chart.setData(response.terms.buckets);
        }
        else
        {
            holder.hide();
        }
    }

    function loadQuestion(chartRegular, chartMultipleChoise) {

        $("#question-term").hide();

        var panel = $("#panel").val(),
            question = $("#question").val();

        $.ajax({
            url: '/stats/insights/question',
            type: 'POST',
            dataType: 'json',
            data: {
                panel: panel,
                question: question,
            },
            success: function (response) {
                setBasicQuestionInformation(response);
                toggleMultipleChoiseChart(chartMultipleChoise, response);
                toggleRegularChart(chartRegular, response);

                if (response.questions)
                {
                    setQuestions(response.questions);
                }

                //we set the lastpage to current panel question amount + 1 if there is a next panel
                //so we can click next when we reach the last question of a panel
                //to quickly go to the next panel.
                //only on the last panel, there should be no next button.
                var last_page = 0;

                if($("#panel option:last-child").attr('value') != $("#panel").val())
                {
                    last_page = $("#question option").size() + 1;
                }
                else{
                    last_page = $("#question option").size();
                }

                //we also want to force the previous button on all panels except the first panel.

                var forcePrevious = false;

                if($("#panel option:first-child").attr('value') != $("#panel").val())
                {
                    forcePrevious = true;
                }

                setPager($("#question-panel"), {
                    current_page: pagerQuestion,
                    last_page: last_page
                }, forcePrevious);
            }
        });
    }

    function setBasicQuestionInformation(response) {
        var title = $("#question-title"),
            info = $("#question-info"),
            meta = $("#question-meta"),
            options = $("#question-options");

        title.html(response.question.title);
        info.html(response.question.question);
        meta.html(response.question.meta);

        response.question.meta ? meta.show() : meta.hide();

        options.html('');

        if(response.multiple_choise)
        {
            var ul = $("<ul>");

            _.each(response.question.choises, function (choise) {
                ul.append($('<li>', {html: choise.title}))
            });
        }


        options.append(ul);
    }

    function setQuestions(questions) {
        var question = $("#question");

        question.html('');

        _.each(questions, function (q) {
            question.append($("<option>", {value: q.id, html: q.label}));
        });
    }

    function emptyQuestions() {
        var question = $("#question");
        question.html('');
    }

    function setPager(holder, response, forcePrevious) {

        if(typeof forcePrevious === 'undefined')
        {
            forcePrevious = false;
        }

        holder.find('.pager-previous, .pager-next').remove();

        if (response.current_page > 1 || forcePrevious)
        {
            //add previous button
            holder.append($('<a>', {
                'class': 'pager-previous btn btn-primary',
                'html': '<i class="fa fa-arrow-left"></i>'
            }))
        }

        if (response.current_page < response.last_page)
        {
            //add a next button
            holder.append($('<a>', {
                'class': 'pager-next btn btn-primary',
                'html': '<i class="fa fa-arrow-right"></i>'
            }))
        }
    }

    function showTerm(term, page) {
        pagerTerm = term;
        pagerPage = page;

        $.ajax({
            url: '/stats/insights/term',
            type: 'POST',
            dataType: 'json',
            data: {
                question: $("#question").val(),
                term: term.key,
                page: page
            },
            success: function (response) {
                var holder = $("#question-term");

                holder.html('');
                holder.show();
                _.each(response.data, function (item) {
                    holder.append($('<p>', {html: item.explanation}))
                });

                setPager(holder, response);

            }
        });
    }

    $(document).ready(function () {

        var questionMultipleChoise = getDonut('question-multiple-choise'),
            questionRegular = getBar('question-regular'),
            panel = $("#question-panel");

        loadQuestion(questionRegular, questionMultipleChoise);

        $("#panel").on('change', function () {
            emptyQuestions();
            pagerQuestion = 1;
            loadQuestion(questionRegular, questionMultipleChoise);
        });

        $("#question").on('change', function () {
            var value = $(this).val();

            $(this).find('option').each(function(i, item){

                if($(item).attr('value') == value)
                {
                    pagerQuestion = i+1;
                }
            });
            loadQuestion(questionRegular, questionMultipleChoise);
        });

        $("#question-term")
            .on('click', '.pager-previous', function (event) {
                showTerm(pagerTerm, pagerPage - 1);
                event.stopPropagation();
                event.preventDefault();

            })
            .on('click', '.pager-next', function (event) {
                showTerm(pagerTerm, pagerPage + 1);
                event.stopPropagation();
                event.preventDefault();
            });

        setPager(panel, {
            current_page: 1,
            last_page: $("#question option").size()
        });

        panel
            .on('click', '.pager-previous', function (event) {

                var previous = $("#question option:nth-child(" + (pagerQuestion - 1) + ")");

                //if there is a previous question, select the question
                if(previous.size())
                {
                    $("#question").val(previous.attr('value')).trigger('change');
                }
                else{
                    //if not, check to see if there is a previous panel.
                    //to do so we need to find the position of the currently selected element.
                    var position = _.findIndex($("#panel option"), function(item){
                        return $(item).attr('value') == $("#panel").val();
                    });

                    if(position > 0)
                    {
                        //we do not need to decrement, since position was 0 indexed and nth-child is not
                        $("#panel").val($("#panel option:nth-child("+ (position) +")").attr('value')).trigger('change');
                    }

                }

                event.stopPropagation();
                event.preventDefault();
            })
            .on('click', '.pager-next', function (event) {

                //if there is a next question, we select it
                var next = $("#question option:nth-child(" + (pagerQuestion + 1 ) + ")");

                if(next.size())
                {
                    $("#question").val(next.attr('value')).trigger('change');
                }
                else{
                    //if not, we check to see if there is a next panel and select that next panel
                    next = $("#panel option:selected + option");

                    if(next.size())
                    {
                        $("#panel").val(next.attr('value')).trigger('change');
                    }
                }

                event.stopPropagation();
                event.preventDefault();
            });

    });


})(window.jQuery, window.app, window.Morris, window.moment);