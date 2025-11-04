@extends('layouts.app')

@section('title', 'Reports')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Reports</h1>
        <p class="text-gray-600 mt-2">Analytics and performance insights</p>
    </div>

    <!-- Date Filter -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex gap-2">
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <input type="date" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">Export PDF</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">Export Excel</button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">$345,000</p>
            <p class="text-green-600 text-xs mt-2">↑ 15% vs last period</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm font-medium">Completed Projects</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">16</p>
            <p class="text-green-600 text-xs mt-2">↑ 25% vs last period</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm font-medium">Avg. Project Duration</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">45</p>
            <p class="text-yellow-600 text-xs mt-2">days (↓ 5% improvement)</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <p class="text-gray-600 text-sm font-medium">On-Time Delivery</p>
            <p class="text-3xl font-bold text-gray-900 mt-2">94%</p>
            <p class="text-green-600 text-xs mt-2">↑ 3% vs last period</p>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Revenue Trend</h2>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-600 text-sm">Chart will display here</p>
                </div>
            </div>
        </div>

        <!-- Project Status Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Status Distribution</h2>
            <div class="h-64 bg-gray-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="text-gray-600 text-sm">Chart will display here</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Project Performance Table -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Top Performing Projects -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Top Performing Projects</h2>
            </div>

            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Project</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">ROI</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">Mobile App Development</td>
                        <td class="px-6 py-3 text-sm text-green-600 font-semibold">+125%</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Excellent</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">Website Redesign</td>
                        <td class="px-6 py-3 text-sm text-green-600 font-semibold">+98%</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Excellent</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">Data Analytics Platform</td>
                        <td class="px-6 py-3 text-sm text-blue-600 font-semibold">+45%</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Good</span>
                        </td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3 text-sm font-medium text-gray-900">System Integration</td>
                        <td class="px-6 py-3 text-sm text-yellow-600 font-semibold">+15%</td>
                        <td class="px-6 py-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Fair</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Resource Utilization -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Resource Utilization</h2>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Development Team</span>
                        <span class="text-sm font-bold text-gray-900">85%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full" style="width: 85%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Design Team</span>
                        <span class="text-sm font-bold text-gray-900">72%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: 72%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">QA Team</span>
                        <span class="text-sm font-bold text-gray-900">68%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-yellow-600 rounded-full" style="width: 68%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Operations</span>
                        <span class="text-sm font-bold text-gray-900">45%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-purple-600 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Summary Statistics</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-gray-600 text-sm">Total Projects</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">24</p>
                <p class="text-xs text-gray-600 mt-1">All time</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Avg. Budget Variance</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">+5.2%</p>
                <p class="text-xs text-yellow-600 mt-1">Slight overage</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Total Team Members</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">24</p>
                <p class="text-xs text-gray-600 mt-1">Across all projects</p>
            </div>

            <div>
                <p class="text-gray-600 text-sm">Client Satisfaction</p>
                <p class="text-2xl font-bold text-gray-900 mt-2">4.6/5</p>
                <p class="text-xs text-green-600 mt-1">★★★★★</p>
            </div>
        </div>
    </div>
@endsection
