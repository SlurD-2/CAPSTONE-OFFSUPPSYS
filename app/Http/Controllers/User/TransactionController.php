<?php

namespace App\Http\Controllers\User;

use App\Models\Stock;

use Illuminate\Http\Request;
use App\Models\RequestSupply;
use App\Models\ReturnRequest;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TransactionController extends Controller
{
    //
 
        
    public function withdrawal()
    {
        $user_id = Auth::id();
    
        $requests = RequestSupply::where('user_id', $user_id)
            ->where('withdrawal_status', 'Ready to Pick Up')  // Only "Ready to Pick Up" status
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('user.withdrawal', compact('requests'));
    }

    public function return()
    {
        // Fetch only completed requests
        $requests = RequestSupply::where('withdrawal_status', 'completed')->get();
        
        return view('user.return', compact('requests'));
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
    

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'request_id' => 'required|exists:request_supplies,id',
                'condition' => 'required|in:defective,damaged,other',
                'quantity' => 'required|integer|min:1',
                'description' => 'required|string|max:500',
                'return_date' => 'required|date',
                'proof_image' => 'nullable|image|max:5120', // 5MB max
            ]);
    
            // Get the original request
            $originalRequest = RequestSupply::findOrFail($validated['request_id']);
    
            // Validate return quantity doesn't exceed original quantity
            if ($validated['quantity'] > $originalRequest->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Return quantity cannot exceed original request quantity'
                ], 422);
            }
    
            // Check if return is more than 24 hours late
            $returnDate = \Carbon\Carbon::parse($validated['return_date']);
            $currentDate = \Carbon\Carbon::now();
            
            if ($currentDate->diffInHours($returnDate, false) < -24) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot submit return because it is more than 24 hours late'
                ], 422);
            }
    
            // Handle image upload
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
    
            return response()->json([
                'success' => true,
                'message' => 'Return submitted successfully!'
            ]);
    
        } catch (\Exception $e) {
            // Ensure we always return JSON even for unexpected errors
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

