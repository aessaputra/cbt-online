<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnswer;
use App\Models\CourseQuestion;
use App\Models\StudentAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class StudentAnswerController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request, Course $course, $question)
  {
    // Validate the request
    $validated = $request->validate([
      'answer_id' => 'required|exists:course_answers,id',
    ]);

    DB::beginTransaction();

    try {
      // Get the question and selected answer
      $currentQuestion = CourseQuestion::findOrFail($question);
      $selectedAnswer = CourseAnswer::findOrFail($validated['answer_id']);

      // Verify the answer belongs to the question
      if ($selectedAnswer->course_question_id != $question) {
        throw ValidationException::withMessages([
          'answer_id' => 'The selected answer does not belong to this question.'
        ]);
      }

      // Check if user already answered this question
      $existingAnswer = StudentAnswer::where('user_id', Auth::id())
        ->where('course_question_id', $question)
        ->first();

      if ($existingAnswer) {
        throw ValidationException::withMessages([
          'system_error' => 'You have already answered this question.'
        ]);
      }

      // Create the student answer
      StudentAnswer::create([
        'user_id' => Auth::id(),
        'course_question_id' => $question,
        'answer' => $selectedAnswer->is_correct ? 'correct' : 'wrong'
      ]);

      DB::commit();

      // Find the next question
      $nextQuestion = CourseQuestion::where('course_id', $course->id)
        ->where('id', '>', $question)
        ->orderBy('id', 'ASC')
        ->first();

      if ($nextQuestion) {
        return redirect()->route('dashboard.learning.course', [
          'course' => $course->id,
          'question' => $nextQuestion->id
        ]);
      }

      // If no more questions, redirect to finished page
      return redirect()->route('dashboard.learning.finished.course', $course->id);
    } catch (\Exception $e) {
      DB::rollBack();
      throw ValidationException::withMessages([
        'system_error' => 'An error occurred: ' . $e->getMessage()
      ]);
    }
  }

  /**
   * Display the specified resource.
   */
  public function show(StudentAnswer $studentAnswer)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit(StudentAnswer $studentAnswer)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, StudentAnswer $studentAnswer)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(StudentAnswer $studentAnswer)
  {
    //
  }
}
