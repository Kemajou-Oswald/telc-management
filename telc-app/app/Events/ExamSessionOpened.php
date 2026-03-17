<?php

namespace App\Events;

use App\Models\ExamSession;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ExamSessionOpened
{
    use Dispatchable, SerializesModels;

    public ExamSession $session;

    public function __construct(ExamSession $session)
    {
        $this->session = $session;
    }
}
