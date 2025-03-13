<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use App\Models\Alert;

// Add the Alert model

class EmailController extends Controller
{
    public function sendEmail(Request $request)
    {
        // Ensure JSON responses for validation errors
        if ($request->expectsJson()) {
            \Illuminate\Support\Facades\Validator::make($request->all(), [
                'location' => 'required|string',
                'column' => 'required|string',
                'threshold' => 'required|numeric',
                'emails' => 'required|array',
                'emails.*' => 'email',
            ])->validate();
        }

        // Create the alert record in the database
        $alert = Alert::create([
            'location' => $request->location,
            'column' => $request->column,
            'threshold' => $request->threshold,
            'emaillist' => json_encode($request->emails),
        ]);

        // Now proceed with the email sending process
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

                $mail->send();
            } catch (Exception $e) {
                return response()->
                json(['message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"], 500);
            }
        }

        return response()->json(['message' => 'Emails sent and alert saved successfully!']);
    }
}
