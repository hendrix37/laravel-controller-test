<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use App\Models\Voice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_value_not_boolean(): void
    {
        // Arrange
        $user = User::factory()->create();
        $question = Question::factory()->create();

        // Act
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/voice', ['question_id' => $question->id, 'value' => 'abc']);
        // Assert
        $response->assertStatus(422);
    }

    public function test_question_not_found(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/voice', ['question_id' => 123, 'value' => true]);
        // Assert
        $response->assertStatus(422);
    }

    public function test_server_error(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $question = Question::factory()->create([
            'user_id' => $user->id,
        ]);

        // Act
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/voice', ['question_id' => $question->id, 'value' => true]);
        // Assert
        $response->assertServerError();
        $response->assertSee('The user is not allowed to vote to your question');
    }

    public function test_check_if_user(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $question = Question::factory()->create();
        Voice::factory()->create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'value' => true
        ]);

        // Act
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/voice', ['question_id' => $question->id, 'value' => true]);
        // Assert
        $response->assertServerError();
        $response->assertSee('The user is not allowed to vote more than once');
    }

    public function test_check_else_user(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $question = Question::factory()->create();
        Voice::factory()->create([
            'user_id' => $user->id,
            'question_id' => $question->id,
            'value' => false
        ]);

        // Act
        $response = $this->actingAs($user)
            ->withHeaders([
                'Accept' => 'application/json',
            ])
            ->post('/api/voice', ['question_id' => $question->id, 'value' => true]);
        // Assert
        $response->assertOk();

        $content = $response->decodeResponseJson();

        $this->assertEquals('Update your voice', $content['message']);
    }

    public function test_created(): void
    {
        // Arrange
        $user = User::factory()->create();

        $question = Question::factory()->create();

        // Act
        $response = $this->actingAs($user)->post('/api/voice', ['question_id' => $question->id, 'value' => true]);
        // Assert
        $response->assertCreated();
        $response->assertSee('Voting completed successfully');
    }
}
