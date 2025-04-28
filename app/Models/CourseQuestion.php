<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class CourseQuestion extends Model
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

  public function course()
  {
    return $this->belongsTo(Course::class, 'course_id');
  }

  public function answers()
  {
    return $this->hasMany(CourseAnswer::class, 'course_question_id', 'id');
  }
}
