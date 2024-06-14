<?php

namespace App\Controllers;

use App\Models\ChecklistItemModel;
use App\Models\ChecklistModel;
use CodeIgniter\RESTful\ResourceController;

class ChecklistController extends ResourceController
{
    public $session;

    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function getAll()
    {
        $model = new ChecklistModel();
        $checklists = $model->where('user_id', $this->session->user_id)->first();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $checklists
        ];
        return $this->respond($response, 200);
    }

    public function create()
    {
        $rules = [
            'name' => 'required',
        ];

        if ($this->validate($rules)) {
            $model = new ChecklistModel();
            $data = [
                'name' => $this->request->getVar('name'),
                'user_id' => $this->session->user_id
            ];
            $model->save($data);
            
            $response = [
                'messages' => 'Berhasil',
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
            return $this->fail($response);
        }
    }

    public function delete($checkListId = null)
    {
        $model = new ChecklistModel();
        $data = $model->where('id', $checkListId)->delete();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $data
        ];
        return $this->respond($response, 200);
    }

    public function getAllItems($checkListId = null)
    {
        $model = new ChecklistItemModel();
        $items = $model->where('checklist_id', $checkListId)->findAll();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $items
        ];
        return $this->respond($response, 200);
    }

    public function createItem($checkListId = null)
    {
        $rules = [
            'name' => 'required',
        ];

        if ($this->validate($rules)) {
            $model = new ChecklistItemModel();
            $data = [
                'item_name' => $this->request->getVar('name'),
                'checklist_id' => $checkListId
            ];
            $model->save($data);
            
            $response = [
                'messages' => 'Berhasil',
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
            return $this->fail($response);
        }
    }

    public function getItem($checkListId = null, $checklistItemId = null) {
        $model = new ChecklistItemModel();
        $item = $model
            ->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->first();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $item
        ];
        return $this->respond($response, 200);
    }

    public function renameItem($checkListId = null, $checklistItemId = null)
    {
        $model = new ChecklistItemModel();
        $data = $model
            ->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->set($this->request->getVar())
            ->update();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $data
        ];
        return $this->respond($response, 200);
    }

    public function deleteItem($checkListId = null, $checklistItemId = null)
    {
        $model = new ChecklistItemModel();
        $data = $model
            ->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->delete();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $data
        ];
        return $this->respond($response, 200);
    }

    public function updateItemStatus($checkListId = null, $checklistItemId = null) {
        $model = new ChecklistItemModel();
        $data = $model
            ->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->first();

        $status = $data['status'] == 0 ? 1 : 0;

        $model = new ChecklistItemModel();
        $data = $model
            ->where('checklist_id', $checkListId)
            ->set('status', $status)
            ->update();
        
        $response = [
            'messages' => 'Berhasil',
            'data' => $data
        ];
        return $this->respond($response, 200);
    }
}
