<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'articles';
    protected int $category_id = 0; // Initialize the property with a default value

    public mixed $content;
    public mixed $title;
    public mixed $author;
    public mixed $published_at;
    public mixed $source;
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'title',
        'content',
        'url',
        'author',
        'source',
        'category_id',
        'published_at'
    ];

    // Relationships
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function preferences()
    {
        return $this->belongsToMany(Preference::class);
    }

}
