<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log; // Import Log facade
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Alert;

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        Log::debug('Mail Configuration', [
            'MAIL_HOST' => env('MAIL_HOST'),
            'MAIL_PORT' => env('MAIL_PORT'),
            'MAIL_USERNAME' => env('MAIL_USERNAME'),
            'MAIL_PASSWORD' => env('MAIL_PASSWORD'),
            'MAIL_FROM_ADDRESS' => env('MAIL_FROM_ADDRESS'),
            'MAIL_FROM_NAME' => env('MAIL_FROM_NAME'),
        ]);
        // Ensure JSON responses for validation errors
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), [
                'location' => 'required|string',
                'column' => 'required|string',
                'threshold' => 'required|numeric',
                'emails' => 'required|array',
                'emails.*' => 'email',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
        }

        // Create the alert record in the database
        $alert = Alert::create([
            'location' => $request->location,
            'column' => $request->column,
            'threshold' => $request->threshold,
            'emaillist' => json_encode($request->emails),
        ]);

        Log::debug('Alert Created', ['alert' => $alert]);

        // Load PHPMailer
        require base_path('vendor/autoload.php');

        foreach ($request->emails as $email) {
            $mail = new PHPMailer(true);

            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = env('MAIL_HOST');
                $mail->SMTPAuth = true;
                $mail->Username = env('MAIL_USERNAME');
                $mail->Password = env('MAIL_PASSWORD');
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = env('MAIL_PORT');

                // Recipients
                $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                $mail->addAddress($email);

                // Content
                $mail->isHTML(true);
                $mail->Subject = 'Threshold Alert';
                $mail->Body = "<p>Hello, </p>
                               <p>You have been selected to track <b>{$request->location}</b></p>
                               <p>Monitoring for when <b>{$request->column}</b> exceeds the threshold <b>
                               {$request->threshold}</b></p>
                               <p>You will be notified by this email when that happens</p>
                               <p>Kind regards,</p>
                               <p>Chil mailing service </p>";

                $mail->AltBody = "Alert for {$request->location}. Column: {$request->column}.
                Threshold exceeded: {$request->threshold}";

                // Send email
                $mail->send();
            } catch (Exception $e) {
                // Log error details
                Log::error("Mailer Error: {$mail->ErrorInfo}"); // Log mail error
                return response()->
                json(['message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"], 500);
            }
        }

        return response()->json(['message' => 'Emails sent and alert saved successfully!']);
    }
}
