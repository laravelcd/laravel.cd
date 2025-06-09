@props([
    'title',
])

<x-container class="py-12 sm:py-16 lg:py-24">
    <p class="text-center text-lg font-medium leading-8 text-gray-700 dark:text-gray-300">
        {{ $title }}
    </p>
    <div class="mt-5 flex flex-wrap items-center justify-center gap-8">
        <div class="flex items-center justify-center px-2">
            <a href="https://letecode.com" target="_blank" class="flex items-center">
                <img
                    class="h-12 dark:hidden"
                    src="{{ asset('/images/sponsors/letecode-logo.png') }}"
                    alt="Letecode Academy"
                />
                <img
                    class="hidden h-12 dark:block"
                    src="{{ asset('/images/sponsors/letecode-logo-light.png') }}"
                    alt="Letecode Academy"
                />
            </a>
        </div>
      
        
    </div>
    <div class="mt-6 text-center lg:mt-10">
        <x-link
            class="text-sm leading-5 text-flag-green hover:text-green-600 hover:underline"
            target="_blank"
            :href="route('sponsors')"
        >
            {{ __('pages/home.view_logo_question') }}
        </x-link>
    </div>
</x-container>
