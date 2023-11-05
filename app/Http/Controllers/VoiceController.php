<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoiceRequest;
use App\Http\Requests\UpdateVoiceRequest;
use App\Models\Question;
use App\Models\Voice;
use Illuminate\Http\JsonResponse;

class VoiceController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @response array{ message:'Voting completed successfully', data:null}
     */
    public function store(StoreVoiceRequest $request): JsonResponse
    {
        $question = $this->findQuestion($request->post('question_id'));

        if ($this->isNotAllowedToVote($question)) {
            return $this->responseServerError('The user is not allowed to vote to your question');
        }

        $voice = $this->findUserVoice($request, $question);

        if ($this->isNotAllowedToVoteTwice($voice, $request)) {
            return $this->responseServerError('The user is not allowed to vote more than once');
        }

        if ($this->updateVoice($voice, $request)) {
            return $this->responseSuccess('Update your voice');
        }

        $this->createVoice($question, $request);

        return $this->responseCreated('Voting completed successfully');
    }

    private function findQuestion($questionId)
    {
        return Question::find($questionId);
    }

    private function isNotAllowedToVote($question)
    {
        return $question->user_id == auth()->id();
    }

    private function findUserVoice($request, $question)
    {
        return Voice::where([
            ['user_id',  auth()->id()],
            ['question_id',  $request->post('question_id')]
        ])->first();
    }

    private function isNotAllowedToVoteTwice($voice, $request)
    {
        return !is_null($voice) && $voice->value === $request->post('value');
    }

    private function updateVoice($voice, $request)
    {
        if (!is_null($voice) && $voice->value !== $request->post('value')) {
            $voice->update([
                'value' => $request->post('value')
            ]);
            return true;
        }
        return false;
    }

    private function createVoice($question, $request)
    {
        $question->voice()->create([
            'user_id' => auth()->id(),
            'value' => $request->post('value')
        ]);
    }
}
