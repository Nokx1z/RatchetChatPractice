<?php
namespace Deadt\RatchetChatPractice\Controllers;

use Deadt\RatchetChatPractice\Core\Controller;

class ChatController extends Controller {
    public function index() {
        $this->view('chat', []);
    }
}