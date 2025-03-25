<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        if ($request->has('truncate_chat')) {
            $this->assistant->startNewChat();
        }
        return view('dashboard');
    }

    public function postMessage(Request $request)
    {
        $isPlainText = true;
        try {
            $response = $this->assistant->postMessage($request->get('message'));
            $message = Str::markdown($response);
            $isPlainText = $message === $response;
        } catch (\Throwable $th) {
            $message = 'Error: ' . $th->getMessage();
        }
        return new JsonResponse([
            'message' => $message,
            'isPlainText' => $isPlainText,
        ]);
    }
}
