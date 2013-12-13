(function($, app){

    'use strict';

    if(typeof app.questionnaire === 'undefined') app.questionnaire = {};
    if(typeof app.questionnaire === 'undefined') app.questionnaire.question = {};

    function Saver()
    {
        this.init();
    }

    Saver.prototype = {
        init: function()
        {
            this.events();
        },
        events: function()
        {

        },
        name: function(title)
        {

        },
        value: function(box)
        {
        },
        sort: function(title)
        {
        },
        position: function(weight)
        {
            return weight / 10 + 1;
        },
        question: function(element)
        {

        }
    }

    $(document).ready(function()
    {
        app.questionnaire.question.saver = new Saver;
    });

})(window.jQuery, window.app);