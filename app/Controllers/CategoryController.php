<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class CategoryController extends BaseController
{
    protected $model;

    public function __construct() {
        $this->model = model(\App\Models\CategoryModel::class);
    }

    public function index()
    {
        $authUserId = auth()->user()->id;

        $categories = $this->model
            ->where('user_id', $authUserId)
            ->findAll();

        return view('categories/index', [
            'categories' => $categories,
        ]);
    }
    public function new() {
        return view('categories/create');
    }

    public function edit(int $id) {
        $authUserId = auth()->user()->id;

        $category = $this->model
            ->where('user_id', $authUserId)
            ->find($id);
        
        if (! $category) return redirect()->to(site_url('categories'))->with('error', 'Categoria não encontrada.');

        return view('categories/edit', [
            'category' => $category,
        ]);
    }

    public function put(int $id) {
        $authUserId = auth()->user()->id;

        $category = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if(! $category) return redirect()->to(site_url('categories'))->with('error', 'Conta não encontrada');

        $formData = $this->request->getPost();

        if($this->model->update($id, $formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->model->errors());
        }

        return redirect()
            ->to(site_url('categories'))
            ->with('success', 'Categoria atualizada com sucesso');
    }

    public function post() {
        $authUserId = auth()->user()->id;
        $formData = $this->request->getPost();

        $formData['user_id'] = $authUserId;
        $formData['type'] = strtoupper($formData['type']);

        if($this->model->insert($formData) === false) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $this->model->errors());
        }

        return redirect()
            ->to(site_url('categories'))
            ->with('success', 'Categoria criada com sucesso!');
    }

    public function delete(int $id) {
        $authUserId = auth()->user()->id;

        $category = $this->model
            ->where('user_id', $authUserId)
            ->find($id);

        if(! $category ) return redirect()->to(site_url('categories'))->with('error', 'Categoria não encontrada');

        if($this->model->delete($id) === false) {
            return redirect()
                ->back()
                ->with('error', 'Não foi possivel eleminar a categoria');
        }

        return redirect()
            ->to(site_url('categories'))
            ->with('success', 'Categoria eliminada com sucesso!');
    }
}
