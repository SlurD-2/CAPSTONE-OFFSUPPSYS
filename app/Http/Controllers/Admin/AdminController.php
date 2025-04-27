<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\RequestSupply;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
class AdminController extends Controller
{
  
    public function requests()
    {
        // Use paginate() without get()
        $requests = RequestSupply::orderBy('withdrawal_status', 'asc')
        ->get();
    
        return view('admin.requests', compact('requests'))->with('success', 'Request Approved Successfully!');
    }
  
    public function create()
    {
        return view('admin.requests.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            
            'requester_name' => 'required|string',
            'department' => 'required|string',
            'item_name' => 'required|string',
            'quantity' => 'required|integer|min:1',
            'datetime' => 'required|date',
            'description' => 'nullable|string',
        ]);

        RequestSupply::create($request->all());

        return redirect()->route('admin.requests.index')->with('success', 'Request created successfully.');
    }

    public function update(HttpRequest $request, $id)
    {
       
        $request->validate([
            'department' => 'required|string|max:255',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'datetime' => 'required|date',
            'description' => 'nullable|string|max:1000',
        ]);

       
        $request = RequestSupply::findOrFail($id);

      
        $request->update([
            'department' => $request->department,
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'datetime' => $request->datetime,
            'description' => $request->description,
        ]);

        
        return redirect()->back()->with('success', 'Request updated successfully!');
    }


    public function dashboard()
    {  
        // Fetching required data
        $totalRequesters = RequestSupply::distinct('id')->count();
        $totalUsers = RequestSupply::distinct('user_id')->count();
        $totalPending = RequestSupply::whereIn('withdrawal_status', ['Pending', 'Processing'])->count();
        $totalCompleted = RequestSupply::where('withdrawal_status', 'Completed')->count();
        $totalItems = RequestSupply::sum('quantity');
        $totalUsersReturned = ReturnRequest::distinct('request_id')->count();
        $totalReplacementCompleted = ReturnRequest::where('replacement_status', 'Completed')->count();

        // Fetch stocks data
        $stocks = Stock::orderBy('item_name')->get();
        $itemNames = $stocks->pluck('item_name');
        $quantities = $stocks->pluck('stock_quantity'); 

        // Fetch yearly request data
        $yearlyData = RequestSupply::selectRaw('YEAR(datetime) as year, COUNT(*) as total')
            ->groupBy('year')
            ->orderBy('year')
            ->pluck('total', 'year');

        // Fetch monthly request data
        $monthlyData = RequestSupply::selectRaw('MONTH(datetime) as month, COUNT(*) as total')
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month');

        // Fetch daily request data grouped by month
        $dailyData = RequestSupply::selectRaw('MONTH(datetime) as month, DAY(datetime) as day, COUNT(*) as total')
            ->groupBy('month', 'day')
            ->get()
            ->groupBy('month') // Group by month first
            ->map(function ($monthData) {
                return $monthData->pluck('total', 'day'); // Then pluck day => count for each month
            })
            ->toArray();

        // Fetch department data
        $departmentData = RequestSupply::selectRaw('department, COUNT(*) as total')
        ->groupBy('department')
        ->orderBy('total', 'DESC')
        ->pluck('total', 'department')
        ->toArray();

        return view('admin.dashboard', compact(
            'stocks', 
            'itemNames', 
            'quantities', 
            'totalRequesters', 
            'totalUsers', 
            'totalItems', 
            'totalPending', 
            'totalCompleted',
            'yearlyData', // Added this
            'monthlyData',
            'dailyData',
            'departmentData',
            'totalUsersReturned',
            'totalReplacementCompleted'
        ));
    }

    public function users()
    {
        // Use paginate() without get()
        $users = User::nonAdmin()->get();


        return view('admin.users', compact('users'))->with('success', 'Request Approved Successfully!');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        try {
            // Validate the input data
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
                'role' => 'required|in:user,chairman,dean,admin',
                'department' => 'required|in:COT,COED,COHTM',
            ]);
    
            // Update the user
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'department' => $validated['department']
            ]);
    
            // Return JSON response
            return response()->json([
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'department' => $user->department
                ]
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }


    public function destroyUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
    
        return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
    }
    

    
    public function storeUser(Request $request)
    {
        // Validate the input data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/^(?=.*[A-Za-z])(?=.*\d)(?=.*[\W_]).+$/'

            ],
            'role' => 'required|in:user,chairman,dean,admin',
            'department' => 'required|in:COT,COED,COHTM',
        ]);
    
        // Ensure password is not the same as email
        if ($request->input('email') === $request->input('password')) {
            return back()->withErrors(['password' => 'Password cannot be the same as the email.']);
        }
    
        // Ensure password is not the same as username
        if ($request->input('name') === $request->input('password')) {
            return back()->withErrors(['password' => 'Password cannot be the same as the username.']);
        }
    
        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'department' => $validated['department']
        ]);
    
        // Return JSON if request is AJAX (from JS)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User created successfully!'
            ]);
        }
    
        // Otherwise, fallback to normal redirect
        return redirect()->route('admin.users')->with('success', 'User created successfully!');
    }
    
}


//   'totalPending',
//                 'totalApproved'
















//BACK UP CODE


// namespace App\Http\Controllers\Admin; 
// use App\Models\Stock;
// use Illuminate\Http\Request;
// use App\Models\RequestSupply; 

// class AdminController extends Controller
// {
  

    // public function requests()
    // {
    //     $requests = RequestSupply::all();


    //     return view('admin.requests', compact('requests'));
        
    // }

  
    // public function create()
    // {
    //     return view('admin.requests.create');
    // }

   
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'user_id' => 'required|integer',
    //         'requester_name' => 'required|string',
    //         'department' => 'required|string',
    //         'item_name' => 'required|string',
    //         'quantity' => 'required|integer|min:1',
    //         'date' => 'required|date',
    //         'description' => 'nullable|string',
    //     ]);

    //     RequestSupply::create($request->all());

    //     return redirect()->route('admin.requests.index')->with('success', 'Request created successfully.');
    // }


    // public function update(HttpRequest $request, $id)
    // {
       
    //     $request->validate([
    //         'department' => 'required|string|max:255',
    //         'item_name' => 'required|string|max:255',
    //         'quantity' => 'required|integer|min:1',
    //         'date' => 'required|date',
    //         'description' => 'nullable|string|max:1000',
    //     ]);

       
    //     $requestRecord = RequestSupply::findOrFail($id);

      
    //     $requestRecord->update([
    //         'department' => $request->department,
    //         'item_name' => $request->item_name,
    //         'quantity' => $request->quantity,
    //         'date' => $request->date,
    //         'description' => $request->description,
    //     ]);

    //     /
    //     return redirect()->back()->with('success', 'Request updated successfully!');
    // }

   
    // public function edit($id)
    // {
    //     $request = RequestSupply::findOrFail($id); 
    //     return view('admin.edit', compact('request'));
    // }

   
    // public function destroy($id)
    // {
    //     $request = RequestSupply::findOrFail($id);
    //     $request->delete(); 
    //     return redirect()->route('admin.requests')->with('success', 'Request deleted successfully.');
    // }

    
    

    // public function dashboard()
    // {  
      
    //     $stocks = Stock::all();
    //     $requests = RequestSupply::all();
    //     $user = RequestSupply::all();

    
     
    //     $totalRequesters = RequestSupply::distinct('id')->count();
    //     $totalUsers = RequestSupply::distinct('user_id')->count();
    //     $totalPending = RequestSupply::where('status', 'Pending')->count('id');
    //     $totalApproved = RequestSupply::where('status', 'Approved')->count('id');



    //     $totalItems = RequestSupply::sum('quantity');


    
    //     $stocks = Stock::orderBy('item_name')->get();
    //     $itemNames = $stocks->pluck('item_name');
    //     $quantities = $stocks->pluck('stock_quantity'); 
    

        
      
    //     return view('admin.dashboard', 
    //     compact('stocks', 
    //             'itemNames', 
    //             'quantities', 
    //             'totalRequesters', 
    //             'totalUsers', 
    //             'totalItems',
    //             'totalPending',
    //             'totalApproved'));
    // }


// }
