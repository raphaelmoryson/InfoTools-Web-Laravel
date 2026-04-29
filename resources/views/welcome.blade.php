@extends('layouts.app')
@section('title', 'Tableau de bord')

@section('content')
    <div class="container-fluid py-4 bg-light">

        {{-- En-tête avec actions rapides --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
            <div>
                <p class="text-muted mb-0">Bienvenue, voici ce qui se passe aujourd'hui.</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-white bg-white border shadow-sm dropdown-toggle" type="button"
                        data-bs-toggle="dropdown">
                        <i class="bi bi-plus-lg me-2"></i>Créer
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                        <li><a class="dropdown-item" href="{{ route('appointments.create') }}"><i
                                    class="bi bi-calendar-event me-2 text-primary"></i>Rendez-vous</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.create') }}"><i
                                    class="bi bi-person-plus me-2 text-success"></i>Client</a></li>
                    </ul>
                </div>
                <a href="{{ route('appointments.create') }}" class="btn btn-primary shadow-sm">
                    <i class="bi bi-calendar-plus me-2"></i>Nouveau RDV
                </a>
            </div>
        </div>

        {{-- KPIs Modernisés --}}
        @php
            $kpis = $kpis ?? ['rdv_today' => 0, 'rdv_week' => 0, 'clients_new' => 0, 'revenue_week' => 0.0];
        @endphp

        <div class="row g-3 mb-4">
            {{-- KPI Card Component --}}
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase ls-1">RDV Aujourd'hui</div>
                                <div class="h2 fw-bold text-dark mt-2 mb-0">{{ $kpis['rdv_today'] }}</div>
                            </div>
                            <div class="icon-shape bg-primary bg-opacity-10 text-primary rounded-3 p-2">
                                <i class="bi bi-calendar-event fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-primary"
                                    style="width: {{ min(100, $kpis['rdv_today'] * 20) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase ls-1">RDV Semaine</div>
                                <div class="h2 fw-bold text-dark mt-2 mb-0">{{ $kpis['rdv_week'] }}</div>
                            </div>
                            <div class="icon-shape bg-info bg-opacity-10 text-info rounded-3 p-2">
                                <i class="bi bi-calendar-week fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-info" style="width: {{ min(100, $kpis['rdv_week'] * 10) }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase ls-1">Nouveaux Clients</div>
                                <div class="h2 fw-bold text-dark mt-2 mb-0">{{ $kpis['clients_new'] }}</div>
                            </div>
                            <div class="icon-shape bg-success bg-opacity-10 text-success rounded-3 p-2">
                                <i class="bi bi-person-check fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-success"
                                    style="width: {{ min(100, $kpis['clients_new'] * 10) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card border-0 shadow-sm h-100 card-hover-effect">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="text-muted small fw-semibold text-uppercase ls-1">CA Semaine</div>
                                <div class="h2 fw-bold text-dark mt-2 mb-0">
                                    {{ number_format($kpis['revenue_week'], 0, ',', ' ') }} €
                                </div>
                            </div>
                            <div class="icon-shape bg-warning bg-opacity-10 text-warning rounded-3 p-2">
                                <i class="bi bi-wallet2 fs-4"></i>
                            </div>
                        </div>
                        <div class="mt-3">
                            <div class="progress" style="height: 4px;">
                                <div class="progress-bar bg-warning"
                                    style="width: {{ min(100, ($kpis['revenue_week'] / 5000) * 100) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section Calendrier et Recherche --}}
        <div class="row g-4">
            {{-- Calendrier Principal --}}
            <div class="col-12 col-xl-9">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <h5 class="mb-0 fw-bold"><i class="bi bi-calendar3 me-2 text-primary"></i>Planning</h5>
                            {{-- Légende rapide --}}
                            <div class="d-none d-md-flex gap-2 ms-3 small">
                                <span
                                    class="badge rounded-pill text-bg-success bg-opacity-10 text-success border border-success border-opacity-25">Confirmé</span>
                                <span
                                    class="badge rounded-pill text-bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25">En
                                    attente</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-light border" id="btn-refresh-calendar" title="Actualiser">
                            <i class="bi bi-arrow-clockwise"></i>
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div id="appointments-calendar"></div>
                    </div>
                </div>
            </div>

            {{-- Sidebar de droite : Recherche et Tâches rapides --}}
            <div class="col-12 col-xl-3">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">Recherche rapide</h6>
                        <form action="#" method="GET">
                            <div class="input-group mb-2">
                                <span class="input-group-text bg-light border-end-0"><i
                                        class="bi bi-search text-muted"></i></span>
                                <input type="search" class="form-control bg-light border-start-0"
                                    placeholder="Client, Facture...">
                            </div>
                            <select class="form-select form-select-sm bg-light mb-2">
                                <option value="">Tout filtrer</option>
                                <option value="clients">Clients</option>
                                <option value="rdv">Rendez-vous</option>
                            </select>
                            <button class="btn btn-primary w-100 btn-sm">Rechercher</button>
                        </form>
                    </div>
                </div>

                {{-- Prochains RDV liste simple (pour le coup d'œil) --}}
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">Prochainement</h6>
                    </div>
                    <div class="list-group list-group-flush">
                        @forelse(collect($upcomingAppointments)->take(4) as $rdv)
                            <div class="list-group-item border-0 px-3 py-2">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 text-center rounded p-2 bg-light border" style="width: 50px;">
                                        <div class="small text-uppercase fw-bold text-muted">
                                            {{ \Carbon\Carbon::parse($rdv->start_at)->translatedFormat('M') }}
                                        </div>
                                        <div class="h5 mb-0 fw-bold">{{ \Carbon\Carbon::parse($rdv->start_at)->format('d') }}
                                        </div>
                                    </div>
                                    <div class="ms-3 flex-grow-1 overflow-hidden">
                                        <h6 class="mb-0 text-truncate" title="{{ $rdv->customer->name }}"
                                        </h6>
                                        <small class="text-muted d-block text-truncate">
                                            {{ \Carbon\Carbon::parse($rdv->start_at)->format('H:i') }} •
                                            {{ $rdv->subject ?? 'Rendez-vous' }}
                                        </small>
                                        {{-- Badge avec le nom du commercial --}}
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border mt-1"
                                            style="font-size: 0.7rem;">
                                            <i class="bi bi-person me-1"></i>{{ $rdv->commercial_name }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-3 text-muted small">Aucun RDV proche</div>
                        @endforelse
                    </div>
                    <div class="card-footer bg-white text-center border-0">
                        <a href="{{ route('appointments.index') }}" class="small text-decoration-none fw-semibold">Voir tout
                            le planning</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Détails RDV --}}
    <div class="modal fade" id="eventModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="eventModalTitle">Détails</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 text-primary me-3">
                            <i class="bi bi-person-fill fs-4"></i>
                        </div>
                        <div>
                            <h4 class="mb-0 fw-bold" id="eventModalClient">Client Name</h4>
                            <span class="badge bg-secondary" id="eventModalStatus">Statut</span>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Date</label>
                            <p class="mb-0 fw-medium" id="eventModalDate">--</p>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted text-uppercase fw-bold">Heure</label>
                            <p class="mb-0 fw-medium" id="eventModalTime">--</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Sujet</label>
                            <p class="mb-0" id="eventModalSubject">--</p>
                        </div>
                        <div class="col-12">
                            <label class="small text-muted text-uppercase fw-bold">Lieu / Entreprise</label>
                            <p class="mb-0 text-muted" id="eventModalLocation">--</p>
                        </div>
                        {{-- Affichage du commercial dans la modale --}}
                        <div class="col-12 mt-3 pt-3 border-top">
                            <label class="small text-muted text-uppercase fw-bold">Commercial assigné</label>
                            <p class="mb-0 fw-bold text-primary" id="eventModalCommercial">--</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <a href="#" id="eventLinkBtn" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-pencil-square me-2"></i>Modifier / Voir fiche
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Styles CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/main.min.css" rel="stylesheet">
    <style>
        .ls-1 {
            letter-spacing: 0.5px;
        }

        .card-hover-effect {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover-effect:hover {
            transform: translateY(-3px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        #appointments-calendar {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            padding: 1rem;
            min-height: 600px;
        }

        .fc .fc-toolbar.fc-header-toolbar {
            margin-bottom: 1.5rem;
        }

        .fc .fc-button-primary {
            background-color: white;
            border: 1px solid #dee2e6;
            color: #495057;
            font-weight: 600;
            text-transform: capitalize;
            padding: 0.4rem 1rem;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        }

        .fc .fc-button-primary:hover {
            background-color: #f8f9fa;
            border-color: #cdd4da;
            color: #212529;
        }

        .fc .fc-button-primary:not(:disabled).fc-button-active {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            color: white;
        }

        .fc .fc-toolbar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #343a40;
        }

        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #f1f3f5;
        }

        .fc .fc-col-header-cell-cushion {
            color: #868e96;
            text-transform: uppercase;
            font-size: 0.75rem;
            padding: 10px 0;
        }

        .fc .fc-daygrid-day-number {
            color: #495057;
            font-weight: 500;
            padding: 8px 12px;
        }

        .fc .fc-day-today {
            background-color: transparent !important;
        }

        .fc .fc-day-today .fc-daygrid-day-number {
            background: var(--bs-primary);
            color: white;
            border-radius: 50%;
            width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 4px;
        }

        .fc-event {
            border: none;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
        }

        .fc-event:hover {
            transform: scale(1.02);
            filter: brightness(0.95);
        }

        .fc-event-main-content {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 2px 4px;
            font-size: 0.8rem;
        }

        .fc-event-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: white;
            flex-shrink: 0;
        }

        .fc-event-time {
            font-weight: 600;
            opacity: 0.9;
            font-size: 0.75rem;
        }

        .fc-event-title {
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .bg-status-confirmed {
            background-color: #d1e7dd !important;
            border-left: 3px solid #198754 !important;
            color: #0f5132 !important;
        }

        .bg-status-confirmed .fc-event-dot {
            background-color: #198754;
        }

        .bg-status-pending {
            background-color: #fff3cd !important;
            border-left: 3px solid #ffc107 !important;
            color: #664d03 !important;
        }

        .bg-status-pending .fc-event-dot {
            background-color: #ffc107;
        }

        .bg-status-cancelled {
            background-color: #f8d7da !important;
            border-left: 3px solid #dc3545 !important;
            color: #842029 !important;
        }

        .bg-status-cancelled .fc-event-dot {
            background-color: #dc3545;
        }

        .bg-status-default {
            background-color: #cfe2ff !important;
            border-left: 3px solid #0d6efd !important;
            color: #084298 !important;
        }

        .bg-status-default .fc-event-dot {
            background-color: #0d6efd;
        }
    </style>

    {{-- Scripts JS --}}
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/locales/fr.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('appointments-calendar');
            const modalEl = document.getElementById('eventModal');
            const modal = new bootstrap.Modal(modalEl);

            const getStatusClass = (status) => {
                switch (status) {
                    case 'confirmé': return 'bg-status-confirmed';
                    case 'en attente': return 'bg-status-pending';
                    case 'annulé': return 'bg-status-cancelled';
                    default: return 'bg-status-default';
                }
            };

            const getStatusBadgeClass = (status) => {
                switch (status) {
                    case 'confirmé': return 'bg-success';
                    case 'en attente': return 'bg-warning text-dark';
                    case 'annulé': return 'bg-danger';
                    default: return 'bg-primary';
                }
            };

            const events = [
                @foreach(($upcomingAppointments ?? []) as $rdv)
                            {
                        id: "{{ $rdv->id ?? uniqid() }}",
                        title: @json($rdv->customer->name ?? 'Client inconnu'),
                        start: @json(\Carbon\Carbon::parse($rdv->start_at)->toIso8601String()),
                        end: @json(!empty($rdv->end_at) ? \Carbon\Carbon::parse($rdv->end_at)->toIso8601String() : null),
                        classNames: [getStatusClass(@json($rdv->status ?? 'prévu'))],
                        extendedProps: {
                            subject: @json($rdv->subject ?? 'Rendez-vous'),
                            status: @json($rdv->status ?? 'prévu'),
                            client: @json($rdv->customer->name ?? ''),
                            company: @json($rdv->client_company ?? null),
                            location: @json($rdv->location ?? 'Au cabinet'),
                            commercial: @json($rdv->commercial_name), // Récupération de la donnée
                            edit_url: "{{ isset($rdv->id) ? route('appointments.edit', $rdv->id) : '#' }}"
                        }
                    }@if (!$loop->last), @endif
                @endforeach
                ];

            const calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr',
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                buttonText: {
                    today: "Aujourd'hui",
                    month: 'Mois',
                    week: 'Semaine',
                    list: 'Liste'
                },
                navLinks: true,
                dayMaxEvents: 3,
                events: events,

                eventContent: function (arg) {
                    let timeText = arg.timeText;
                    if (!timeText) {
                        let date = new Date(arg.event.start);
                        timeText = date.getHours().toString().padStart(2, '0') + ':' + date.getMinutes().toString().padStart(2, '0');
                    }

                    let title = arg.event.title;
                    let customHtml = document.createElement('div');
                    customHtml.className = 'fc-event-main-content';
                    customHtml.innerHTML = `
                                <span class="fc-event-dot"></span>
                                <span class="fc-event-time">${timeText}</span>
                                <span class="fc-event-title">${title}</span>
                            `;
                    return { domNodes: [customHtml] };
                },

                eventClick: function (info) {
                    info.jsEvent.preventDefault();

                    const props = info.event.extendedProps;
                    const start = info.event.start;

                    document.getElementById('eventModalTitle').innerText = props.subject;
                    document.getElementById('eventModalClient').innerText = props.client;

                    const badge = document.getElementById('eventModalStatus');
                    badge.className = `badge ${getStatusBadgeClass(props.status)}`;
                    badge.innerText = props.status;

                    const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    document.getElementById('eventModalDate').innerText = start.toLocaleDateString('fr-FR', dateOptions);
                    document.getElementById('eventModalTime').innerText = start.toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });

                    document.getElementById('eventModalSubject').innerText = props.subject;
                    let loc = props.location;
                    if (props.company) loc += ` (${props.company})`;
                    document.getElementById('eventModalLocation').innerText = loc;

                    // Affichage du nom du commercial dans la modale
                    document.getElementById('eventModalCommercial').innerText = props.commercial;

                    document.getElementById('eventLinkBtn').href = props.edit_url;

                    modal.show();
                }
            });

            calendar.render();

            document.getElementById('btn-refresh-calendar').addEventListener('click', function () {
                window.location.reload();
            });
        });
    </script>
@endsection