<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Services\Data\DataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DataController extends Controller
{
    protected $dataService;

    public function __construct(DataService $dataService)
    {
        $this->dataService = $dataService;
    }

    // C. GET /api/search/nama?nama=Turner Mia
    public function searchByNama(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|min:2',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter ?nama= wajib diisi.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $result = $this->dataService->searchByNama($request->nama);

        if ($result === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari sumber eksternal.',
            ], 503);
        }

        return response()->json([
            'success'     => true,
            'keyword'     => $request->nama,
            'total_found' => count($result),
            'data'        => $result,
        ], 200);
    }

    // D. GET /api/search/nim?nim=9352078461
    public function searchByNim(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter ?nim= wajib diisi.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $result = $this->dataService->searchByNim(trim($request->nim));

        if ($result === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari sumber eksternal.',
            ], 503);
        }

        return response()->json([
            'success'     => true,
            'keyword'     => $request->nim,
            'total_found' => count($result),
            'data'        => $result,
        ], 200);
    }

    // E. GET /api/search/ymd?ymd=20230405
    public function searchByYmd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ymd' => ['required', 'string', 'size:8', 'regex:/^\d{8}$/'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Parameter ?ymd= wajib diisi dalam format YYYYMMDD.',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $result = $this->dataService->searchByYmd(trim($request->ymd));

        if ($result === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari sumber eksternal.',
            ], 503);
        }

        return response()->json([
            'success'     => true,
            'keyword'     => $request->ymd,
            'total_found' => count($result),
            'data'        => $result,
        ], 200);
    }

    // GET /api/data/all
    public function all()
    {
        $data = $this->dataService->getAll();

        if ($data === null) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dari sumber eksternal.',
            ], 503);
        }

        return response()->json([
            'success' => true,
            'total'   => count($data),
            'data'    => $data,
        ], 200);
    }
}