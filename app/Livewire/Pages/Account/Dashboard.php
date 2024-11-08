<?php

declare(strict_types=1);

namespace App\Livewire\Pages\Account;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * @property-read User $user
 */
final class Dashboard extends Component
{
    #[Computed]
    public function user(): User
    {
        return User::with('articles')
            ->scopes('withCounts')
            ->find(Auth::id());
    }

    public function render(): View
    {
        return view('livewire.pages.account.dashboard', [
            'articles' => $this->user->articles()
                ->orderByDesc('created_at')
                ->orderBy('submitted_at')
                ->paginate(12),
        ])->title(__('pages/account.dashboard.title', ['username' => $this->user->username]));
    }
}