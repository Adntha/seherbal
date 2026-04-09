<?php

// Vercel Serverless Entry Point for Laravel
// This file forwards all requests to Laravel's public/index.php

// Fix: Set the document root to the public folder
$_ENV['APP_BASE_PATH'] = dirname(__DIR__);

// Include Laravel's public index
require __DIR__ . '/../public/index.php';
