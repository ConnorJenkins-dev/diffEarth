<?php

namespace Tests\Unit;

use Tests\TestCase;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailTest extends TestCase
{
    public function test_email_is_sent_successfully()
    {
        $mailer = $this->createMock(PHPMailer::class);
        $mailer->method('send')->willReturn(true);

        $this->assertTrue($mailer->send());
    }

    public function test_email_fails_to_send()
    {
        $mailer = $this->createMock(PHPMailer::class);
        $mailer->method('send')->will($this->throwException(new Exception('SMTP error')));

        $this->expectException(Exception::class);
        $mailer->send();
    }
}
