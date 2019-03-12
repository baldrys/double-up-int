@extends('layouts.app')

@section('content')
@include('layouts.tableLayout', [
    'tableTitle' => 'User profiles',
    'itemName' => 'profile',
    'items' => $profiles
    ])


    <script>
            $("#addForm").submit(function( event ) {
                event.preventDefault();
                var $form = $( this );
                var profileName = $form.find( "input[name='name']" ).val();
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}",
                    data: { name: profileName },
                    type: 'POST',
                    }).done(function(){location.reload()});
            });


            $(".table-save").click(function( event ) {
                event.preventDefault();
                var nameToSave = $( this ).closest('.row-item').children('td.item-name').text();
                var iDProfileToSave = $( this ).closest('.row-item').children('td.item-id').text();
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}"+"/"+iDProfileToSave,
                    data: { name: nameToSave },
                    type: 'PATCH',
                    }).done(function(){location.reload()});

            });

            $(".table-remove").click(function( event ) {
                event.preventDefault();
                console.log(iDProfileToDelete);
                var iDProfileToDelete = $( this ).closest('.row-item').children('td.item-id').text();
                
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}"+"/"+iDProfileToDelete,
                    type: 'DELETE',
                    }).done(function(){location.reload()});
            });

            
    </script>


@endsection