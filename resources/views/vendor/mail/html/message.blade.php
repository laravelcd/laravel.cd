@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            <img src="{{ asset('images/laravelcd-icon.svg') }}" alt="{{ config('app.name') }}" style="width: 150px; height: auto;">   
        @endcomponent
    @endslot

    {{-- Body --}}
    {{ $slot }}

    {{-- Subcopy --}}
    @isset($subcopy)
        @slot('subcopy')
            @component('mail::subcopy')
                {{ $subcopy }}
            @endcomponent
        @endslot
    @endisset

    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            <span style="display: block; margin: 0px 0px 10px 0px">
                Vous recevez cet e-mail, car vous êtes devenu un membre précieux de la communauté Laravel RDC.
            </span>
            <span>
                © {{ date('Y') }} {{ config('app.name') }}.
                @lang('Tous droits reservés.')
            </span>
            <span style="display: block; margin: 10px 0px 0px 0px">Kinshasa - RDC.</span>
        @endcomponent
    @endslot
@endcomponent
