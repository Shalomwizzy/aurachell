@extends('layouts.admin')
@section('title', $title ?? 'Admin')
@section('content')
<div class="p-8">
    <h1 class="text-2xl font-semibold text-[var(--adm-text)] mb-2">{{ $title ?? 'Admin' }}</h1>
    <p class="text-text-muted">This section is under construction.</p>
</div>
@endsection
