<?php

declare(strict_types=1);

namespace App\Livewire\Pages;

use App\Models\Article;
use App\Models\Discussion;
use App\Models\Plan;
use App\Models\Thread;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

final class Home extends Component
{
    public function render(): View
    {
        $ttl = now()->addDays(2);

        // @phpstan-ignore-next-line
        seo()
            ->description(__('pages/home.description'))
            ->twitterDescription(__('pages/home.description'))
            ->image(asset('/images/socialcard.png'))
            ->twitterSite('laravelcd')
            ->withUrl();

        return view('livewire.pages.home', [
            'plans' =>  Plan::query()->developer()->get()
            ,
            'latestArticles' => Article::with(['tags', 'user', 'user.transactions']) // @phpstan-ignore-line
                    ->published()
                    ->orderByDesc('sponsored_at')
                    ->orderByDesc('published_at')
                    ->orderByViews()
                    ->trending()
                    ->limit(4)
                    ->get(),
            'latestThreads' => Thread::with(['user', 'user.transactions'])
                    ->whereNull('solution_reply_id')
                    ->whereBetween('threads.created_at', [now()->subMonths(3), now()])
                    ->inRandomOrder()
                    ->limit(4)
                    ->get()
            ,
            'latestDiscussions' => Discussion::with(['user', 'user.transactions']) // @phpstan-ignore-line
                    ->recent()
                    ->orderByViews()
                    ->limit(3)
                    ->get()
            ,
        ]);
    }
}
