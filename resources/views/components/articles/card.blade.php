@props(['article'])

<div class="space-y-6">
    <a href="{{ route('articles.show', $article) }}" class="group">
        <div class="aspect-w-3 aspect-h-2 lg:aspect-w-3 lg:aspect-h-1">
            <img class="object-cover shadow-lg rounded-lg group-hover:opacity-75" src="{{ $article->getFirstMediaUrl('media') }}" alt="{{ $article->title }}" />
        </div>
    </a>
    <div>
        <div>
            <time datetime="{{ $article->created_at->format('Y-m-d') }}" class="font-sans text-sm leading-5 text-skin-base capitalize">
                {{ $article->created_at->isoFormat('LL') }}
            </time>
            <a href="{{ route('articles.show', $article) }}" class="mt-2 flex items-center justify-between group">
                <h4 class="text-lg leading-6 font-semibold font-sans text-skin-inverted group-hover:text-skin-primary">{{ $article->title }}</h4>
                <x-heroicon-o-external-link class="ml-2.5 h-5 w-5 text-skin-base" />
            </a>
            <p class="mt-1 font-normal text-skin-base leading-6">
                {!! $article->excerpt(120) !!}
            </p>
        </div>
        @if ($article->tags->isNotEmpty())
            <div class="mt-4 flex items-center space-x-2">
                @foreach ($article->tags as $tag)
                    <x-tag :tag="$tag" />
                @endforeach
            </div>
        @endif
    </div>
</div>