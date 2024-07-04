<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Userinfo;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;

class UserController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $users = Userinfo::all();
    
            return response()->json($users);
        }
    
        return view('dashboard');
    }

    public function show($id)
{
    $user = Userinfo::find($id);
    if ($user) {
        return response()->json($user);
    }
    return response()->json(['error' => 'User not found'], 404);
}

public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'firstname' => 'required|string|max:255',
        'lastname' => 'required|string|max:255',
        'fullname' => 'required|string|max:255',
        'emailid' => 'required|string|email|max:255,' . $id,
        'mobileno' => 'required|string|max:10,' . $id,
        'pan_no' => 'required|string|max:10,' . $id,
    ]);

    // dd($request->all());
    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()->all()], 422);
    }
    $user = Userinfo::find($id);
    if ($user) {
        $user->update($request->all());
        return response()->json(['success' => 'User updated successfully']);
    }

    return response()->json(['error' => 'User not found'], 404);
}


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'fullname' => 'required|string|max:255',
            'emailid' => 'required|string|email|max:255|unique:users',
            'mobileno' => 'required|string|max:15',
            'pan_no' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $user = Userinfo::create([
            'firstname' => $request->first_name,
            'lastname' => $request->last_name,
            'fullname' => $request->full_name,
            'emailid' => $request->email,
            'mobileno' => $request->mobile_number,
            'pan_no' => $request->pan_number,
        ]);

        return response()->json($user, 201);
    }

    public function destroy($id)
    {
        Userinfo::find($id)->delete();
        return response()->json(['success' => 'User deleted successfully']);
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:csv,xlsx'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()->all()], 422);
        }
    
        $path = $request->file('file')->getRealPath();
        try {
            $data = Excel::toCollection(null, $path)[0];
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error reading file. Please check the file format.'], 422);
        }
    
        // dd($data);
        $headers = $data->first()->toArray();
    
        $rows = $data->slice(1);
    
        $errors = [];
        $rowIndex = 1;
        foreach ($rows as $row) {
            $rowIndex++;
            $rowData = array_combine($headers, $row->toArray());
            $rowErrors = [];
    
            foreach ($rowData as $column => $value) {
                if (empty($value)) {
                    $rowErrors[] = "Please enter $column in row ".$rowIndex-1;
                }
            }
    
            if (!filter_var($rowData['emailid'], FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = "Please enter a valid Email in row ".$rowIndex-1;
            }
    
            if (!preg_match('/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i', $rowData['pan_no'])) {
                $rowErrors[] = "Please enter a valid PAN Number in row ".$rowIndex-1;
            }
    
            if ($rowErrors) {
                $errors = array_merge($errors, $rowErrors);
            } else {
                Userinfo::updateOrCreate(
                    ['emailid' => $rowData['emailid']],
                    $rowData
                );
            }
        }
    
        if ($errors) {
            return response()->json(['errors' => $errors], 422);
        }
    
        return response()->json(['success' => 'File uploaded successfully']);
    }


}
