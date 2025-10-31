<?php
namespace Haryadi\Controller;

use Haryadi\View\View;

class Controller
{
    protected function view(string $name, array $data = [])
    {
        return View::render($name, $data);
    }
}
