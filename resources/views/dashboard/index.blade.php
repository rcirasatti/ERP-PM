@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600 mt-2">Welcome back! Here's your project overview.</p>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Projects -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Projects</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">24</p>
                    <p class="text-green-600 text-xs mt-2">↑ 12% from last month</p>
                </div>
                <div class="bg-blue-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- In Progress -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">In Progress</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">8</p>
                    <p class="text-gray-600 text-xs mt-2">4 pending review</p>
                </div>
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Completed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">16</p>
                    <p class="text-green-600 text-xs mt-2">↑ 25% completed on time</p>
                </div>
                <div class="bg-green-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Revenue -->
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">$245.5K</p>
                    <p class="text-green-600 text-xs mt-2">↑ 8% from last month</p>
                </div>
                <div class="bg-purple-100 p-3 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Recent Projects (spans 2 columns) -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Recent Projects</h2>
                <a href="{{ route('projects.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Project Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Due Date</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">Website Redesign</p>
                                    <p class="text-xs text-gray-600">Client: ABC Corp</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-600 rounded-full" style="width: 65%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">65%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Dec 15, 2025</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">Mobile App Development</p>
                                    <p class="text-xs text-gray-600">Client: XYZ Ltd</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-green-600 rounded-full" style="width: 100%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">100%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Oct 30, 2025</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">System Integration</p>
                                    <p class="text-xs text-gray-600">Client: Tech Inc</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-yellow-600 rounded-full" style="width: 40%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">40%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Jan 10, 2026</td>
                        </tr>

                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="font-medium text-gray-900">Database Migration</p>
                                    <p class="text-xs text-gray-600">Internal Project</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <div class="w-24 h-2 bg-gray-200 rounded-full">
                                        <div class="h-2 bg-blue-600 rounded-full" style="width: 80%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">80%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600">Nov 20, 2025</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Project Status Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Project Distribution</h2>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">In Progress</span>
                        <span class="text-sm font-bold text-gray-900">8 (33%)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: 33%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Completed</span>
                        <span class="text-sm font-bold text-gray-900">16 (67%)</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full" style="width: 67%"></div>
                    </div>
                </div>

                <div class="pt-4 mt-4 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Avg. Project Duration</span>
                            <span class="font-semibold text-gray-900">45 days</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">On-Time Delivery</span>
                            <span class="font-semibold text-green-600">94%</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Budget Variance</span>
                            <span class="font-semibold text-yellow-600">+5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks & Calendar -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Upcoming Tasks -->
        <div class="lg:col-span-2 bg-white rounded-lg shadow-md overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Upcoming Tasks</h2>
                <a href="{{ route('tasks.index') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">View All</a>
            </div>

            <div class="divide-y divide-gray-200">
                <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start space-x-4">
                        <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 rounded">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Complete UI mockups for website redesign</p>
                            <p class="text-sm text-gray-600 mt-1">Project: Website Redesign</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">High Priority</span>
                                <span class="text-xs text-gray-500">Due: Nov 6, 2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start space-x-4">
                        <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 rounded">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Review and approve development code</p>
                            <p class="text-sm text-gray-600 mt-1">Project: Mobile App Development</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Medium Priority</span>
                                <span class="text-xs text-gray-500">Due: Nov 7, 2025</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 hover:bg-gray-50 transition cursor-pointer">
                    <div class="flex items-start space-x-4">
                        <input type="checkbox" class="mt-1 h-4 w-4 text-blue-600 rounded">
                        <div class="flex-1">
                            <p class="font-medium text-gray-900">Client presentation and feedback session</p>
                            <p class="text-sm text-gray-600 mt-1">Project: System Integration</p>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">Low Priority</span>
                                <span class="text-xs text-gray-500">Due: Nov 10, 2025</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline/Reminders -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Reminders</h2>

            <div class="space-y-4">
                <div class="p-3 bg-red-50 border-l-4 border-red-500 rounded">
                    <p class="text-sm font-medium text-red-900">Urgent: Client meeting today</p>
                    <p class="text-xs text-red-700 mt-1">2:00 PM - Zoom Conference</p>
                </div>

                <div class="p-3 bg-yellow-50 border-l-4 border-yellow-500 rounded">
                    <p class="text-sm font-medium text-yellow-900">Invoice payment due tomorrow</p>
                    <p class="text-xs text-yellow-700 mt-1">Project: ABC Corp - $5,000</p>
                </div>

                <div class="p-3 bg-blue-50 border-l-4 border-blue-500 rounded">
                    <p class="text-sm font-medium text-blue-900">Team standup meeting</p>
                    <p class="text-xs text-blue-700 mt-1">Daily at 10:00 AM - Conference Room A</p>
                </div>

                <div class="p-3 bg-green-50 border-l-4 border-green-500 rounded">
                    <p class="text-sm font-medium text-green-900">Project milestone completed</p>
                    <p class="text-xs text-green-700 mt-1">Website Redesign - Phase 2 Done</p>
                </div>
            </div>

            <button class="w-full mt-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">
                View Calendar
            </button>
        </div>
    </div>
@endsection
