

@extends('layouts.admin')

@section('content')

<div class="main-container bg-gray-100 p-3 h-100">
    <!-- Main Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
        <div>
            <h1 class="text-3xl font-extrabold text-dark">Inventory Reports</h1>
            <p class="text-base text-dark mt-1 mb-3">Manage and Track Monthly Requests and Inventory Status</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-3">
       <h2 class="text-xl font-semibold text-gray-800">Monthly Summary</h2>
        
        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.monthly-reports') }}" 
              class="flex flex-col justify-end sm:flex-row gap-3 w-full md:w-auto">
            <div class="flex-1 min-w-[120px]">
                <label for="month" class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                <select name="month" id="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @foreach (range(1, 12) as $month)
                        <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $month, 1)->format('F') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex-1 min-w-[120px]">
                <label for="year" class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                <select name="year" id="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @foreach (range(2000, now()->year) as $year)
                        <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <button type="submit" class="mt-auto bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z" clip-rule="evenodd" />
                </svg>
                Apply
            </button>
        </form>
    </div>

    <!-- Stats Cards -->
    <div class="flex flex-col sm:flex-row gap-3 mb-3">
        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-16px)] min-w-[180px] flex flex-col items-center justify-center hover:shadow-lg transition-shadow">

            <div class="text-center">
                <p class="text-sm font-medium text-dark-800">Monthly Requesters</p>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalRequesters }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-16px)] min-w-[180px] flex flex-col items-center justify-center hover:shadow-lg transition-shadow">
            <div class="text-center">
                <p class="text-sm font-medium text-dark">Active Users</p>
                <p class="text-3xl font-bold text-teal-600 mt-2">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-16px)] min-w-[180px] flex flex-col items-center justify-center hover:shadow-lg transition-shadow">
            <div class="text-center">
                <p class="text-sm font-medium text-dark-800">Quantity Withdrawn</p>
                <p class="text-3xl font-bold text-purple-600 mt-2">{{ $totalCompletedItems }}</p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-16px)] min-w-[180px] flex flex-col items-center justify-center hover:shadow-lg transition-shadow">
            <div class="text-center">
                <p class="text-sm font-medium text-dark-800">Monthly Returns</p>
                <p class="text-3xl font-bold text-amber-600 mt-2">{{ $totalReturns }}</p>
            </div>
        </div>

        
        <div class="bg-white rounded-lg shadow-md p-4 w-full sm:w-[calc(50%-12px)] md:w-[calc(33.333%-16px)] lg:w-[calc(20%-16px)] min-w-[180px] flex flex-col items-center justify-center hover:shadow-lg transition-shadow">
            <div class="text-center">
                <p class="text-sm font-medium text-dark">Monthly Replacement</p>
                <p class="text-3xl font-bold text-teal-600 mt-2">{{ $totalReplacementCompleted}}</p>
            </div>
        </div>



    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 gap-3">

      
        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
            <!-- Withdrawals Chart -->
            <div class="bg-white rounded-xl shadow-md p-6 ">
                <h2 class="text-xl font-semibold text-gray-800 pb-3">Withdrawal Analysis</h2>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6 ">
          
                    
                    <form id="withdrawalsFilterForm" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                        <div class="flex-1 min-w-[120px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="flex-1 min-w-[120px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                            <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">All</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="mt-auto bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                            Update
                        </button>
                    </form>
                </div>
                
                <div class="relative h-80 ">
                    <canvas id="withdrawalsChart" class="h-full w-full"></canvas>
                </div>
            </div>

            <!-- Returns Chart -->
            <div class="bg-white rounded-xl shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 pb-3">Returns Analysis</h2>
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
               
                    
                    <form id="returnsFilterForm" class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">

                        <div class="flex-1 min-w-[120px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Year</label>
                            <select name="year" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                @for($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ $y == date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="flex-1 min-w-[120px]">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Month</label>
                            <select name="month" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <option value="">All</option>
                                @foreach(range(1, 12) as $month)
                                    <option value="{{ $month }}">
                                        {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="mt-auto bg-teal-600 hover:bg-teal-700 text-white px-3 py-2 rounded-lg transition">
                            Update
                        </button>
                        
                    </form>

                </div>
                
                <div class="relative h-90">
                    <canvas id="returnsChart"  class="h-full w-full"></canvas>
                </div>

                

            </div>

            
        </div>
     
   </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('returnsChart').getContext('2d');
    let chart;

    function loadReturnsData(year, month = null) {
        showLoading(true);
        
        fetch(`/api/returns/monthly?year=${year}${month ? `&month=${month}` : ''}`)
            .then(response => response.json())
            .then(data => {
                if (chart) chart.destroy();
                
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.month_name),
                        datasets: [{
                            label: 'Items Returned',
                            data: data.map(item => item.count),
                            backgroundColor: 'rgba(13, 148, 136, 0.4)',   // teal-600 with 70% opacity
                            borderColor: 'rgba(15, 118, 110, 1)',         // teal-700 fully opaque

                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: (context) => 
                                        `${context.parsed.y} ${context.parsed.y === 1 ? 'return' : 'returns'}`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'No. of Returns'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            })
            .finally(() => showLoading(false));
    }

    function showLoading(show) {
        const loader = document.getElementById('chartLoading');
        if (show) {
            if (!loader) {
                const div = document.createElement('div');
                div.id = 'chartLoading';
                div.className = 'absolute inset-0 bg-white bg-opacity-80 flex items-center justify-center';
                div.innerHTML = '<div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>';
                ctx.canvas.parentNode.appendChild(div);
            }
        } else if (loader) {
            loader.remove();
        }
    }

    // Initial load
    loadReturnsData(new Date().getFullYear());

    // Form submission
    document.getElementById('returnsFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        loadReturnsData(formData.get('year'), formData.get('month'));
    });
    });
    document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('withdrawalsChart').getContext('2d');
    let chart;

    function loadChart(year, month = null) {
        fetch(`/api/withdrawals/monthly-completed?year=${year}${month ? `&month=${month}` : ''}`)
            .then(response => response.json())
            .then(data => {
                if (chart) chart.destroy();
                
                chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.map(item => item.month_name),
                        datasets: [{
                            label: 'Completed Withdrawals',
                            data: data.map(item => item.count),
                            backgroundColor: 'rgba(13, 148, 136, 0.4)',   // teal-600 with 70% opacity
                            borderColor: 'rgba(15, 118, 110, 1)',         // teal-700 fully opaque

                            borderWidth: 2,
                            tension: 0.3,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            tooltip: {
                                callbacks: {
                                    label: (context) => `${context.parsed.y} withdrawals`
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'No. of Withdrawals'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Month'
                                }
                            }
                        }
                    }
                });
            });
    }

    // Initial load
    loadChart(new Date().getFullYear());

    // Form submission
    document.getElementById('withdrawalsFilterForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        loadChart(formData.get('year'), formData.get('month'));
    });
    });

    // Fetch the data passed from the controller and convert it to JavaScript variables
    var itemNames = @json($itemNames);  // This will hold the item names
    var quantities = @json($quantities);  // This will hold the stock quantities
    
    // Define the threshold for low stock
    var lowStockThreshold = 40;
    
    // Glassy color styles for red and teal
    var glassyRed = 'rgba(255, 0, 0, 0.5)'; // Semi-transparent red
    var glassyTeal = 'rgba(0, 128, 128, 0.5)'; // Semi-transparent teal
    
    // Separate the data into two categories: low and high
    var lowStockData = quantities.map(quantity => quantity <= lowStockThreshold ? quantity : null);
    var highStockData = quantities.map(quantity => quantity > lowStockThreshold ? quantity : null);
    
    // Adjust for high resolution
    function setCanvasResolution(canvas) {
        const ctx = canvas.getContext('2d');
        const pixelRatio = window.devicePixelRatio || 1;
    
        // Set canvas width and height to match pixel ratio
        canvas.width = canvas.offsetWidth * pixelRatio;
        canvas.height = canvas.offsetHeight * pixelRatio;
    
        // Scale the drawing context
        ctx.scale(pixelRatio, pixelRatio);
    }
    
    // Select the canvas
    const canvas = document.getElementById('stockChart');
    setCanvasResolution(canvas);
    
    // Create the chart
    var ctx = canvas.getContext('2d');
    var stockChart = new Chart(ctx, {
        type: 'line',  // Use a bar chart
        data: {
            labels: itemNames,  // Use item names as labels for the X-axis
            datasets: [
                {
                    label: 'Low Stocks',  // Label for low stock (red)
                    data: lowStockData,  // Data for low stock
                    backgroundColor: glassyRed,  // Glassy effect for low stock
                    borderColor: 'rgba(255, 0, 0, 0.8)',  // Border with higher opacity
                    borderWidth: 1,  // Border thickness
                    borderRadius: 5,  // Rounded corners
                    barThickness: 15  // Bar width
                },
                {
                    label: 'High Stocks',  // Label for high stock (teal)
                    data: highStockData,  // Data for high stock
                    backgroundColor: glassyTeal,  // Glassy effect for high stock
                    borderColor: 'rgba(0, 128, 128, 0.8)',  // Border with higher opacity
                    borderWidth: 1,  // Border thickness
                    borderRadius: 5,  // Rounded corners
                    barThickness: 15  // Bar width
                }
            ]
        },
        options: {
            responsive: true,  // Make the chart responsive
            maintainAspectRatio: false,  // Allow flexible resizing
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: '#333',  // Dark gray for legend text
                        font: {
                            size: 14,  // Adjust font size
                            weight: 'bold'
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,  // Ensure that the Y-axis starts from zero
                    ticks: {
                        color: '#666',  // Medium gray for Y-axis labels
                        font: {
                            size: 15, // Adjust font size
                            weight: 'bold'
                        }
                    },
                    grid: {
                        display: false  // Hide vertical grid lines
                    }
                },
                x: {
                    ticks: {
                        color: '#666',  // Medium gray for X-axis labels
                        font: {
                            size: 12, // Adjust font size
                
                        },
                        autoSkip: false,  // Ensure all labels are displayed
                    },
                    grid: {
                        color: '#ddd'  // Light gray for grid lines
                    }
                }
            }
        }
    });
    


</script>

 

       

   



@endsection

