@extends('layouts.app')

@section('content')
    <h3>Список групп пользователя {{$user->name }}: </h3>
    <table class="table">
            <thead>
              <tr>
                <th >#</th>
                <th >Название группы</th>
                <th ></th>
              </tr>
            </thead>
            <tbody>

            @foreach ($groups as $group)
                <tr class="rowGroup">
                    <th scope="row" >{{ $group->id }}</th>
                    <td>{{ $group->name }}</td>
                    <td class="text-right">
                        <div class="deleteGroup">
                            <button type="button" class="btn btn-danger">удалить</button>
                        </div>
                    </td> 
                </tr>
            @endforeach


            </tbody>
          </table>

          
    <h3>Форма для добавления группы</h3>
        <form id="createGroupForm">
            <div class="form-group">
                <input type="text" name="groupName" class="form-control" placeholder="введите название группы" required="required" >
            </div>
            <button type="submit" class="btn btn-primary">Добавить группу</button>
        </form>

    <script>
            $("#createGroupForm").submit(function( event ) {
                event.preventDefault();
                var $form = $( this );
                var groupName = $form.find( "input[name='groupName']" ).val();
                var addGroupPost = $.post( "{{ url('api/v0/users/group') }}", { name: groupName } );
                addGroupPost.done(function(data) {
                    var newGroupId = data.data.created_group.id;
                    var userId = {{$user->id }};
                    addGroupToUserPost = $.post( "{{ url('api/v0/user/'.$user->id.'/group/') }}"+"/"+newGroupId, {});
                    addGroupToUserPost.done(function() {
                        location.reload();
                    });
                });
            });


            $(".deleteGroup").click(function( event ) {
                event.preventDefault();
                var idGroupToDelete = $( this ).closest('.rowGroup').children('th').text();
                $.ajax({
                    url: "{{ url('api/v0/users/groups') }}"+"/"+idGroupToDelete,
                    type: 'DELETE',
                    }).done(function(){location.reload()});
            });



            
    </script>


@endsection