<?php

namespace App\Http\Controllers\User;
use App\Models\Stock;
use Illuminate\Http\Request;
use App\Models\RequestSupply;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use App\Mail\NewRequestNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class RequestSupplyController extends Controller
{   
   
    public function request()
    {
            
        $requests = RequestSupply::where('user_id')
            ->orderBy('datetime','asc')
            
            ->get();

        $stocks = Stock::all(); 

        return view('user.request', compact('requests', 'stocks'));


    }

    public function storeRequest(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'requester_name' => 'required|string',
                'department' => 'required|string',
                'item_name' => 'required|string',
                'variant_value' => 'nullable|string', // Add this line
                'quantity' => 'required|integer|min:1',
                'datetime' => 'required|date',
                'description' => 'nullable|string',
                'date_needed' => 'required|date|after_or_equal:today',
         
            ]);
    
              // Store the request in the database
        $newRequest = RequestSupply::create($validated);

        // Send email notification
        $recipientEmail = 'pagulakert@gmail.com'; // Replace with actual recipient email
        // Or you could get it from the user: $request->user()->email;
        
        Mail::to($recipientEmail)
            ->send(new NewRequestNotification($newRequest));
    
            return redirect()->route('user.request')->with('success', 'Request submitted successfully!');
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput()
                ->with('error', 'Please correct the errors in the form.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'An error occurred. Please try again.');
        }
    }

    public function history(Request $request)
    {
                // $sortDirection = $request->get('sort_direction', 'desc'); // Default to descending order
                $user_id = Auth::id();
                            $sortDirection = $request->get('sort_direction', 'desc'); // Default to descending order

                $requests = RequestSupply::where('user_id', $user_id)
                            ->where('withdrawal_status', 'Completed') // Only show completed withdrawals
                            // ->orderBy('withdrawed_at', $sortDirection) // Sort by withdrawal date
                            ->get();

                $returns = ReturnRequest::where('user_id', $user_id)
                ->where('return_status', 'approved')
                ->get();

                return view('user.history.history', compact('requests', 'returns'));
    }


    public function status()
    {
        $user_id = Auth::id();
        
        // Requests data (first tab)
        $requests = RequestSupply::where('user_id', $user_id)
        
    
            ->get();
            $returns = ReturnRequest::where('user_id', $user_id)
            ->get();


            return view('user.status.status', [
                'requests' => $requests,
                'returns' => $returns
            ]);
    }


    public function getRequestDetails($id)
    {
        $request = RequestSupply::findOrFail($id);
        return response()->json([
            'id'         => $request->id,
            'department' => $request->department,
            'item_name'  => $request->item_name,
            'quantity'   => $request->quantity,
            'datetime'   => $request->datetime,
            'description'=> $request->description,
        ]);
    }

public function checkReturnStatus($requestId)
    {
        $returnExists = ReturnRequest::where('request_id', $requestId)->exists();
        
        
        return response()->json([
            'already_returned' => $returnExists
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_id' => 'required|exists:request_supplies,id',
            'condition' => 'required|in:defective,damaged,other',
            'quantity' => 'required|integer|min:1',
            'description' => 'required|string|max:500',
            'return_date' => 'required|date',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);
    
        // Check if this request has already been returned
        $existingReturn = ReturnRequest::where('request_id', $validated['request_id'])->first();
        
        if ($existingReturn) {
            return response()->json([
                'success' => false,
                'message' => 'This item has already been returned.'
            ], 422);
        }
    
        // Get the original request
        $originalRequest = RequestSupply::findOrFail($validated['request_id']);
    
        // Validate return quantity doesn't exceed original quantity
        if ($validated['quantity'] > $originalRequest->quantity) {
            return back()->withErrors(['quantity' => 'Return quantity cannot exceed original request quantity']);
        }

        $imagePath = null;
        if ($request->hasFile('proof_image')) {
            $imagePath = $request->file('proof_image')->store('returns/proof_images', 'public');
        }
    
        // Create the return record
        $return = ReturnRequest::create([
            'request_id' => $validated['request_id'],
            'user_id' => Auth::id(),
            'requester_name' => Auth::user()->name,
            'item_name' => $originalRequest->item_name,
            'quantity' => $validated['quantity'],
            'department' => $originalRequest->department,
            'return_date' => $validated['return_date'],
            'condition' => $validated['condition'],
            'description' => $validated['description'],
            'proof_image' => $imagePath,
            'return_status' => 'pending',
        ]);
    
        // Update the original request status
        $originalRequest->update(['status' => 'returned']);
    
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Return submitted successfully!'
            ]);
        }
    
        return redirect()->route('user.status')
            ->with('success', 'Return submitted successfully!');
    }

    public function updateReturn($id, Request $request)
    {
        // Validate and update the return request
        $validatedData = $request->validate([
            'department' => 'required|string',
            'item_name'  => 'required|string',
            'quantity'   => 'required|integer|min:1',
            'datetime'   => 'required|date',
            // Additional validations for signature and description if needed
        ]);

        $returnRequest = RequestSupply::findOrFail($id);
        $returnRequest->update($validatedData);

        // Optionally, update signature or description if provided
        if ($request->has('signature')) {
            $returnRequest->signature = $request->input('signature');
        }
        if ($request->has('description')) {
            $returnRequest->description = $request->input('description');
        }

        $returnRequest->save();

        return redirect()->back()->with('success', 'Return request updated successfully.');
    }


}







// //  // Request Form Handling

//  public function request(){
        

    
    
    
//     // Fetch the requests with sorting and filtering
//     $requests = RequestSupply::where('user_id')
//         ->orderBy('date','asc')
//         ->get();

//     // Return the view with the data
//     return view('user.request', compact('requests'));


  
// }
// public function store(Request $request)
// {
//     // Validate the request data
//     $validated = $request->validate([
//         'requester_name' => 'required|string',
//         'department' => 'required|string',
//         'item_name' => 'required|string',
//         'quantity' => 'required|integer|min:1',
//         'date' => 'required|date',
//         'description' => 'nullable|string',
//         'user_id' => 'required|exists:users,id', 
//     ]);

//     $requestData = $request->only(['department', 'item_name', 'quantity', 'date', 'description']);
    
//     // Automatically associate the authenticated user's ID with the request
//     $requestData['user_id'] = Auth::id();  // This will get the logged-in user's ID
//     $requestData['requester_name'] = Auth::user()->name;  // Get the logged-in user's name

//     // Store the request in the database
 
//     // Create a new request supply record
//     RequestSupply::create($validated);
    

    

//     // Redirect or return a response
//     return redirect()->route('user.request')->with('success', 'Request submitted successfully.');

    

// }
// //History 
// public function history(Request $request)
// {
//     // Get the authenticated user's ID
//     $userId = Auth::id();

//     // Default sort column and direction
//     $sortBy = $request->input('sort_by', 'date');
//     $sortDirection = $request->input('sort_direction', 'asc');

//     // Validate the sort inputs
//     $allowedSortBy = ['date', 'month', 'year'];
//     $allowedSortDirection = ['asc', 'desc'];

//     if (!in_array($sortBy, $allowedSortBy)) {
//         $sortBy = 'date';
//     }

//     if (!in_array($sortDirection, $allowedSortDirection)) {
//         $sortDirection = 'asc';
//     }

//     // Fetch the requests with sorting and filtering
//     $requests = RequestSupply::where('user_id', $userId)
//         ->orderBy($sortBy, $sortDirection)
//         ->get();

//     // Return the view with the data
//     return view('user.history', compact('requests', 'sortBy', 'sortDirection'));
// }


// public function status()
// {   
//     $userId = Auth::id();

//     // Default sort column and direction
//     $sortBy = request()->input('sort_by', 'date', 'status');
//     $sortDirection = request()->input('sort_direction', 'asc');

//     // Validate the sort inputs
//     $allowedSortBy = ['date', 'month', 'year'];
//     $allowedSortDirection = ['asc', 'desc'];

//     if (!in_array($sortBy, $allowedSortBy)) {
//         $sortBy = 'date';
//     }

//     if (!in_array($sortDirection, $allowedSortDirection)) {
//         $sortDirection = 'asc';
//     }

//     // Fetch the requests with sorting and filtering
//     $requests = RequestSupply::where('user_id', $userId)
//         ->orderBy($sortBy, $sortDirection)
//         ->get();
 
//     return view('user.request-status', compact('requests', 'sortBy', 'sortDirection'));
// // }