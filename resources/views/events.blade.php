<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tədbirlər - Hyper Drive</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Exo+2:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('components.navigation')

    <!-- Page Header -->
    <section class="page-header">
        <div class="container">
            <h1 class="page-title">
                <span class="glitch" data-text="{{ $settings->page_title }}">{{ $settings->page_title }}</span>
            </h1>
            <p class="page-subtitle">{{ $settings->page_subtitle }}</p>
        </div>
    </section>

    <!-- Events Filter -->
    <section class="events-filter">
        <div class="container">
            <div class="filter-buttons">
                <button class="filter-btn active" data-filter="all">{{ $settings->filter_all_text }}</button>
                <button class="filter-btn" data-filter="racing">{{ $settings->filter_racing_text }}</button>
                <button class="filter-btn" data-filter="meet">{{ $settings->filter_meet_text }}</button>
                <button class="filter-btn" data-filter="drift">{{ $settings->filter_drift_text }}</button>
            </div>
        </div>
    </section>

    <!-- Events Grid -->
    <section class="events-section">
        <div class="container">
            <div class="events-grid">
                @foreach($settings->events as $event)
                <div class="event-card-detailed" data-category="{{ $event['category'] }}">
                    <div class="event-image">
                        <img src="{{ $event['image'] }}" alt="{{ $event['title'] }}">
                        @if($event['is_featured'])
                        <div class="event-status featured">Önə Çıxan</div>
                        @endif
                        <div class="event-date">{{ $event['date'] }}</div>
                    </div>
                    <div class="event-content">
                        <div class="event-category">
                            @switch($event['category'])
                                @case('meet')
                                    {{ $settings->filter_meet_text }}
                                    @break
                                @case('racing')
                                    {{ $settings->filter_racing_text }}
                                    @break
                                @case('drift')
                                    {{ $settings->filter_drift_text }}
                                    @break
                                @default
                                    {{ ucfirst($event['category']) }}
                            @endswitch
                        </div>
                        <h3>{{ $event['title'] }}</h3>
                        <p class="event-description">{{ $event['description'] }}</p>
                        <div class="event-details">
                            <div class="detail-item">
                                <span class="detail-icon">📍</span>
                                <span>{{ $event['location'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">🕒</span>
                                <span>{{ $event['time'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">👥</span>
                                <span>{{ $event['participants'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-icon">💰</span>
                                <span>{{ $event['price'] }}</span>
                            </div>
                        </div>
                        <div class="event-actions">
                            <a href="{{ $event['registration_url'] }}" class="btn-primary">Qeydiyyat</a>
                            <a href="{{ $event['details_url'] }}" class="btn-secondary">Təfərrüatlar</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    @include('components.footer')

    <script src="{{ asset('script.js') }}"></script>
    <script src="{{ asset('events.js') }}"></script>
</body>
</html> 