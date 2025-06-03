<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page with media gallery
     */
    public function index(Request $request)
    {
        // Get category from request if filtering
        $category = $request->get('category', 'all');
        
        // Base query
        $query = Post::with(['media', 'user'])
            ->withCount(['likes', 'comments'])
            ->where(function($q) {
                // Only get posts that have media or legacy file_url
                $q->whereHas('media')
                    ->orWhereNotNull('file_url');
            });
        
        // Apply category filter if not 'all'
        if ($category !== 'all') {
            switch ($category) {
                case 'video':
                case 'audio':
                case 'image':
                    // Filter by media type
                    $query->where(function($q) use ($category) {
                        $q->whereHas('media', function($mediaQuery) use ($category) {
                            $mediaQuery->where('file_type', $category);
                        })->orWhere('file_type', $category);
                    });
                    break;
                    
                case 'tugas-akhir':
                    // Filter by title or description containing "Tugas Akhir" or "TA"
                    $query->where(function($q) {
                        $q->where('title', 'like', '%tugas akhir%')
                          ->orWhere('title', 'like', '%TA %')
                          ->orWhere('description', 'like', '%tugas akhir%');
                    });
                    break;
                    
                case 'ui-ux':
                    $query->where(function($q) {
                        $q->where('title', 'like', '%ui%')
                          ->orWhere('title', 'like', '%ux%')
                          ->orWhere('title', 'like', '%user interface%')
                          ->orWhere('title', 'like', '%user experience%')
                          ->orWhere('description', 'like', '%ui%')
                          ->orWhere('description', 'like', '%ux%');
                    });
                    break;
                    
                // Add more category cases as needed
                case 'animasi':
                    $query->where(function($q) {
                        $q->where('title', 'like', '%animasi%')
                          ->orWhere('title', 'like', '%animation%')
                          ->orWhere('description', 'like', '%animasi%')
                          ->orWhere('description', 'like', '%animation%');
                    });
                    break;
                    
                case 'vr':
                    $query->where(function($q) {
                        $q->where('title', 'like', '%virtual reality%')
                          ->orWhere('title', 'like', '%VR%')
                          ->orWhere('description', 'like', '%virtual reality%')
                          ->orWhere('description', 'like', '%VR%');
                    });
                    break;
                    
                case 'ar':
                    $query->where(function($q) {
                        $q->where('title', 'like', '%augmented reality%')
                          ->orWhere('title', 'like', '%AR%')
                          ->orWhere('description', 'like', '%augmented reality%')
                          ->orWhere('description', 'like', '%AR%');
                    });
                    break;
                    
                case 'foto':
                    $query->where(function($q) {
                        $q->whereHas('media', function($mediaQuery) {
                            $mediaQuery->where('file_type', 'image');
                        })->orWhere(function($subQ) {
                            $subQ->where('file_type', 'image')
                                 ->orWhere('title', 'like', '%foto%')
                                 ->orWhere('title', 'like', '%photo%')
                                 ->orWhere('title', 'like', '%photography%');
                        });
                    });
                    break;
            }
        }
        
        // Get posts with pagination or limit
        $posts = $query->latest()
            ->limit(20) // Show 20 posts
            ->get();
        
        // Add a virtual 'category' attribute to each post for frontend filtering
        $posts->each(function($post) {
            $post->category = $this->determineCategory($post);
        });

        return view('welcome', compact('posts', 'category'));
    }
    
    /**
     * Determine the category of a post based on its content
     */
    private function determineCategory($post)
    {
        $title = strtolower($post->title ?? '');
        $description = strtolower($post->description ?? '');
        $content = $title . ' ' . $description;
        
        // Check media type first
        $mediaType = null;
        if ($post->media->first()) {
            $mediaType = $post->media->first()->file_type;
        } elseif ($post->file_type) {
            $mediaType = $post->file_type;
        }
        
        // Priority order for category determination
        if (str_contains($content, 'tugas akhir') || str_contains($content, ' ta ')) {
            return 'tugas-akhir';
        }
        if (str_contains($content, 'ui') || str_contains($content, 'ux') || 
            str_contains($content, 'user interface') || str_contains($content, 'user experience')) {
            return 'ui-ux';
        }
        if (str_contains($content, 'animasi') || str_contains($content, 'animation')) {
            return 'animasi';
        }
        if (str_contains($content, 'virtual reality') || str_contains($content, 'vr')) {
            return 'vr';
        }
        if (str_contains($content, 'augmented reality') || str_contains($content, 'ar')) {
            return 'ar';
        }
        if (str_contains($content, 'nirmana')) {
            return 'nirmana';
        }
        if (str_contains($content, 'gambar berulak') || str_contains($content, 'pattern')) {
            return 'gambar-berulak';
        }
        
        // Fall back to media type
        if ($mediaType === 'video') return 'video';
        if ($mediaType === 'audio') return 'audio';
        if ($mediaType === 'image' || 
            str_contains($content, 'foto') || 
            str_contains($content, 'photo') || 
            str_contains($content, 'photography')) {
            return 'foto';
        }
        
        return 'all';
    }
}
