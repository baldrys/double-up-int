@extends('layouts.app')

@section('content')
@include('layouts.tableLayout', [
    'tableTitle' => $user->name.'s groups',
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
                    }).done(function(data){
                        var newGroupId = data.data.created_group.id;
                        $.ajax({
                        url: "{{ url('api/v0/user/'.$user->id.'/group/') }}"+"/"+newGroupId,
                        type: 'POST',
                        }).done(function(){location.reload()});  
                    });
            });

            $(".table-save").click(function( event ) {
                event.preventDefault();
                var nameToSave = $( this ).closest('.row-item').children('td.item-name').text();
                var idGroupToSave = $( this ).closest('.row-item').children('td.item-id').text();
                $.ajax({
                    url: "{{ url('api/v0/users/group') }}"+"/"+idGroupToSave,
                    data: { name: nameToSave },
                    type: 'PATCH',
                    }).done(function(){location.reload()});

            });

            $(".table-remove").click(function( event ) {
                event.preventDefault();
                var idGroupToDelete = $( this ).closest('.row-item').children('td.item-id').text();
                $.ajax({
                    url: "{{ url('api/v0/users/groups') }}"+"/"+idGroupToDelete,
                    type: 'DELETE',
                    }).done(function(){location.reload()});
            });     
    </script>
@endsection