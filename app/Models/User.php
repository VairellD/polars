<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'bio',
        'url',
        'profile_picture',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    /**
     * Get the likes for the user.
     */
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Get the comments for the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

  /**
 * Get the user's profile picture or default avatar if none exists.
 *
 * @return string
 */
public function getProfilePictureUrlAttribute()
{
    if (!empty($this->profile_picture)) {
        // Extract the filename from the full path
        $pathParts = explode('/', $this->profile_picture);
        $filename = end($pathParts);
        
        // Return the route to our controller
        return route('profile.picture', $filename);
    }
    
    return asset('assets/default-avatar.png');
}

    
}
