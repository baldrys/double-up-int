@extends('layouts.app')

@section('content')
@include('layouts.tableLayout', [
    'tableTitle' => 'User profiles',
    'itemName' => 'profile',
    'items' => $profiles
    ])
  <script>
      addItemWithForm("{{ url('api/v0/users/profile') }}");
      saveOnClick("{{ url('api/v0/users/profile') }}");
      deleteOnClick("{{ url('api/v0/users/profile') }}"); 
  </script>
@endsection