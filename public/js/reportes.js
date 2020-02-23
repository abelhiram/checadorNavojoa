$(document).ready(function(){
    
    $('div.tableInfo table').delegate('tr', 'click', function() {
        $('.'+this.id).toggle("fold");
    });

    $('.input-group.date').datepicker({format: "dd.mm.yyyy"});
});