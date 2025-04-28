<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class StudentAnswer extends Model
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

  public function question()
  {
    return $this->belongsTo(CourseQuestion::class, 'course_question_id');
  }
}
