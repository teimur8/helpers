<?php

namespace common\services;


use common\entities\ConfirmToken;
use common\entities\user\User;
use Namshi\Notificator\Notification\Sms\SmsNotification;
use Yii;

class SmsService extends NotificationService
{
    public function sendRegisterConfirm(User $user, ConfirmToken $token)
    {
        $this->send(new SmsNotification(
            $user->phone,
            Yii::t('app', "Registration code: {token}", ['token' => $token->token])
        ));
    }

    public function sendResetPasswordConfirm(User $user, ConfirmToken $token)
    {
        $notificator = new SmsNotification(
            $user->phone,
            Yii::t('app', 'Password reset code: {token}', ['token' => $token->token])
        );
        
        $this->send($notificator);
    }

    public function sendMemberInvitePassword(User $user, string $password)
    {
        $this->send(new SmsNotification(
            $user->phone,
            Yii::t('app', 'Your password for UnicastApp is: {password}', compact('password'))
        ));
    }

    public function sendChangePhoneConfirm(User $user, ConfirmToken $token)
    {
        $this->send(new SmsNotification(
            $user->phone,
            Yii::t('app', "New phone number confirmation code: {token}", ['token' => $token->token])
        ));
    }
}
