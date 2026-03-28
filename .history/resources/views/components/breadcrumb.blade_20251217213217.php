{{-- Optimized Breadcrumb Component --}}
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{ route('rutarrr1') }}">
                <i class="fas fa-home"></i>
                <span>Inicio</span>
            </a>
        </li>
        @foreach($items ?? [] as $item)
            @if($loop->last)
                <li class="breadcrumb-item active" aria-current="page">
                    @if(isset($item['icon'])) <i class="{{ $item['icon'] }}"></i> @endif
                    <span>{{ $item['label'] }}</span>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ $item['url'] ?? '#' }}">
                        @if(isset($item['icon'])) <i class="{{ $item['icon'] }}"></i> @endif
                        <span>{{ $item['label'] }}</span>
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>

<style>
.breadcrumb-nav {
    background: #fff;
    border-radius: 8px;
    padding: 0.75rem 1rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    border: 1px solid #e9ecef;
}

.breadcrumb {
    background: transparent;
    margin: 0;
    padding: 0;
    font-size: 0.875rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.breadcrumb-item + .breadcrumb-item::before {
    content: "→";
    color: #6c757d;
    font-weight: 600;
    margin: 0 0.25rem;
}

.breadcrumb-item a {
    color: #0d6efd;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s ease;
}

.breadcrumb-item a:hover {
    color: #0b5ed7;
    text-decoration: underline;
}

.breadcrumb-item.active {
    color: #6c757d;
    font-weight: 600;
}

.breadcrumb-item i {
    font-size: 0.75rem;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .breadcrumb-nav {
        padding: 0.5rem 0.75rem;
    }

    .breadcrumb {
        font-size: 0.8rem;
    }

    .breadcrumb-item span {
        display: none;
    }

    .breadcrumb-item.active span {
        display: inline;
    }
}
</style>
