<?php

namespace App\Validation;

class CustomRules
{
    public function check_date(string $str, string $fields, array $data): bool
    {
        if (empty($data['tanggal_mulai'])) {
            return false;
        }

        $tanggal_mulai = strtotime($data['tanggal_mulai']);
        $tanggal_selesai = strtotime($str);

        return $tanggal_selesai >= $tanggal_mulai;
    }
}
