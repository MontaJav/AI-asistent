<?php

namespace App;

use App\Models\Course;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use OpenAI;

class Assistant
{
    private const ASSISTANT_SYSTEM_DESCRIPTION = "You are an AI Study Planner."
        . "You can create a personalized study schedule based on a user's courses and assignments."
        . "You can add items to the user's schedule.";
//        . "You can get a list of courses and check which courses user is registered in."
//        . "You can check the user's assignments and remind of the deadlines."
//        . "You can get the course info, schedule and assignments and ID by name when the user mentions it."
//        . "You can get the user's current credit points amount and the total required amount of credit points."

    private const ASSISTANT_TOOLS = [
        [
            'name' => 'getAllCourses',
            'description' => 'Get a list of all available courses',
        ],
        [
            'name' => 'getUserCourses',
            'description' => 'Get a list of courses user is registered in',
        ],
        [
            'name' => 'getCourseByName',
            'description' => 'Get the course by name',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'courseName' => [
                        'type' => 'string',
                        'description' => 'Course name',
                    ],
                ],
                'required' => ['courseName'],
            ],
        ],
//        [
//            'name' => 'getUserCreditpoints',
//            'description' => 'Get user creditpoints for the courses',
//        ],
//        [
//            'name' => 'getRequiredCreditpoints',
//            'description' => 'Get required creditpoints for the courses',
//        ],
        [
            'name' => 'startNewChat',
            'description' => 'Start the conversation from scratch',
        ],
//        [
//            'name' => 'registerUserInCourse',
//            'description' => 'Register user in the course',
//            'parameters' => [
//                'type' => 'object',
//                'properties' => [
//                    'courseId' => [
//                        'type' => 'integer',
//                        'description' => 'Course ID',
//                    ],
//                ],
//                'required' => ['courseId'],
//            ],
//        ],
//        [
//            'name' => 'unregisterUserInCourse',
//            'description' => 'Unregister user in the course',
//            'parameters' => [
//                'type' => 'object',
//                'properties' => [
//                    'courseId' => [
//                        'type' => 'integer',
//                        'description' => 'Course ID',
//                    ],
//                ],
//                'required' => ['courseId'],
//            ],
//        ],
        [
            'name' => 'getUserAssignments',
            'description' => 'Get a list of user assignments',
        ],
        [
            'name' => 'getAssignmentDeadlines',
            'description' => 'Get a list of assignment deadlines',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'timeLimit' => [
                        'type' => 'string',
                        'description' => 'Amount of time to limit the deadlines',
                    ],
                ],
            ],
        ],
        [
            'name' => 'getUserSchedule',
            'description' => 'Get user personal schedule',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'timeLimit' => [
                        'type' => 'string',
                        'description' => 'Amount of time to limit the deadlines',
                    ],
                ],
            ],
        ],
        [
            'name' => 'addCustomScheduleItem',
            'description' => 'Add a custom item to user schedule',
            'parameters' => [
                'type' => 'object',
                'properties' => [
                    'description' => [
                        'type' => 'string',
                        'description' => 'Description',
                    ],
                    'start' => [
                        'type' => 'string',
                        'description' => 'Start time of the schedule item',
                    ],
                    'durationHours' => [
                        'type' => 'integer',
                        'description' => 'Duration of the schedule item in hours',
                    ],
                ],
                'required' => ['description', 'start'],
            ],
        ]
    ];

    private OpenAI\Client $openAi;

    public function __construct()
    {
        ini_set('max_execution_time', 300);
        $this->openAi = OpenAi::client(env('OPENAI_API_KEY'));
    }

    private function getAllCourses(): array
    {
        return Course::all()->map->toMinimumInfoArray()->toArray();
    }

    private function getUserCourses(): array
    {
        return Auth::user()->courses->map->toMinimumInfoArray()->toArray();
    }

    private function getCourseByName(string $courseName)
    {
        return Course::where('name', $courseName)->first()->toArray();
    }

    private function getUserCreditpoints()
    {
        return Auth::user()->courses->sum('creditpoints');
    }

    private function getRequiredCreditpoints()
    {
        return env('REQUIRED_CREDITPOINTS', 20);
    }

    private function registerUserInCourse(int $courseId): void
    {
        Auth::user()->courses()->attach($courseId);
    }

    private function unregisterUserInCourse(int $courseId): void
    {
        Auth::user()->courses()->detach($courseId);
    }

    private function getUserAssignments(): array
    {
        return auth()->user()->courses->flatMap->assignments->map->toMinimumInfoArray()->toArray();
    }

    private function getAssignmentDeadlines(string $timeLimit = '1 week'): array
    {
        return auth()->user()->courses->flatMap->assignments
            ->where('due_at', '<', now()->add($timeLimit))
            ->map->toMinimumInfoArray()
            ->sortBy('due_at')
            ->filter((fn($assignment) => !$assignment['isCompleted']))
            ->values()
            ->toArray();
    }

    private function addCustomScheduleItem(string $description, string $start, int $durationHours = 1)
    {
        $start = CarbonImmutable::parse($start);

        auth()->user()->schedule()->create([
            'description' => $description,
            'start' => $start->format('Y-m-d H:00:00'),
            'end' => $start->addHours($durationHours)->format('Y-m-d H:00:00'),
        ]);

        $this->startNewChat();

        return 'Item added to the schedule';
    }

    private function getUserSchedule(string $timeLimit = '1 week')
    {
        return auth()->user()->schedule
            ->whereBetween('start', [now(), now()->add($timeLimit)->endOfDay()])
            ->map->toMinimumInfoArray()->toArray();
    }

    public function startNewChat()
    {
        Session::remove('chat_messages');
    }

    public function postMessage(string $message, string $role = 'user', string $defaultResponse = 'No answer'): string
    {
        $messages = $this->getPreviousMessages();
        $messages[] = ['role' => $role, 'content' => $message];
        Session::put('chat_messages', $messages);

        return $this->getChatMessage($this->openAi->chat()->create([
            'model' => 'gpt-4',
            'messages' => $messages,
            'functions' => self::ASSISTANT_TOOLS,
        ]), $defaultResponse);
    }

    private function getPreviousMessages(): array
    {
        $configMessage = ['role' => 'system', 'content' => self::ASSISTANT_SYSTEM_DESCRIPTION];
        $previousMessages = Session::get('chat_messages', []);
        if (!$previousMessages) {
            $previousMessages[] = $configMessage;
        } elseif (count($previousMessages) > (int)env('MAX_CHAT_MESSAGES', 100)) {
            $previousMessages = array_slice($previousMessages, -1 * (int)env('MAX_CHAT_MESSAGES', 100));
            array_unshift($previousMessages, $configMessage);
        }

        return $previousMessages;
    }

    public function getGreeting(): string
    {
        return $this->postMessage(
            'Hello!' . (Auth::guest() ? '' : ' My name is ' . Auth::user()->name),
            'user',
            'Hi! Please sign in to continue.'
        );
    }

    private function getChatMessage(OpenAI\Responses\Chat\CreateResponse $response, string $default = ""): string
    {
        foreach ($response->choices ?? [] as $item) {
            if (!isset($item->message)) {
                continue;
            }
            if ($item->message->role !== 'assistant') {
                continue;
            }
            if (isset($item->message->functionCall)) {
                $function = $item->message->functionCall;
                $parameters = json_decode($function->arguments, true) ?? [];
                $functionResult = $this->{$function->name}(...$parameters);
                return $this->postMessage(json_encode($functionResult), 'system');
            }
            if (isset($item->message->content)) {
                return $item->message->content;
            }
        }
        return $default;
    }
}
