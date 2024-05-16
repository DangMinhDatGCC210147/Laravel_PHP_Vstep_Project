<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\IndexAdminController;
use App\Http\Controllers\InstructorsController;
use App\Http\Controllers\ListeningController;
use App\Http\Controllers\ReadingController;
use App\Http\Controllers\SpeakingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TestsController;
use App\Http\Controllers\WritingController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthController::class, 'showlogin'])->name('student.index');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware(['auth'])->group(function () {

    Route::get('/index-lecturer', [IndexAdminController::class, 'index'])->name('admin.index');
    // INSTRUCTORS
    Route::get('/list-lecturer', [InstructorsController::class, 'index'])->name('tableLecturer.index');
    Route::get('/create-lecturer', [InstructorsController::class, 'create'])->name('createInstructor.create');
    Route::post('/create-lecturer', [AuthController::class, 'registerPost'])->name('createInstructor.store');
    Route::get('/lecturers/{slug}/edit', [InstructorsController::class, 'edit'])->name('createInstructor.edit');
    Route::put('/lecturers/{slug}', [InstructorsController::class, 'update'])->name('createInstructor.update');
    Route::delete('/lecturers/{slug}', [InstructorsController::class, 'destroy'])->name('createInstructor.destroy');


    //TESTS
    Route::get('/list-test', [TestsController::class, 'index'])->name('tableTest.index');
    Route::get('/tests/create', [TestsController::class, 'create'])->name('test.create');
    Route::post('/tests', [TestsController::class, 'store'])->name('test.store');
    Route::get('/tests/{test_slug}/edit', [TestsController::class, 'edit'])->name('test.edit');
    Route::put('/tests/{test_slug}', [TestsController::class, 'update'])->name('test.update');
    Route::delete('/tests/{test_slug}', [TestsController::class, 'destroy'])->name('test.destroy');

    //EACH SKILL OF THE TEST
    Route::get('/tests/{test_slug}/skills', [TestsController::class, 'show'])->name('testSkills.show');
    //SHOW 4 PARTS OF THE SKILL FOR EACH TEST
    Route::get('/tests/{test_slug}/skills/{skill_slug}/parts', [TestsController::class, 'showDetails'])->name('test.skill.parts');


    //VIEW INPUT QUESTION FOR READING
    Route::get('/test/{test_slug}/skills/{skill_slug}/view', [TestsController::class, 'addSkillQuestions'])->name('skill.add.questions');
    Route::get('/edit-skill/{test_slug}/{skill_slug}/edit', [TestsController::class, 'editSkillQuestions'])->name('skill.edit.questions');


    //FUNCTIONS FOR SAVE AND UPDATE READING
    Route::post('/save-reading/{test_slug}/{skill_id}/reading', [ReadingController::class, 'storeReading'])->name('reading.questions.store');
    Route::put('/edit-reading/{test_slug}/{skill_slug}/update', [ReadingController::class, 'updateReading'])->name('reading.questions.update');

    //FUNCTIONS FOR SAVE AND UPDATE WRITING
    Route::post('/save-writing/{test_slug}/{skill_id}/writing', [WritingController::class, 'storeWriting'])->name('writing.questions.store');
    Route::put('/update-writing/{test_slug}/{skill_slug}/update', [WritingController::class, 'updateWriting'])->name('writing.questions.update');

    //FUNCTIONS FOR SAVE AND UPDATE LISTENING
    Route::post('/save-listening/{test_slug}/{skill_id}/listening', [ListeningController::class, 'storeListening'])->name('listening.questions.store');
    Route::put('/update-listening/{test_slug}/{skill_slug}/update', [ListeningController::class, 'updateListening'])->name('listening.questions.update');

    //FUNCTIONS FOR SAVE AND UPDATE SPEAKING
    Route::post('/save-speaking/{test_slug}/{skill_id}/speaking', [SpeakingController::class, 'storeSpeaking'])->name('speaking.questions.store');
    Route::put('/update-speaking/{test_slug}/{skill_slug}/update', [SpeakingController::class, 'updateSpeaking'])->name('speaking.questions.update');
});
