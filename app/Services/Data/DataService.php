<?php

namespace App\Services\Data;

use App\Repositories\Data\DataRepository;

class DataService
{
    protected $dataRepository;

    public function __construct(DataRepository $dataRepository)
    {
        $this->dataRepository = $dataRepository;
    }

    /**
     * Ambil semua data mentah
     */
    public function getAll(): ?array
    {
        return $this->dataRepository->fetchAll();
    }

    /**
     * Filter by NAMA (case-insensitive, partial match)
     */
    public function searchByNama(string $nama): ?array
    {
        $data    = $this->dataRepository->fetchAll();
        $keyword = strtolower(trim($nama));

        if ($data === null) return null;

        return array_values(array_filter($data, function ($item) use ($keyword) {
            return str_contains(strtolower($item['NAMA'] ?? ''), $keyword);
        }));
    }

    /**
     * Filter by NIM (exact match)
     */
    public function searchByNim(string $nim): ?array
    {
        $data = $this->dataRepository->fetchAll();

        if ($data === null) return null;

        return array_values(array_filter($data, function ($item) use ($nim) {
            return (string)($item['NIM'] ?? '') === $nim;
        }));
    }

    /**
     * Filter by YMD (exact match, format YYYYMMDD)
     */
    public function searchByYmd(string $ymd): ?array
    {
        $data = $this->dataRepository->fetchAll();

        if ($data === null) return null;

        return array_values(array_filter($data, function ($item) use ($ymd) {
            return (string)($item['YMD'] ?? '') === $ymd;
        }));
    }
}