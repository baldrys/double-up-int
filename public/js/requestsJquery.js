// Привязка выполнения POST запроса по url к отправке формы
function addItemWithForm(urlPost){
    $("#addForm").submit(function( event ) {
        event.preventDefault();
        var $form = $( this );
        var itemToAdd = $form.find( "input[name='name']" ).val();
        $.ajax({
            url: urlPost,
            data: { name: itemToAdd },
            type: 'POST',
            }).done(function(){location.reload()});
    })
}

// Привязка выполнения PATCH запроса по url к нажатию копки save
function saveOnClick(urlPatch){
    $(".table-save").click(function( event ) {
    event.preventDefault();
    var nameToSave = $( this ).closest('.row-item').children('td.item-name').text();
    var idPatch = $( this ).closest('.row-item').children('td.item-id').text();
    $.ajax({
        url: urlPatch+"/"+idPatch,
        data: { name: nameToSave },
        type: 'PATCH',
        }).done(function(){location.reload()});
    })
}


// Привязка выполнения DELETE запроса по url к нажатию копки delete
function deleteOnClick(urlDelete){
    $(".table-remove").click(function( event ) {
        event.preventDefault();
        var idDelete = $( this ).closest('.row-item').children('td.item-id').text();
        $.ajax({
            url: urlDelete+"/"+idDelete,
            type: 'DELETE',
            }).done(function(){location.reload()});
    })
}