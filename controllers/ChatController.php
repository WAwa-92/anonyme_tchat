<?php

namespace Controllers;

use Managers\SalonManager;
use Managers\MessageManager;

class ChatController
{
    private $salonManager;
    private $messageManager;

    public function __construct()
    {
        $this->salonManager = new SalonManager();
        $this->messageManager = new MessageManager();
    }

    public function home()
    {
        $salons = $this->salonManager->findAll();
        $activeSalon = null;
        $messages = [];

        if (count($salons) > 0) {
            $activeSalon = $salons[0];
            $messages = $this->messageManager->findBySalon($activeSalon->getId());
        }

        $title = 'Tchat anonyme';
        $template = __DIR__ . '/../views/chat/index.phtml';

        include __DIR__ . '/../views/layout.phtml';
    }

    public function salon()
    {
        $salonId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
        $salons = $this->salonManager->findAll();
        $activeSalon = $this->salonManager->findById($salonId);

        if ($activeSalon === null) {
            header('Location: index.php?route=home');
            exit;
        }

        $messages = $this->messageManager->findBySalon($salonId);

        $title = 'Salon - ' . $activeSalon->getName();
        $template = __DIR__ . '/../views/chat/index.phtml';

        include __DIR__ . '/../views/layout.phtml';
    }

    public function createSalon()
    {
        $name = trim($_POST['name'] ?? '');

        if ($name !== '') {
            $newId = $this->salonManager->create($name);
            header('Location: index.php?route=salon&id=' . $newId);
            exit;
        }

        header('Location: index.php?route=home');
        exit;
    }

    public function sendMessage()
    {
        $salonId = (int) ($_POST['salon_id'] ?? 0);
        $content = trim($_POST['content'] ?? '');

        if ($salonId > 0 && $content !== '') {
            $this->messageManager->create($salonId, $content);
        }

        header('Location: index.php?route=salon&id=' . $salonId);
        exit;
    }

    public function pinMessage()
    {
        $salonId  = (int) ($_POST['salon_id'] ?? 0);
        $messageId = (int) ($_POST['message_id'] ?? 0);

        if ($salonId > 0 && $messageId > 0) {
            $this->messageManager->pinOne($salonId, $messageId);
        }

        header('Location: index.php?route=salon&id=' . $salonId);
        exit;
    }

    public function about()
    {
        $title = 'À propos';
        $template = __DIR__ . '/../views/about/index.phtml';

        include __DIR__ . '/../views/layout.phtml';
    }
}
