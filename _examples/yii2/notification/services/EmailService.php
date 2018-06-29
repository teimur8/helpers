<?php

namespace common\services;


use common\models\ConfirmToken;
use common\models\EmailNotification;
use common\models\User;
use Yii;

class EmailService extends NotificationService
{
    public function sendRegisterConfirm(User $user, ConfirmToken $token)
    {
        $this->send(new EmailNotification(
            'emailConfirm',
            $user->email,
            Yii::t('app', 'Email confirmation'),
            compact('user', 'token')
        ));
    }

    public function sendMemberInvitePassword(User $user, string $password)
    {
        $this->send(new EmailNotification(
            'inviteMember',
            $user->email,
            Yii::t('app/email', 'UnicastApp invite'),
            compact('password')
        ));
    }

    /**
     * @param string $email
     * @param string $body
     * @param string|null $filename
     * @param array $fileOptions
     */
    public function sendHelpRequest(string $email, string $body, string $filename = null, array $fileOptions = [])
    {
        $this->send(new EmailNotification(
            'help',
            Yii::$app->params['supportEmail'],
            Yii::t('app/email', 'UnicastApp helpdesk'),
            compact('body', 'email'),
            $filename,
            $fileOptions
        ));
    }

}
