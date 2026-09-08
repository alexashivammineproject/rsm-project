<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cviebrock\EloquentSluggable\SluggableScopeHelpers;

class Product extends Model
{
    use Sluggable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title', 'slug', 'image', 'alt_img', 'excerpt', 'description', 'views', 'user_id', 'is_active', 'allow_comments', 
        'meta_title', 'meta_keywords', 'meta_description', 'new_launches', 'hot_offers'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
            ]
        ];
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Custome scope for Active blogs.
     *
     * @return string
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    /**
     * Custome scope for Like Search.
     *
     * @return string
     */
    public function scopeLike($query, $field, $value)
    {
        return $query->where($field, 'LIKE', "%$value%");
    }

    /**
     * Custome scope for Or Like Search.
     *
     * @return string
     */
    public function scopeOrLike($query, $field, $value)
    {
        return $query->orWhere($field, 'LIKE', "%$value%");
    }

    /**
     * Get the user record associated with the blog.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the comments for the blog post.
     */
    // public function comments()
    // {
    //     return $this->hasMany('App\Comment');
    // }

    /**
     * The category that belong to the blog.
     */
    public function pcategories()
    {
        return $this->belongsToMany('App\Pcategory');
    }

    /**
     * The category that belong to the blog.
     */
    public function subpcategories()
    {
        return $this->belongsToMany('App\Subpcategory');
    }
}
