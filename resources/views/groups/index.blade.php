@extends('layouts.app')

@section('content')
    <!-- Editable table -->
    <div class="card">
        <h3 class="card-header text-center font-weight-bold text-uppercase py-4">{{$user->name }}'s groups</h3>
        <div class="card-body">
            <div id="table" class="table-editable">
            <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i class="fas fa-plus fa-2x"
                    aria-hidden="true"></i></a></span>
            <table class="table table-bordered table-responsive-md table-striped text-center">
                <tr>
                <th class="text-center">Gruop id</th>
                <th class="text-center">Gruop name</th>
                <th class="text-center">Save</th>
                <th class="text-center">Remove</th>
                </tr>

            @foreach ($groups as $group)
                <tr class="row-group">
                <td class="pt-3-half group-id" contenteditable="false">{{ $group->id }}</td>
                <td class="pt-3-half group-name" contenteditable="true">{{ $group->name }}</td>
                <td>
                    <span class="table-save"><button type="button" class="btn btn-primary btn-rounded btn-sm my-0">Save</button></span>
                </td>
                <td>
                    <span class="table-remove"><button type="button" class="btn btn-danger btn-rounded btn-sm my-0">Remove</button></span>
                </td>
                </tr>
            @endforeach
                
            </table>
            </div>
        </div>
        </div>
        <!-- Editable table -->
        <div class="card">
            
            <div class="card-body">
                <h4 class="card-title">Add gruop</h4>
                <form id="addGroupForm">
                    <div class="form-group">
                        <input type="text" name="groupName" class="form-control" placeholder="Gruop name" required="required" >
                    </div>
                    <button type="submit" class="btn btn-primary">add</button>
                </form>
            </div>
        </div>
        
    <script>
            $("#addGroupForm").submit(function( event ) {
                event.preventDefault();
                var $form = $( this );
                var groupName = $form.find( "input[name='groupName']" ).val();
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
                var nameToSave = $( this ).closest('.row-group').children('td.group-name').text();
                var iDGroupToSave = $( this ).closest('.row-group').children('td.group-id').text();
                $.ajax({
                    url: "{{ url('api/v0/users/group') }}"+"/"+iDGroupToSave,
                    data: { name: nameToSave },
                    type: 'PATCH',
                    }).done(function(){location.reload()});

            });

            $(".table-remove").click(function( event ) {
                event.preventDefault();
                var idGroupToDelete = $( this ).closest('.row-group').children('td.group-id').text();
                console.log(idGroupToDelete);
                $.ajax({
                    url: "{{ url('api/v0/users/groups') }}"+"/"+idGroupToDelete,
                    type: 'DELETE',
                    }).done(function(){location.reload()});
            });     
    </script>
@endsection