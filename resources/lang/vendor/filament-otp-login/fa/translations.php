<?php

return [
    'otp_code' => 'کد هویت',

    'mail' => [
        'subject' => 'کد هویت',
        'greeting' => 'سلام علیکم!',
        'line1' => 'کد هویت شما: :code',
        'line2' => 'کد هویت شما تا :seconds ثانیه معتبر است.',
        'line3' => 'اگر شما درخواست کد نکرده‌اید، لطفاً این پیام را نادیده بگیرید.',
        'salutation' => 'با احترام, :app_name',
    ],

    'view' => [
        'time_left' => 'ثانیه باقی مانده',
        'resend_code' => 'ارسال مجدد کد هویت',
        'verify' => 'تأیید شد',
        'go_back' => 'بازگشت',
    ],

    'notifications' => [
        'title' => 'کد هویت ارسال شد',
        'body' => 'کد هویت ارسال شد. این کد تا :seconds ثانیه معتبر است.',
    ],

    'validation' => [
        'invalid_code' => 'کد هویت نادرست است.',
        'expired_code' => 'کد هویت منقضی شده است.',
    ],
];