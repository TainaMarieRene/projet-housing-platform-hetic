<?php 

namespace Controller;

use Model\UserModel;

abstract class Controller{
    public array $styles = [];
    public ?UserModel $userLoggedIn;

    public function setUserLoggedIn(?UserModel $userLoggedIn): self{
        $this->userLoggedIn = $userLoggedIn;
        return $this;
    }

    protected function getUserLoggedIn(): ?UserModel{
        return $this->userLoggedIn;
    }

    protected function updateStyles(array $styles): void{
        $this->styles = $styles;
    }
    
    protected function render(string $view, array $styles, array $data): void{
        $defaultData = [
            "user_logged_in" => $this->getUserLoggedIn()
        ];
        $data = array_merge($defaultData, $data);
        $header = $this->captureOutput('template', 'header.php', $data);
        $footer = $this->captureOutput('template', 'footer.php', $data);
        $content = $this->captureOutput('content', $view, $data);

        require_once __ROOT_DIR__ . "/src/view/view.php";
    }

    private function captureOutput(string $way, string $file, array $data): string{
        ob_start();
        require_once sprintf('%s/src/view/%s/%s', __ROOT_DIR__, $way, $file);
        return ob_get_clean(); 
    }
}
