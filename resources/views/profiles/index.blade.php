@extends('layouts.app')

@section('content')
<!-- Editable table -->
    <div class="card">
        <h3 class="card-header text-center font-weight-bold text-uppercase py-4">User profiles</h3>
        <div class="card-body">
          <div id="table" class="table-editable">
            <span class="table-add float-right mb-3 mr-2"><a href="#!" class="text-success"><i class="fas fa-plus fa-2x"
                  aria-hidden="true"></i></a></span>
            <table class="table table-bordered table-responsive-md table-striped text-center">
              <tr>
                <th class="text-center">Profile id</th>
                <th class="text-center">Profile</th>
                <th class="text-center">Save</th>
                <th class="text-center">Remove</th>
              </tr>



          @foreach ($profiles as $profile)
              <tr class="row-profile">
                <td class="pt-3-half profile-id" contenteditable="false">{{ $profile->id }}</td>
                <td class="pt-3-half profile-name" contenteditable="true">{{ $profile->name }}</td>
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
              <h4 class="card-title">Add profile</h4>
              <form id="addProfileForm">
                    <div class="form-group">
                        <input type="text" name="profileName" class="form-control" placeholder="Profile name" required="required" >
                    </div>
                    <button type="submit" class="btn btn-primary">add</button>
                </form>
          </div>
      </div>

    <script>
            $("#addProfileForm").submit(function( event ) {
                event.preventDefault();
                var $form = $( this );
                var profileName = $form.find( "input[name='profileName']" ).val();
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}",
                    data: { name: profileName },
                    type: 'POST',
                    }).done(function(){location.reload()});
            });


            $(".table-save").click(function( event ) {
                event.preventDefault();
                var nameToSave = $( this ).closest('.row-profile').children('td.profile-name').text();
                var iDProfileToSave = $( this ).closest('.row-profile').children('td.profile-id').text();
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}"+"/"+iDProfileToSave,
                    data: { name: nameToSave },
                    type: 'PATCH',
                    }).done(function(){location.reload()});

            });

            $(".table-remove").click(function( event ) {
                event.preventDefault();
                console.log(iDProfileToDelete);
                var iDProfileToDelete = $( this ).closest('.row-profile').children('td.profile-id').text();
                
                $.ajax({
                    url: "{{ url('api/v0/users/profile') }}"+"/"+iDProfileToDelete,
                    type: 'DELETE',
                    }).done(function(){location.reload()});
            });

            
    </script>


@endsection