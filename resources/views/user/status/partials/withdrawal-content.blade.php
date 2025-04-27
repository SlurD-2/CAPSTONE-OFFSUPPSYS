   {{-- Withdrawals-Content --}}
        <div class="gap-3 rounded-md gap-30 flex items-start">
        
            <div class="relative w-full flex-grow sm:flex-grow-0">
                <input type="text" id="searchInput"
                    class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Search request by name...">
                <svg class="absolute right-3 top-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16 10a6 6 0 1 0-12 0 6 6 0 0 0 12 0z"/>
                </svg>
            </div>
            <div class="">
                <button id="processReturnBtn"  class="px-2 py-2 bg-teal-500 text-white rounded hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fa-solid fa-circle-plus bg-transparent mr-2"></i>Return
                </button>
            </div>
        </div>

        @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-white rounded-lg overflow-hidden shadow-md">
            <div class="overflow-x-auto px-3">
            
        
                <table class="min-w-full bg-white divide-y divide-gray-200">
                    <thead class="bg-white tracking-wide">
                        <tr>
                            <th class="py-3 text-left text-sm text-gray-600 uppercase">Request ID</th>
                            <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Date &amp; Time</th>
                            <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Completed</th>
                            <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Item Name</th>
                            <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Quantity</th>
                            <th class="px-3 py-3 text-center text-sm text-gray-600 uppercase">Withdrawal</th>
                            <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Withdrawn by </th>
                            <th class="px-3 text-left text-sm text-gray-600 uppercase">Select</th>
                        </tr>
                    </thead>
                    <tbody id="requestTbody" class="bg-white divide-y divide-gray-200">
                        @php
                            $hasRequests = false;
                        @endphp
                        
                        
                        @foreach($requests as $request)
                        @if ($request->department === auth()->user()->department && 
                        $request->withdrawal_status === 'Completed' &&
                        \Carbon\Carbon::parse($request->completed_at)->isToday())
                    @php
                        $hasRequests = true;
                        $isReturned = $request->returnRequests()->exists();
                    @endphp
                                <tr>
                                    <td class="py-3 whitespace-nowrap text-sm font-semibold text-dark uppercase">
                                    
                                        REQ#: {{ $request->id }}
                                    </td>
                                    <td class="px-3 text-sm text-dark capitalize">
                                        {{ \Carbon\Carbon::parse($request->datetime)->format('d/m/Y') }}
                                        <br>
                                        {{ \Carbon\Carbon::parse($request->datetime)->format('h:i A') }}
                                    </td>  

                                    <td class="px-3 py-3 text-sm">
                                        @if($request->completed_at)
                                            <div class="flex flex-col">
                                                <span class="text-dark font-sm">
                                                    {{ \Carbon\Carbon::parse($request->completed_at)->format('d/m/Y') }}
                                                </span>
                                                <span class="text-dark text-sm mt-0.5">
                                                    {{ \Carbon\Carbon::parse($request->completed_at)->format('h:i A') }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>

                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-semibold text-dark capitalize">{{ $request->item_name }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap text-sm text-dark capitalize">{{ $request->quantity }}</td>
                            
                                    <td class="px-3 py-4 whitespace-nowrap text-sm font-medium flex justify-center items-center space-x-2">
                                        <span class="inline-flex px-2 py-1 leading-5 font-semibold rounded-full
                                            @if($request->withdrawal_status === 'Processing')
                                                bg-yellow-100 text-yellow-800
                                            @elseif($request->withdrawal_status === 'Ready to Pick Up')
                                                bg-blue-100 text-blue-800 
                                            @else
                                                bg-green-100 text-green-800
                                            @endif">
                                            {{ $request->withdrawal_status }}
                                        </span>
                                    
                                        @if($request->withdrawal_status === 'Ready to Pick Up')
                                            <!-- Alert Beating Effect -->
                                            <div class="w-3 h-3 bg-red-500 rounded-full animate-ping"></div>
                                        @endif
                                    </td>
                                    
                                    
                                
                                    
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-dark capitalize">{{ $request->withdrawn_by }}</td>
                                    <td class="px-3 py-4 whitespace-nowrap">
                                        @if(!$isReturned)
                                            <input type="radio" name="selectedRequest" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" 
                                                value="{{ $request->id }}" 
                                                data-item-name="{{ $request->item_name }}"
                                                data-quantity="{{ $request->quantity }}"
                                                data-date="{{ $request->datetime }}">
                                        @else
                                            <span class="text-gray-400">Returned</span>
                                        @endif
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        
                        @if(!$hasRequests)
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                    <div class="flex flex-col items-center justify-center py-8">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <span class="mt-2 text-lg font-medium text-gray-600">No requests found</span>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>

                <!-- Return Modal -->
                <div id="returnModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
                    
                    <div class="absolute right-0" id="modalBackdrop">
                            
                    </div>
                        
                    <div id="rightModal" class="fixed right-0 top-10 bottom-10 h-100 w-full max-w-md overflow-y-auto transform transition-transform duration-300 ease-in-out translate-x-0 bg-white shadow-xl z-50">
                        <div class="h-full overflow-y-auto p-4">
                            <!-- Your modal content here -->
                    
                                <h3 class="text-lg font-bold text-gray-900 mb-3 uppercase">Process Return</h3>
                                
                                <!-- Item Details -->
                                <div class="mb-6">
                                    <h4 class="text-md font-bold text-gray-700 mb-2">Item Details</h4>
                                    <div class="bg-gray-50 rounded-md p-3">
                                        <div class="flex items-center">
                                            <p class="text-sm text-dark font-bold">Item Name: </p>
                                            <p id="modalItemName" class="ml-2 font-sm"></p>
                                        </div>
                                        <div class="flex items-center">
                                            <p class="text-sm text-dark font-bold">Quantity: </p>
                                            <p id="modalQuantity" class="ml-2 font-medium"></p>
                                        </div>
                                    </div>
                                </div>

                                <form action="{{ route('user.return.store') }}" method="POST" id="returnForm" enctype="multipart/form-data">
                                    @csrf
                                    <!-- Hidden Inputs -->
                                    <input type="hidden" name="request_id" id="requestIdInput">
                                    <input type="hidden" id="userId" name="user_id" value="{{ Auth::id() }}">
                                    <input type="hidden" id="requesterName" name="requester_name" value="{{ Auth::user()->name }}">
                                    <input type="hidden" id="department" name="department" value="{{ Auth::user()->department }}">
                                    <input type="hidden" id="itemName" name="item_name">
                                
                                    <!-- Condition -->
                                    <div class="mb-4">
                                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-1">Condition</label>
                                        <select id="condition" name="condition" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                            <option value="">Select condition</option>
                                            <option value="defective">Defective</option>
                                            <option value="damaged">Damaged</option>
                                   
                                        </select>
                                    </div>
                                
                                    <!-- Quantity -->
                                    <div class="mb-4">
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-1">Quantity to Return</label>
                                        <input type="number" id="quantity" name="quantity" min="1" required
                                            class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md">
                                    </div>
                                
                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                        <textarea id="description" name="description" rows="3" class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border border-gray-300 rounded-md"></textarea>
                                    </div>
                                    
                                    <!-- Image Proof -->
                                    <div class="mb-4">
                                        <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-1">Upload Proof (Image)</label>
                                        <div class="mt-1 flex items-center">
                                            <input type="file" id="proof_image" name="proof_image" accept="image/*" class="block w-full text-sm text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-md file:border-0
                                                file:text-sm file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100">
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">Upload an image showing the item's condition (max 5MB)</p>
                                    </div>

                                    <!-- Return Date -->
                                    <div class="mb-4">
                                        <label for="return_date" class="block text-sm font-medium text-gray-700 mb-1">Return Date</label>
                                        <input type="datetime-local" id="return_date" name="return_date" required class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                    </div>
                                
                                    <!-- Form Actions -->
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" id="cancelReturnBtn" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Cancel
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md text-sm font-medium hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            Submit Return
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </div>

            </div>
        </div>


    <script>
    document.addEventListener('DOMContentLoaded', function () {


    const radioButtons = document.querySelectorAll('input[name="selectedRequest"]');
    const processReturnBtn = document.getElementById('processReturnBtn');

    const now = new Date();
    document.getElementById('return_date').value = now.toISOString().slice(0, 16);

    document.getElementById('returnForm').addEventListener('submit', function (e) {
            e.preventDefault();
            
            // Get the file input element
            const proofImageInput = document.getElementById('proof_image');
            
            // Check if a file is selected
            if (!proofImageInput.files || proofImageInput.files.length === 0) {
                // Show SweetAlert message
                Swal.fire({
                    title: 'Proof Required',
                    text: 'Please attach an image proof of the item condition before submitting.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#3085d6',
                });
                return; // Exit the function and prevent form submission
            }
            
            // Continue with form submission if validation passes
            const returnQuantity = parseInt(document.getElementById('quantity').value);
            const maxQuantity = parseInt(document.getElementById('quantity').max);

            if (isNaN(returnQuantity) || returnQuantity < 1 || returnQuantity > maxQuantity) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Invalid Quantity', 
                    text: `Please enter a valid quantity between 1 and ${maxQuantity}` 
                });
                return;
            }

            const returnDate = new Date(document.getElementById('return_date').value);
            const currentDate = new Date();
            const hoursLate = (currentDate - returnDate) / (1000 * 60 * 60);

            if (hoursLate > 24) {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Late Return', 
                    text: 'Cannot submit return because it is more than 24 hours late' 
                });
                return;
            }

            // Disable submit button to prevent multiple submissions
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = `<span class="flex items-center justify-center">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>`;

            // Prepare form data
            const formData = new FormData(this);

            // Submit the form via AJAX
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) return response.json();
                const text = await response.text();
                if (response.ok) return { success: true, message: 'Return submitted successfully!' };
                const doc = new DOMParser().parseFromString(text, 'text/html');
                const errorMsg = doc.querySelector('.alert-danger, .error, [role="alert"]')?.textContent.trim() || 'An error occurred while processing your request';
                throw new Error(errorMsg);
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({ 
                        icon: 'success', 
                        title: 'Success!', 
                        text: data.message 
                    }).then(() => {
                        this.reset();
                        document.getElementById('returnModal').classList.add('hidden');
                        window.location.reload();
                    });
                } else {
                    throw new Error(data.message || 'Error submitting return');
                }
            })
            .catch(error => {
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Error', 
                    text: error.message || 'An error occurred while submitting the return' 
                });
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Submit Return';
            });
        });

    radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
    processReturnBtn.disabled = !this.checked;
    });
    });

    const modal = document.getElementById('returnModal');
    const cancelReturnBtn = document.getElementById('cancelReturnBtn');

    processReturnBtn.addEventListener('click', function () {
    const selectedRadio = document.querySelector('input[name="selectedRequest"]:checked');
    if (selectedRadio) {
    document.getElementById('modalItemName').textContent = selectedRadio.dataset.itemName;
    document.getElementById('modalQuantity').textContent = selectedRadio.dataset.quantity;

    document.getElementById('requestIdInput').value = selectedRadio.value;
    document.getElementById('userId').value = selectedRadio.dataset.userId;
    document.getElementById('requesterName').value = selectedRadio.dataset.requesterName;
    document.getElementById('department').value = selectedRadio.dataset.department;
    document.getElementById('itemName').value = selectedRadio.dataset.itemName;

    const quantityInput = document.getElementById('quantity');
    quantityInput.value = selectedRadio.dataset.quantity;
    quantityInput.max = selectedRadio.dataset.quantity;

    modal.classList.remove('hidden');
    }
    });

    cancelReturnBtn.addEventListener('click', () => modal.classList.add('hidden'));
    modal.addEventListener('click', e => { if (e.target === modal) modal.classList.add('hidden'); });

    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');

    function switchTab(tabName) {
    tabContents.forEach(content => content.classList.add('hidden'));
    document.getElementById(`${tabName}-content`)?.classList.remove('hidden');
    tabButtons.forEach(btn => btn.classList.remove('text-blue-700', 'border-b-2', 'border-blue-500', 'active-tab', 'text-gray-600'));
    document.querySelector(`[data-tab="${tabName}"]`)?.classList.add('text-blue-700', 'border-b-2', 'border-blue-500', 'active-tab');
    window.history.pushState({ tab: tabName }, '', window.location.pathname + '?tab=' + tabName);
    }

    tabButtons.forEach(button => button.addEventListener('click', function () {
    switchTab(this.getAttribute('data-tab'));
    }));

    const tabParam = new URLSearchParams(window.location.search).get('tab');
    switchTab(['requests', 'withdrawals', 'returns'].includes(tabParam) ? tabParam : 'requests');

    window.addEventListener('popstate', function (event) {
    if (event.state?.tab) switchTab(event.state.tab);
    });

    window.verifyReturn = function (returnId) {
    if (confirm('Are you sure you want to verify this return?')) {
    fetch(`/admin/returns/${returnId}/verify`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => data.success ? location.reload() : alert('Error verifying return'))
    .catch(error => {
        console.error('Error:', error);
        alert('Error verifying return');
    });
    }
    };

    window.copyRequestId = function (requestId) {
    navigator.clipboard.writeText(requestId)
    .then(() => alert('Request ID copied to clipboard: ' + requestId))
    .catch(err => console.error('Failed to copy: ', err));
    };

    window.showPrintModal = showPrintModal;
    window.closePrintModal = closePrintModal;
    window.printRequest = printRequest;

    });
</script>
