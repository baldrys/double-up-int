@extends('emails.layouts.base')
@section('content')

    <p>Пользователь с email {{$admin->email}} обновил информацию пользователя с id: {{$oldUser->id}}</p>

    @if ($oldUser->name != $newUser->name)
        <p>Имя: {{ $oldUser->name }} => {{ $newUser->name }} </p>
    @else
        <p>Имя: {{ $oldUser->name }}</p>
    @endif

    @if ($oldUser->role != $newUser->role)
        <p>Роль: {{($oldUser->role == 'User')?('Пользователь'):('Администратор')}} => {{($newUser->role == 'User')?('Пользователь'):('Администратор')}}</p>
    @else
        <p>Роль: {{($oldUser->role == 'User')?('Пользователь'):('Администратор')}}</p>
    @endif


    @if ($newUser->banned)
        <p>Пользователь забанен!</p>
    @endif

@endsection