<?php

declare(strict_types=1);

namespace App\Core;

class Controller
{
    protected View $view;
    protected Response $response;

    public function __construct()
    {
        $this->view = new View();
        $this->response = new Response();
    }

    protected function view(string $template, array $data = []): void
    {
        echo $this->view->render($template, $data);
    }

    protected function json(mixed $data, int $status = 200): void
    {
        $this->response->json($data, $status);
    }

    protected function redirect(string $url, int $status = 302): void
    {
        $this->response->redirect($url, $status);
    }

    protected function back(): void
    {
        $this->response->back();
    }

    protected function validate(Request $request, array $rules): array
    {
        $validator = new \App\Support\Validator();
        $data = $request->all();
        $result = $validator->validate($data, $rules);

        if (!$result['valid']) {
            Session::flash('errors', $result['errors']);
            Session::setOld($data);
            $this->back();
        }

        return $data;
    }
}
