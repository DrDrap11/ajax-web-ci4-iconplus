<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\User_model;

class User extends Controller
{

    public function __construct()
    {
        require_once APPPATH . 'ThirdParty/ssp.php';
        $this->db = db_connect();
        
    }

    public function index()
    {
        $session = session();
        $uname['user_name'] = $session->get('user_name');
        $uname['role'] = $session->get('role');

        echo view('header', $uname);
        echo view('user_view');
        echo view('footer');
    }

    public function getAllUser()
    {
        //DB Details
        $dbDetails = array(
            "host" => $this->db->hostname,
            "user" => $this->db->username,
            "pass" => $this->db->status,
            "db" => $this->db->database,
        );

        // $table = "employees"; //langsung dr tabel employees
        $table = "users";
        $primaryKey = "user_id";

        $columns = array(
            array(
                "db" => "user_id",
                "dt" => 0,
                "formatter" => function ($d, $row) {
                    return "<div class='btn-group'>
                                  <a class='btn btn-warning btn-edit ' data-id='" . $row['user_id'] . "' data-bs-toggle='modal' data-bs-target='#editModal' id='updateBtn' style='margin-right: 10px'><i class='ti ti-edit'></i></a>

                                  <button class='btn btn btn-danger' data-id='" . $row['user_id'] . "' id='deleteUserBtn'> <i class='ti ti-trash'></i></button>
                             </div>";
                }
            ),
            array("db" => "user_id", "dt" => 1),
            array("db" => "user_name", "dt" => 2),
            array("db" => "user_email", "dt" => 3),
            array("db" => "status", "dt" => 4)
        );

        $session = session();
        $user_id = $_SESSION["user_id"];
        $role = $_SESSION["role"];

        if($role === "Admin") {
            echo json_encode(
                \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, null, "deleted_at IS NULL")
            );
        } else {
            echo json_encode(
                \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, null, "user_id = '$user_id' AND deleted_at IS NULL")
            );
        }

        // if ($role === "Admin") {
        //     echo json_encode(
        //         \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, null)
        //     );
        // } else {
        //     echo json_encode(
        //         \SSP::simple($_GET, $dbDetails, $table, $primaryKey, $columns, null)
        //     );
        // }
    }

    public function editUser()
    {
        $model = new User_model();
        $id = $this->request->getPost("edit_id");

        $data['users'] = $model->getUser($id);
        // echo json_encode($data);
        // $data['employee'] = $model->getEmployee($id)->getResult();
        return $this->response->setJSON($data);
        // var_dump($data);
    }

    // public function updateUser()
    // {
    //     $validation = \Config\Services::validation();
    //     $model = new User_model;

    //         $id = $this->request->getPost("user_id");
    //         $data = [
    //             'user_name'          => $this->request->getPost('user_name'),
    //             'user_email'          => $this->request->getPost('user_email'),
    //         ];

    //         $update = $model->update($id, $data);

    //         if ($update) {
    //             $output = ['status' => 'Data berhasil diupdate'];
    //             return $this->response->setJSON($output);
    //         } else {
    //             $output = ['status' => 'Data gagal diupdate'];
    //             return $this->response->setJSON($output);
    //         }
    //     // }
    // }

    public function deleteUser($id){


        $dataModel = new \App\Models\User_Model();
        $data_id = $this->request->getPost('id');
        $query = $dataModel->delete($data_id);

        if ($query) {
            echo json_encode(['code' => 1, 'msg' => 'Data behasil dihapus']);
        } else {
            echo json_encode(['code' => 0, 'msg' => 'Data gagal dihapus']);
        }
    }
}