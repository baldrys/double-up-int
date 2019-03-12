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
        saveOnClick("{{ url('api/v0/users/group') }}");
        deleteOnClick("{{ url('api/v0/users/groups') }}"); 
    </script>   
@endsection