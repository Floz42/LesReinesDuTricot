<?php 

namespace App\Service;

class MailerService 
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendMail(string $subject, string $setTo,string $setBody): void
    {
            $message = (new \Swift_Message())
            ->setSubject($subject)
            ->setFrom(['flo.carreclub@gmail.com' => 'Flo Thiébaud'])
            ->setTo($setTo)
            ->setContentType('text/html')
            ->setBody($setBody);
            $this->mailer->send($message);
    }

}