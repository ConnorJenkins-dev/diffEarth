<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\AlertEmail;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'location' => 'required|string',
            'column' => 'required|string',
            'threshold' => 'required|numeric',
            'emails' => 'required|array',
            'emails.*' => 'email',
        ]);

        $emailData = [
            'location' => $request->location,
            'column' => $request->column,
            'threshold' => $request->threshold,
        ];

        foreach ($request->emails as $email) {
            Mail::to($email)->send(new AlertEmail($emailData));
        }

        return response()->json(['message' => 'Emails sent successfully!']);
    }
}
