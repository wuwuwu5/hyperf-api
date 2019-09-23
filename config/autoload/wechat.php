<?php

return [
    'teacher' => [
        'app_id' => env('WECHAT_TEACHER_MINI_PROGRAM_APPID', ''),
        'secret' => env('WECHAT_TEACHER_MINI_PROGRAM_SECRET', ''),
        'token' => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
        'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        'log' => [
            'level' => env('WECHAT_LOG_LEVEL', 'debug'),
            'file' => env('WECHAT_LOG_FILE', BASE_PATH . '/runtime/logs/teacher_watch.log'),
        ],
    ],

    \App\Models\Student::class => [
        'app_id' => env('WECHAT_STUDENT_MINI_PROGRAM_APPID', ''),
        'secret' => env('WECHAT_STUDENT_MINI_PROGRAM_SECRET', ''),
        'token' => env('WECHAT_MINI_PROGRAM_TOKEN', ''),
        'aes_key' => env('WECHAT_MINI_PROGRAM_AES_KEY', ''),
        'log' => [
            'level' => env('WECHAT_LOG_LEVEL', 'debug'),
            'file' => env('WECHAT_LOG_FILE', BASE_PATH . '/runtime/logs/student_watch.log'),
        ],
    ]
];