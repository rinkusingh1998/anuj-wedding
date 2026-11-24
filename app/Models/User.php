<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Admin panel ka access.
     *
     * Admins ko poora access milta hai. Editors content manage kar sakte hain,
     * lekin sensitive screens (users, global settings, redirects, business
     * profile, sitemap settings) sirf admins ke liye hain.
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin'
            && in_array($this->role, ['admin', 'editor'], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
