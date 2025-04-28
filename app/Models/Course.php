<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Course extends Model
{
  use HasFactory, SoftDeletes;

  public $incrementing = false;
  protected $keyType = 'string';


  protected $guarded = [
    'id',
  ];

  protected static function boot()
  {
    parent::boot();

    static::creating(function ($model) {
      $model->id = $model->id ?? Str::uuid()->toString();
    });
  }


  public function category()
  {
    return $this->belongsTo(Category::class, 'category_id');
  }

  public function questions()
  {
    return $this->hasMany(CourseQuestion::class, 'course_id', 'id');
  }

  public function students()
  {
    return $this->belongsToMany(User::class, 'course_students', 'course_id', 'user_id')
      ->withTimestamps();;
  }
}
