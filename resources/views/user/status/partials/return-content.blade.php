 {{-- Returns-Content --}}
 <div >
    
    @if (session('success'))
    <div class="mb-6 p-4 bg-green-100 text-green-700 rounded-md">
        {{ session('success') }}
    </div>
    @endif

            
    <div class=" gap-2 rounded-md gap-30 flex items-start">
   
        <div class="relative w-full flex-grow sm:flex-grow-0">
          <input type="text" id="searchInput"
                class="w-full sm:w-64 px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Search request by name...">
          <svg class="absolute right-3 top-3 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M16 10a6 6 0 1 0-12 0 6 6 0 0 0 12 0z"/>
          </svg>
        </div>

    </div>

    <div class="bg-white rounded-lg overflow-hidden shadow-md">
        <div class="overflow-x-auto px-3">
        
       
            <table class="min-w-full bg-white divide-y divide-gray-200">
                <thead class="bg-white tracking-wide">
                    <th class="py-3 text-left text-sm text-gray-600 uppercase">Return ID</th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Item Name</th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Qty. Returned</th>

                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Return Date</th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Condition</th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Status</th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Replacement </th>
                    <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($returns as $return)
                        @if($return->replacement_status !== 'completed')
                            <tr>
                                <td class="px-2 py-4 whitespace-nowrap text-sm text-dark">{{ $return->request_id }}</td>
                                
                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark capitalize">{{ $return->item_name }}</td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark capitalize">{{ $return->quantity }}</td>

                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark capitalize">
                                    {{ \Carbon\Carbon::parse($return->return_date)->format('m/d/Y') }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark capitalize">{{ $return->condition }}</td>

                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark capitalize">
                                    
                                    {{ $return->return_status }}
                                </td>
                                <td class="px-3 py-3 whitespace-nowrap text-sm textdark text-center capitalize">
                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full">
                                        {{ $return->replacement_status }}
                                    </span>
                                </td>

                    
                            </tr>
                        @endif
                    @endforeach
                    
                
                </tbody>
            </table>
        </div>

   </div>

</div>

    