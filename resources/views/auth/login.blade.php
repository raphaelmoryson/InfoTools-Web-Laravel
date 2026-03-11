@extends('layouts.app')

@section('title', 'Connexion')

@section('content')
<div class="container py-5" style="max-width:480px;">
    <h1 class="h4 mb-4 text-center">Connexion CRM</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="test@infotools.local" required autofocus>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" value="password123" required>
        </div>

        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
    </form>

    <p class="mt-3 text-center text-muted small">
        Accès réservé aux commerciaux InfoTools.
    </p>
</div>
@endsection
