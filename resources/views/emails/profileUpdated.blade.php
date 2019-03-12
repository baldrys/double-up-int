@extends('emails.layouts.base')
@section('content')
    <p>Обновился профиль с id: {{ $oldProfile->id }}:</p>
    <p>{{ $oldProfile->name }} => {{ $newProfile->name }}</p>
@endsection