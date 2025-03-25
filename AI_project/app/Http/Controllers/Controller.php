<?php

namespace App\Http\Controllers;

use App\Assistant;
use Illuminate\Support\Str;

class Controller
{
    public function __construct(
        protected Assistant $assistant,
    ) {
    }

    public function welcome()
    {
        try {
            $message = $this->assistant->getGreeting();
        } catch (\Throwable $th) {
            $message = 'Please log in to chat with me!';
        }
        return view('welcome', [
            'greeting' => $message,
        ]);
    }

    public function readme()
    {
        return Str::markdown(file_get_contents(base_path('README.md')));
    }
}
