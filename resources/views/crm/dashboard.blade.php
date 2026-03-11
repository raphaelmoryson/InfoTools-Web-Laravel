@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Rendez-vous à venir</h1>
    @if($appointments->isEmpty())
        <p>Aucun rendez-vous prévu.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Client</th>
                    <th>Objet</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appt)
                    <tr>
                        <td>{{ $appt->date->format('d/m/Y H:i') }}</td>
                        <td>{{ $appt->customer->name ?? 'Non assigné' }}</td>
                        <td>{{ $appt->subject }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
