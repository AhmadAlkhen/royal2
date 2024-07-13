<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post_report extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'applied_by',
        'note',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class ,'applied_by','id');
    }
    public function post()
    {
        return $this->belongsTo(Post::class ,'post_id','id');
    }
    
}
