@extends('layouts.admin')

@section('content')
 
     
<div class="main-container h-full bg-gray-100 p-3">

    <div class="transition-opacity duration-300">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-2">
            <div>
                <h1 class="text-3xl font-extrabold text-dark">Dashboard</h1>
                <p class="text-base text-dark mt-1 mb-3">Manage and Track Monthly Requests and Inventory Status</p>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 mb-3">
            <!-- No. of Requests -->
            <div class="flex-grow min-w-[180px] basis-full sm:basis-[calc(50%-0.75rem)] md:basis-[calc(33.333%-0.75rem)] lg:basis-[calc(20%-0.75rem)] bg-white rounded-lg shadow-md px-3 py-4 flex items-center hover:shadow-lg transition-shadow">                
                <div class="text-blue-500 text-2xl bg-blue-100 p-3 rounded-full flex justify-center items-center h-12 w-12 mr-2">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-600 truncate">Total Requests</p>
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-800 truncate">{{ $totalRequesters }}</h3>
                        <span class="text-xs text-gray-500 ml-1 bg-blue-100 rounded-lg px-1 whitespace-nowrap">requests</span>
                    </div>
                </div>
            </div>
            
            <!-- Completed Requests -->
            <div class="flex-grow min-w-[180px] basis-full sm:basis-[calc(50%-0.75rem)] md:basis-[calc(33.333%-0.75rem)] lg:basis-[calc(20%-0.75rem)] bg-white rounded-lg shadow-md px-3 py-4 flex items-center hover:shadow-lg transition-shadow">                
                <div class="text-teal-600 text-2xl bg-green-100 p-3 rounded-full flex justify-center items-center h-12 w-12 mr-2">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-600 truncate">Completed</p>
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-800 truncate">{{ $totalCompleted }}</h3>
                        <span class="text-xs text-gray-500 ml-1 bg-green-100 rounded-lg px-1 whitespace-nowrap">done</span>
                    </div>
                </div>
            </div>
            
            <!-- Pending Requests -->
            <div class="flex-grow min-w-[180px] basis-full sm:basis-[calc(50%-0.75rem)] md:basis-[calc(33.333%-0.75rem)] lg:basis-[calc(20%-0.75rem)] bg-white rounded-lg shadow-md px-3 py-4 flex items-center hover:shadow-lg transition-shadow">
                <div class="text-yellow-500 text-2xl bg-yellow-100 p-3 rounded-full flex justify-center items-center h-12 w-12 mr-2">
                    <i class="fas fa-hourglass-half"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-600 truncate">Pending Request</p>
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-800 truncate">{{ $totalPending }}</h3>
                        <span class="text-xs text-gray-500 ml-1 bg-yellow-100 rounded-lg px-1 whitespace-nowrap">pending</span>
                    </div>
                </div>
            </div>
            
            <!-- Returns -->
            <div class="flex-grow min-w-[180px] basis-full sm:basis-[calc(50%-0.75rem)] md:basis-[calc(33.333%-0.75rem)] lg:basis-[calc(20%-0.75rem)] bg-white rounded-lg shadow-md px-3 py-4 flex items-center hover:shadow-lg transition-shadow">               
                <div class="text-blue-500 text-2xl bg-blue-100 p-3 rounded-full flex justify-center items-center h-12 w-12 mr-2">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-600 truncate">No. of Returns</p>
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-800 truncate">{{ $totalUsersReturned }}</h3>
                        <span class="text-xs text-gray-500 ml-1 bg-blue-100 rounded-lg px-1 whitespace-nowrap">returns</span>
                    </div>
                </div>
            </div>
            
            <!-- Completed Replacements -->
            <div class="flex-grow min-w-[180px] basis-full sm:basis-[calc(50%-0.75rem)] md:basis-[calc(33.333%-0.75rem)] lg:basis-[calc(20%-0.75rem)] bg-white rounded-lg shadow-md px-3 py-4 flex items-center hover:shadow-lg transition-shadow">
                <div class="text-blue-600 text-2xl bg-blue-100 p-3 rounded-full flex justify-center items-center h-12 w-12 mr-2">
                    <i class="fas fa-tools text-xl"></i>
                </div>
                <div class="min-w-0">
                    <p class="text-sm text-gray-600 truncate">Replacements</p>
                    <div class="flex items-center">
                        <h3 class="text-2xl font-bold text-gray-800 truncate">{{ $totalReplacementCompleted }}</h3>
                        <span class="text-xs text-gray-500 ml-1 bg-blue-100 rounded-lg px-1 whitespace-nowrap">replaced</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="dashboard-footer flex flex-col md:flex-row gap-3">

            <!-- Monthly Request Chart Section -->
            <div class="flex-1 min-w-0 bg-white rounded-lg shadow p-4">
                <div class="flex flex-col md:flex-row gap-4 mb-4">
                    <div class="w-full md:w-1/2">
                        <select id="yearFilter" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Years</option>
                        </select>
                    </div>
                    <div class="w-full md:w-1/2">
                        <select id="monthFilter" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Months</option>
                            <option value="1">January</option>
                            <option value="2">February</option>
                            <option value="3">March</option>
                            <option value="4">April</option>
                            <option value="5">May</option>
                            <option value="6">June</option>
                            <option value="7">July</option>
                            <option value="8">August</option>
                            <option value="9"></option>
                            <option value="10">February</option>

                            <!-- Other months -->
                        </select>
                    </div>
                </div>
                <div class="w-full min-h-[16rem] md:min-h-[20rem] lg:min-h-[24rem] bg-gray-50 p-3 rounded-sm overflow-hidden">
                    <canvas id="monthlyRequestChart" class="w-full h-full"></canvas>
                </div>
            </div>
            
            <!-- Department Chart Section -->
            <div class="flex-1 min-w-0 bg-white rounded-lg shadow p-4">
                <div class="flex justify-end mb-4">
                    <div class="w-full sm:w-[200px] lg:w-[250px]">
                        <select id="departmentFilter" class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Departments</option>
                            <option value="COT">COT</option>
                            <option value="COED">COED</option>
                            <option value="COHTM">COHTM</option>
                        </select>
                    </div>
                </div>
                
                <div class="w-full min-h-[16rem] md:min-h-[20rem] lg:min-h-[24rem] bg-gray-50 p-3 rounded-sm overflow-hidden">
                    <canvas id="departmentRequestChart" class="w-[100px] h-50"></canvas>
                </div>
                
            </div>
        </div>

    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Simulate data loading (replace with your actual data fetching logic)
    function loadDashboardData() {
        // This would be your actual data fetching code
        // For demonstration, we'll use a timeout
        setTimeout(function() {
            // Hide skeleton
            const skeleton = document.getElementById('skeleton-loading');
            skeleton.classList.add('hidden');
            
            // Show actual content with fade-in effect
            const content = document.getElementById('dashboard-content');
            content.classList.remove('hidden');
            setTimeout(() => content.classList.remove('opacity-0'), 10);
            
        }, 2000); // Adjust timeout to match your actual load time
    }
    
    // Start loading data
    loadDashboardData();
    });
    document.addEventListener("DOMContentLoaded", function () {
    var ctx = document.getElementById("monthlyRequestChart").getContext("2d");
    
    // Laravel data converted to JavaScript format
    var monthlyData = {!! json_encode($monthlyData) !!};
    var dailyData = {!! json_encode($dailyData) !!};
    var yearlyData = {!! json_encode($yearlyData ?? []) !!};

    // Populate year filter dropdown
    const yearFilter = document.getElementById('yearFilter');
    const monthFilter = document.getElementById('monthFilter');
    
    // Get unique years from your data (or use the yearlyData if available)
    const uniqueYears = Object.keys(yearlyData).length > 0 ? 
        Object.keys(yearlyData).sort((a, b) => b - a) : 
        ['2023', '2024']; // Fallback if no yearly data
    
    uniqueYears.forEach(year => {
        const option = document.createElement('option');
        option.value = year;
        option.textContent = year;
        yearFilter.appendChild(option);
    });

    // Initialize request data array for all 12 months (default 0)
    var requestData = new Array(12).fill(0);
    Object.keys(monthlyData).forEach(month => {
        requestData[month - 1] = monthlyData[month]; // Adjust for 0-based index
    });

    // Month labels
    var labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    // Create gradient effect
    var gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(0, 128, 128, 0.5)");
    gradient.addColorStop(1, "rgba(0, 128, 128, 0)");

    // Initialize Chart.js
    var monthlyChart = new Chart(ctx, {
        type: "line",
        data: {
            labels: labels,
            datasets: [{
                label: "Monthly Requests",
                data: requestData,
                backgroundColor: 'rgba(13, 148, 136, 0.4)',   // teal-600 with 70% opacity
                borderColor: 'rgba(15, 118, 110, 1)',  
                borderWidth: 2,
                pointBackgroundColor: "rgb(0, 128, 128)",
                pointBorderColor: "#fff",
                pointHoverBackgroundColor: "#fff",
                pointHoverBorderColor: "rgb(0, 128, 128)",
                fill: true,
                tension: 0.3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        color: "#333",
                        font: {
                            size: 14,
                            weight: "bold"
                        }
                    }
                },
                tooltip: {
                    backgroundColor: "rgba(0, 0, 0, 0.7)",
                    titleFont: { size: 14, weight: "bold" },
                    bodyFont: { size: 12 },
                    padding: 10
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: "#666", font: { size: 12, weight: "bold" } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: "rgba(200, 200, 200, 0.3)", lineWidth: 1 },
                    ticks: { color: "#666", font: { size: 12, weight: "bold" } }
                        }
                    }
                }
            });

        // Filter functionality
        async function updateChart() {
        const selectedYear = yearFilter.value;
        const selectedMonth = monthFilter.value;

        // For a production app, you should make an AJAX call here to get filtered data
        // Example:
        try {
            const response = await fetch(`/api/requests/filter?year=${selectedYear}&month=${selectedMonth}`);
            const filteredData = await response.json();
            
            // Update chart with the new data
            monthlyChart.data.datasets[0].data = filteredData.data;
            monthlyChart.data.datasets[0].label = filteredData.label || "Monthly Requests";
            monthlyChart.update();
        } catch (error) {
            console.error("Error fetching filtered data:", error);
            
            // Fallback to client-side filtering (basic implementation)
            let filteredData = [...requestData];
            
            if (selectedMonth !== 'all') {
                const monthIndex = parseInt(selectedMonth) - 1;
                filteredData = filteredData.map((value, index) => {
                    return index === monthIndex ? value : 0;
                });
            }

            monthlyChart.data.datasets[0].data = filteredData;
            
            // Update label
            if (selectedMonth !== 'all') {
                monthlyChart.data.datasets[0].label = `Requests for ${labels[parseInt(selectedMonth) - 1]}`;
                if (selectedYear !== 'all') {
                    monthlyChart.data.datasets[0].label += ` ${selectedYear}`;
                }
            } else if (selectedYear !== 'all') {
                monthlyChart.data.datasets[0].label = `Monthly Requests for ${selectedYear}`;
            } else {
                monthlyChart.data.datasets[0].label = "Monthly Requests";
            }

            monthlyChart.update();
                    }
                }

                // Add event listeners
                yearFilter.addEventListener('change', updateChart);
                monthFilter.addEventListener('change', updateChart);
            });
            const ctx = document.getElementById("departmentRequestChart").getContext("2d");

const departmentData = {!! json_encode($departmentData) !!};

const departmentNames = Object.keys(departmentData);
const requestCounts = Object.values(departmentData);

const departmentColors = {
    "COT": "#FDE68A",   // Yellow (bg-yellow-300)
    "COED": "#93C5FD",   // Blue (bg-blue-300)
    "COHTM": "#86EFAC"   // Green (bg-green-300)
};

const barColors = departmentNames.map(name => departmentColors[name] || '#999');
const total = requestCounts.reduce((sum, val) => sum + val, 0);

const centerTextPlugin = {
    id: 'centerText',
    beforeDraw(chart) {
        const { width, height, ctx } = chart;
        ctx.restore();
        ctx.font = "bold 1.5em sans-serif";
        ctx.textBaseline = "middle";
        ctx.fillStyle = "#111";
        const text = `Total: ${total}`;
        const textX = Math.round((width - ctx.measureText(text).width) / 2);
        const textY = height / 2;
        ctx.fillText(text, textX, textY);
        ctx.save();
    }
};

const departmentChart = new Chart(ctx, {
    type: "pie",
    data: {
        labels: departmentNames,
        datasets: [{
            label: "Requests Per Department",
            data: requestCounts,
            backgroundColor: barColors,
            borderColor: "#fff",
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    color: '#333',
                    font: {
                        size: 13,
                        weight: 'bold'
                    }
                }
            },
            tooltip: {
                backgroundColor: "rgba(0, 0, 0, 0.7)",
                callbacks: {
                    label: function (context) {
                        return `${context.label}: ${context.raw}`;
                    }
                }
            }
        }
    },
    plugins: [centerTextPlugin]
});

function resizeChart() {
    setTimeout(() => departmentChart.resize(), 300);
}

document.querySelector(".sidebar-toggle").addEventListener("click", resizeChart);
window.addEventListener("resize", resizeChart);



</script>



   



@endsection