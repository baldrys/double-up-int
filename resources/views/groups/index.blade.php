@extends('layouts.app')

@section('content')
@include('layouts.tableLayout', [
    'tableTitle' => 'User groups',
    'itemName' => 'group',
    'items' => $groups
    ])
    <script>
        $("#addForm").submit(function( event ) {
            event.preventDefault();
            var $form = $( this );
            var groupName = $form.find( "input[name='name']" ).val();
            $.ajax({
                url: "{{ url('api/v0/users/group') }}",
                data: { name: groupName },
                type: 'POST',
                }).done(function(data){location.reload()});
        });

        $(".table-save").click(function( event ) {
            event.preventDefault();
            var nameToSave = $( this ).closest('.row-item').children('td.item-name').text();
            var iDGroupToSave = $( this ).closest('.row-item').children('td.item-id').text();
            $.ajax({
                url: "{{ url('api/v0/users/group') }}"+"/"+iDGroupToSave,
                data: { name: nameToSave },
                type: 'PATCH',
                }).done(function(){location.reload()});

        });

        $(".table-remove").click(function( event ) {
            event.preventDefault();
            var idGroupToDelete = $( this ).closest('.row-item').children('td.item-id').text();
            console.log(idGroupToDelete);
            $.ajax({
                url: "{{ url('api/v0/users/groups') }}"+"/"+idGroupToDelete,
                type: 'DELETE',
                }).done(function(){location.reload()});
        });     
    </script>
@endsection