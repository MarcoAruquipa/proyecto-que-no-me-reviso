@php
    $logoFile = file_exists(public_path('img/logo.png')) ? asset('img/logo.png') : null;
@endphp

@if($logoFile)
    <img src="{{ $logoFile }}" alt="Pegaso Motors" class="pegaso-logo {{ $size ?? 'md' }}">
@else
    <div class="pegaso-marca {{ $size ?? 'md' }}">
        <span class="pegaso-monograma">P</span>
        <span class="pegaso-letras">
            <strong>PEGASO MOTORS</strong>
            <small>Movilidades y Crédito</small>
        </span>
    </div>
@endif

<style>
    .pegaso-logo { border-radius: 12px; }

    .pegaso-logo.sm { width: 38px; height: 38px; object-fit: cover; }
    .pegaso-logo.md { width: 72px; height: 72px; object-fit: cover; }
    .pegaso-logo.lg { width: 120px; height: 120px; object-fit: cover; }

    .pegaso-marca {
        display: inline-flex;
        align-items: center;
        gap: 12px;
    }

    .pegaso-monograma {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: linear-gradient(135deg, #d4af37, #f6e27a);
        color: #2b1552;
        font-weight: 900;
        font-size: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .35);
        flex-shrink: 0;
    }

    .pegaso-marca.sm { gap: 8px; }
    .pegaso-marca.sm .pegaso-monograma { width: 38px; height: 38px; font-size: 19px; }
    .pegaso-marca.sm strong { font-size: 13px; }
    .pegaso-marca.sm small { font-size: 10px; }

    .pegaso-marca.lg .pegaso-monograma { width: 84px; height: 84px; font-size: 42px; }
    .pegaso-marca.lg strong { font-size: 26px; letter-spacing: 2px; }
    .pegaso-marca.lg small { font-size: 15px; }

    .pegaso-marca strong {
        color: #fff;
        letter-spacing: 1.5px;
        display: block;
        line-height: 1.15;
        font-size: 17px;
    }

    .pegaso-marca small {
        color: #d4af37;
        font-size: 12px;
        display: block;
    }
</style>