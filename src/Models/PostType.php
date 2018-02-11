<?php
/**
 * Created by PhpStorm.
 * User: nico
 * Date: 11/02/2018
 * Time: 09:26
 */

namespace NGiraud\PostType\Models;


use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use NGiraud\PostType\Interfaces\PostType as PostTypeInterface;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

abstract class PostType extends Model implements PostTypeInterface
{
    use SoftDeletes, HasSlug;

    protected $guarded = ['id'];

    protected $dates = ['deleted_at', 'published_at'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($item) {
            if (Auth::check()) {
                $item->user_id = Auth::id();
            }

            $item->published_at = null;
            if ($item->status == static::STATUS_PUBLISHED) {
                $item->published_at = Carbon::now();
            }
        });

        static::addGlobalScope('published', function ($builder) {
            $builder
                ->where('status', static::STATUS_PUBLISHED)
                ->whereNotNull('published_at');
        });
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug')
            ->slugsShouldBeNoLongerThan(50)
            ->usingSeparator('-')
            ->usingLanguage(config('app.locale'))
            ->doNotGenerateSlugsOnUpdate();
    }

    public function rules()
    {
        return [
            'name' => 'required|min:2|max:255',
            'content' => 'nullable',
            'excerpt' => 'nullable',
            'published_at' => 'nullable|date',
            'parent_id' => 'nullable|integer',
            'status' => $this->ruleStatus()
        ];
    }

    public function ruleStatus()
    {
        return 'in:' . implode(',', [static::STATUS_DRAFT, static::STATUS_PUBLISHED]);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function parent()
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(static::class, 'parent_id');
    }
}