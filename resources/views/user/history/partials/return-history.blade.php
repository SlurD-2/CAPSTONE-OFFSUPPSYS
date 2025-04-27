

  <!-- Month Filter Controls -->
  <div class="flex justify-between items-center mb-4">
    .
  </div>

  {{-- Returns-Content --}}
  <div id="return-content" class="bg-white shadow rounded-md mx-auto ">
      @if(!$requests->contains('withdrawal_status', 'Completed'))
      <p class="text-center text-gray-500">No completed withdrawals found.</p>
      @else
      <div id="noDataMessageReturn" class="hidden text-center p-5 text-gray-500">
          No matching requests found.
      </div>

      <div class="bg-white rounded-md overflow-hidden shadow-md">
          <div class="overflow-x-auto px-3">
              <table class="min-w-full bg-white divide-y divide-gray-200">
                  <thead class="bg-white tracking-wide">
                      <tr>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Item Name</th>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Qty Returned</th>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Return Date</th>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Condition</th>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Status</th>
                          <th class="px-3 py-3 text-left text-sm text-gray-600 uppercase">Replacement</th>
                      </tr>
                  </thead>
                  <tbody id="returnTableBody" class="bg-white divide-y divide-gray-200">
                      @foreach($returns as $return)
                          @if($return->replacement_status === 'completed')
                          <tr>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 capitalize font-semibold">{{ $return->item_name }}</td>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700">{{ $return->quantity }}</td>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700">
                                  {{ \Carbon\Carbon::parse($return->return_date)->format('m/d/Y') }}
                              </td>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $return->condition }}</td>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-700 capitalize">{{ $return->return_status }}</td>
                              <td class="px-3 py-3 whitespace-nowrap text-sm text-dark capitalize">
                                  <span class="inline-flex px-2 py-1 leading-5 font-semibold rounded-full
                                      {{ $return->replacement_status === 'completed' ? 'bg-teal-100 text-teal-800' : '' }}">
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
      @endif
  </div>

   

  
  <script>
      document.addEventListener('DOMContentLoaded', function() {
       const tabButtons = document.querySelectorAll('.tab-button');
  
        // Function to activate a tab by ID
        function activateTab(tabId) {
        // Find the button that controls this tab
        const button = document.querySelector(`.tab-button[data-tab="${tabId}"]`);
        const content = document.getElementById(tabId);
        
        if (!button || !content) return false;
        
        // Update active tab styling
        tabButtons.forEach(btn => {
            btn.classList.remove('text-blue-700', 'border-b-2', 'border-blue-500', 'active-tab');
            btn.classList.add('text-gray-600');
        });
        
        button.classList.add('text-blue-700', 'border-b-2', 'border-blue-500', 'active-tab');
        button.classList.remove('text-gray-600');
        
        // Hide all tab contents
        document.querySelectorAll('[id$="-content"]').forEach(content => {
            content.style.display = 'none';
        });
        
        // Show the selected content
        content.style.display = 'block';
        
        // Update URL hash without adding to browser history
        if (window.location.hash.substring(1) !== tabId) {
            history.replaceState(null, null, `#${tabId}`);
        }
        
        // Store the active tab in sessionStorage
        sessionStorage.setItem('activeTab', tabId);
        
        return true;
      }
    
      // Add click event listeners to each tab button
      tabButtons.forEach(button => {
          button.addEventListener('click', function() {
              const targetTabId = this.getAttribute('data-tab');
              activateTab(targetTabId);
          });
      });
    
      // Determine which tab to activate on page load
      function determineInitialTab() {
      // 1. Check URL hash first
      const hashTabId = window.location.hash.substring(1);
      if (hashTabId && document.getElementById(hashTabId)) {
          return hashTabId;
      }
      
      // 2. Check sessionStorage next
      const storedTabId = sessionStorage.getItem('activeTab');
      if (storedTabId && document.getElementById(storedTabId)) {
          return storedTabId;
      }
      
      // 3. Fall back to first tab
      if (tabButtons.length > 0) {
          return tabButtons[0].getAttribute('data-tab');
      }
        
        return null;
      }
    
      // Activate the appropriate tab on page load
      const initialTabId = determineInitialTab();
      if (initialTabId) {
          activateTab(initialTabId);
      }
      
      // Handle browser back/forward navigation
      window.addEventListener('hashchange', function() {
          const tabId = window.location.hash.substring(1);
          if (tabId && document.getElementById(tabId)) {
              activateTab(tabId);
            }
         });
      });
        // Tab switching functionality
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('[id$="-content"]');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Update active tab button
            tabButtons.forEach(btn => {
                btn.classList.remove('text-blue-700', 'border-blue-500', 'active-tab');
                btn.classList.add('text-gray-600');
            });
            button.classList.add('text-blue-700', 'border-blue-500', 'active-tab');
            button.classList.remove('text-gray-600');
            
            // Show selected tab content
            const tabId = button.getAttribute('data-tab');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(tabId).classList.remove('hidden');
            
            // Update month filter for the current tab
            updateMonthFilterOptions();
            filterByMonth(getCurrentMonth());
        });
    });
    
    // Get current month in YYYY-MM format
    function getCurrentMonth() {
        const now = new Date();
        return `${now.getFullYear()}-${String(now.getMonth() + 1).padStart(2, '0')}`;
    }
    
    // Update the month filter dropdown with available months
    function updateMonthFilterOptions() {
        const monthFilter = document.getElementById('month-filter');
        const activeTab = document.querySelector('.active-tab').getAttribute('data-tab');
        const tableBodyId = activeTab === 'withdrawal-content' ? 'withdrawalTableBody' : 'returnTableBody';
        const rows = document.getElementById(tableBodyId).querySelectorAll('tr');
        
        // Clear existing options except "All Months"
        while (monthFilter.options.length > 1) {
            monthFilter.remove(1);
        }
        
        // Collect unique months from the data
        const months = new Set();
        rows.forEach(row => {
            const month = row.getAttribute('data-month');
            if (month) months.add(month);
        });
        
        // Convert to array and sort by date (newest first)
        const sortedMonths = Array.from(months).sort().reverse();
        
        // Add options to the select
        sortedMonths.forEach(month => {
            const date = new Date(`${month}-01`);
            const option = document.createElement('option');
            option.value = month;
            option.textContent = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
            monthFilter.appendChild(option);
        });
        
        // Update current month indicator
        const currentMonth = getCurrentMonth();
        const currentMonthText = new Date(`${currentMonth}-01`).toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
        document.getElementById('current-month-indicator').textContent = `Current Month: ${currentMonthText}`;
    }
    
    // Filter rows by month
    function filterByMonth(month) {
        const activeTab = document.querySelector('.active-tab').getAttribute('data-tab');
        const tableBodyId = activeTab === 'withdrawal-content' ? 'withdrawalTableBody' : 'returnTableBody';
        const noDataMessageId = activeTab === 'withdrawal-content' ? 'noDataMessageWithdrawal' : 'noDataMessageReturn';
        const rows = document.getElementById(tableBodyId).querySelectorAll('tr');
        
        let hasVisibleRows = false;
        
        rows.forEach(row => {
            const rowMonth = row.getAttribute('data-month');
            const isCompleted = row.getAttribute('data-status') === 'Completed' || row.getAttribute('data-status') === 'completed';
            
            if (month === 'all' || rowMonth === month) {
                row.classList.remove('hidden');
                // Highlight current month's completed entries
                if (month === getCurrentMonth() && isCompleted) {
                    row.classList.add('bg-blue-50');
                } else {
                    row.classList.remove('bg-blue-50');
                }
                hasVisibleRows = true;
            } else {
                row.classList.add('hidden');
                row.classList.remove('bg-blue-50');
            }
        });
        
        // Show/hide no data message
        document.getElementById(noDataMessageId).classList.toggle('hidden', hasVisibleRows);
    }
    
    // Initialize month filter
    updateMonthFilterOptions();
    filterByMonth(getCurrentMonth());
    
   
    
    // Handle month filter change
    monthFilter.addEventListener('change', function() {
        filterByMonth(this.value);
    });
  </script>
    



