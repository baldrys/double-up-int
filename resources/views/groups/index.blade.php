@extends('layouts.app')

@section('content')
@include('layouts.tableLayout', [
    'tableTitle' => 'User groups',
    'itemName' => 'group',
    'items' => $groups
    ])
    <script>
        addItemWithForm("{{ url('api/v0/users/group') }}");
        saveOnClick("{{ url('api/v0/users/group') }}");
        deleteOnClick("{{ url('api/v0/users/groups') }}"); 
    </script>
@endsection