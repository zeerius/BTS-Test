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
                'messages' => 'Berhasil membuat checklist baru',
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
            'messages' => 'Berhasil menghapus data checklist',
        ];
        return $this->respond($response, 200);
    }

    public function getAllItem($checkListId = null)
    {

        $model = new ChecklistItemModel();
        $items = $model
            ->where('checklist_id', $checkListId)
            ->where('status', 1)
            ->findAll();
        
        $response = [
            'messages' => 'Berhasil mengambil data semua checklist item',
            'data' => $items
        ];
        return $this->respond($response, 200);
    }

    public function createItem($checkListId = null)
    {
        $rules = [
            'itemName' => 'required',
        ];

        if ($this->validate($rules)) {
            $model = new ChecklistItemModel();
            $data = [
                'item_name' => $this->request->getVar('itemName'),
                'checklist_id' => $checkListId
            ];
            $model->save($data);
            
            $response = [
                'messages' => 'Berhasil membuat item checklist',
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
            'messages' => 'Berhasil mengambil data item checklist',
            'data' => $item
        ];
        return $this->respond($response, 200);
    }

    public function renameItem($checkListId = null, $checklistItemId = null)
    {
        $rules = [
            'itemName' => 'required',
        ];

        if ($this->validate($rules)) {
            $model = new ChecklistItemModel();
            $model->where('id', $checklistItemId)
                ->where('checklist_id', $checkListId)
                ->set('item_name', $this->request->getVar('itemName'))
                ->update();
            
            $response = [
                'messages' => 'Berhasil mengubah data item checklist',
            ];
            return $this->respond($response, 200);
        } else {
            $response = [
                'error' => $this->validator->getErrors(),
            ];
            return $this->fail($response);
        }
    }

    public function deleteItem($checkListId = null, $checklistItemId = null)
    {
        $model = new ChecklistItemModel();
        $model->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->delete();
        
        $response = [
            'messages' => 'Berhasil menghapus item checklist',
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
            ->where('id', $checklistItemId)
            ->where('checklist_id', $checkListId)
            ->set('status', $status)
            ->update();
        
        $response = [
            'messages' => 'Berhasil mengubah status item checklist menjadi '.$status?'aktif':'tidak aktif',
        ];
        return $this->respond($response, 200);
    }
}
