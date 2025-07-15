<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Employees",
 *     description="Operasi CRUD untuk data karyawan"
 * )
 * 
 * 
 * /**
 * @OA\Schema(
 *     schema="Employee",
 *     type="object",
 *     title="Employee",
 *     required={"first_name", "last_name", "email", "phone", "position", "department_id", "role_id"},
 *     @OA\Property(property="first_name", type="string", example="Budi"),
 *     @OA\Property(property="last_name", type="string", example="Santoso"),
 *     @OA\Property(property="email", type="string", format="email", example="budi@example.com"),
 *     @OA\Property(property="phone", type="string", example="081234567890"),
 *     @OA\Property(property="position", type="string", example="Manager"),
 *     @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
 *     @OA\Property(property="date_joined", type="string", format="date", example="2023-05-01"),
 *     @OA\Property(property="address", type="string", example="Jl. Merdeka No.1"),
 *     @OA\Property(property="status", type="string", example="active"),
 *     @OA\Property(property="department_id", type="integer", example=1),
 *     @OA\Property(property="role_id", type="integer", example=2)
 * )
 */

class EmployeeController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/employees",
     *     tags={"Employees"},
     *     summary="Ambil semua data karyawan dengan opsi pencarian dan filter",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Cari berdasarkan nama lengkap",
     *         required=false,
     *         @OA\Schema(type="string", example="Budi")
     *     ),
     *     @OA\Parameter(
     *         name="position",
     *         in="query",
     *         description="Filter berdasarkan jabatan/posisi",
     *         required=false,
     *         @OA\Schema(type="string", example="Manager")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil mengambil data",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employees retrieved successfully."),
     *             @OA\Property(property="data", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $query = Employee::with(['department', 'role']);

        if ($request->filled('search')) {
            $query->where('full_name', 'ILIKE', '%' . $request->search . '%');
        }

        if ($request->filled('position')) {
            $query->where('position', $request->position);
        }

        $employees = $query->get();

        return response()->json([
            'success' => true,
            'message' => 'Employees retrieved successfully.',
            'data' => $employees,
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/employees",
     *     tags={"Employees"},
     *     summary="Tambah karyawan baru",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"first_name", "last_name", "email", "phone", "position", "department_id", "role_id"},
     *             @OA\Property(property="first_name", type="string", example="Budi"),
     *             @OA\Property(property="last_name", type="string", example="Santoso"),
     *             @OA\Property(property="email", type="string", format="email", example="budi@example.com"),
     *             @OA\Property(property="phone", type="string", example="081234567890"),
     *             @OA\Property(property="position", type="string", example="Manager"),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-01-01"),
     *             @OA\Property(property="date_joined", type="string", format="date", example="2023-05-01"),
     *             @OA\Property(property="address", type="string", example="Jl. Merdeka No.1"),
     *             @OA\Property(property="status", type="string", example="active"),
     *             @OA\Property(property="department_id", type="integer", example=1),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Berhasil menambah karyawan",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee created successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:employees,email',
            'phone'          => 'required|string|max:20',
            'position'       => 'required|string|max:100',
            'department_id'  => 'required|exists:departments,id',
            'role_id'        => 'required|exists:roles,id',
            'date_of_birth'  => 'nullable|date',
            'date_joined'    => 'nullable|date',
            'address'        => 'nullable|string',
            'status'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $employee = Employee::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employee created successfully.',
            'data'    => $employee,
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Ambil detail karyawan berdasarkan ID",
     *     security={{"bearerAuth":{}}},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee found."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Karyawan tidak ditemukan")
     * )
     */
    public function show($id)
    {
        $employee = Employee::with(['department', 'role'])->find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Employee found.',
            'data' => $employee,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Update data karyawan",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Employee")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil update",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee updated successfully."),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name'     => 'sometimes|required|string|max:100',
            'last_name'      => 'sometimes|required|string|max:100',
            'email'          => 'sometimes|required|email|unique:employees,email,' . $id,
            'phone'          => 'sometimes|required|string|max:20',
            'position'       => 'sometimes|required|string|max:100',
            'department_id'  => 'sometimes|required|exists:departments,id',
            'role_id'        => 'sometimes|required|exists:roles,id',
            'date_of_birth'  => 'nullable|date',
            'date_joined'    => 'nullable|date',
            'address'        => 'nullable|string',
            'status'         => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $employee->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Employee updated successfully.',
            'data' => $employee,
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/employees/{id}",
     *     tags={"Employees"},
     *     summary="Hapus karyawan (soft delete)",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Berhasil dihapus",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Employee deleted successfully.")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Karyawan tidak ditemukan")
     * )
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee not found.',
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'success' => true,
            'message' => 'Employee deleted successfully.',
        ]);
    }
}
