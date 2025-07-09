<?php

namespace App\Controllers;

use App\Models\MahasiswaModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\Model;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpParser\Node\Stmt\TryCatch;

class ImportController extends BaseController
{
    public function saveToModel(UploadedFile $file, Model $model, array $fileField)
    {
        try {
            $rule = [
                'npm'   => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Field NPM tidak ditemukan pada file!'
                    ]
                ],

                'nama' => [
                    'rules' => 'required',
                    'errors' => [
                        'required' => 'Field Nama tidak ditemukan pada file!'
                    ]
                ],
            ];

            $spreadsheet = IOFactory::load($file);
            $sheet = $spreadsheet->getActiveSheet();
            $highestRow = $sheet->getHighestRow();
            $highestColumn = $sheet->getHighestColumn();
            $headerRow = 1;
            $headings = [];
            $data = [];
            $associativeData = [];
            $filteredData = [];
            $successData = 0;
            $failedData = 0;

            for ($col = 'A'; $col <= $highestColumn; $col++) {
                $cellValue = $sheet->getCell($col . $headerRow)->getValue();
                $headings[] = strtolower($cellValue);
            }

            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 'A'; $col <= $highestColumn; $col++) {
                    $cellValue = $sheet->getCell($col . $row)->getValue();
                    $rowData[] = $cellValue;
                }
                $data[] = $rowData;
            }

            for ($i = 0; $i < count($data); $i++) {
                $associativeData[] = array_combine($headings, $data[$i]);
            }

            if(!$this->validateData(array_values($associativeData)[0], $rule)) {
                return array(
                    'success' => false,
                    'message' => array_values($this->validator->getErrors())[0]
                ); 
            }

            for ($i = 0; $i < count($associativeData); $i++) {
                $validatedField = 0;

                foreach ($fileField as $x) {
                    if (!is_null($associativeData[$i][$x])) {
                        $validatedField++;
                    }
                }

                if ($validatedField == count($fileField)) {
                    $filteredData[] = $associativeData[$i];
                    $successData++;
                } else {
                    $failedData++;
                }
            }

            foreach($filteredData as $data) {
                $password = password_hash($data['npm'], PASSWORD_DEFAULT);
                $mahasiswa = new MahasiswaModel();
                $user = new UserModel();
                $id = $mahasiswa->insert($data);
                $userData = array(
                    'id_mahasiswa' => $id,
                    'username' => $data['npm'],
                    'password' => $password,
                    'status' => 'aktif',
                    'role' => 'mahasiswa'
                );
                $user->insert($userData);
            }

            return array(
                'success' => $successData > 0 ? true : false,
                'totalOfSuccessData' => $successData,
                'totalOfFailedData' => $failedData
            );
        } catch (\Throwable $th) {
            return array(
                'success' => false,
                'message' => $th->getMessage()
            );
        }
    }
}
