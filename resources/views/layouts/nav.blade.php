<nav class="navbar navbar-expand-lg navbar-light bg-light">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        {{-- <a class="navbar-brand" href="#">Navbar</a> --}}
      
        <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
          <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a class="nav-link" href="#">Профили пользователей</a>
            </li>
            {{-- <li class="nav-item">
              <a class="nav-link" href="#">Disabled</a>
            </li> --}}
          </ul>
          <form class="form-inline my-2 my-lg-0"  id="searchByIdUserForm">
            <input class="form-control mr-sm-2" name="IdUserToSearch" type="search" placeholder="id пользователя" aria-label="Search">
            <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Искать</button>
          </form>
        </div>
      </nav>
<script>
    $("#searchByIdUserForm").submit(function( event ) {
        event.preventDefault();
        var $form = $( this );
        var IdUserToSearch = $form.find( "input[name='IdUserToSearch']" ).val();
        $(location).attr('href', "{{ url('groups') }}"+"/"+IdUserToSearch)
        console.log(IdUserToSearch);
    });

</script>      