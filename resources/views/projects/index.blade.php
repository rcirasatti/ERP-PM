@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Projects</h1>
            <p class="text-gray-600 mt-2">Manage and monitor all your projects</p>
        </div>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
            + New Project
        </button>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search projects..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Status</option>
                    <option>In Progress</option>
                    <option>Completed</option>
                    <option>Pending</option>
                    <option>On Hold</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Teams</option>
                    <option>Team A</option>
                    <option>Team B</option>
                    <option>Team C</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Projects Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Project Card 1 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Website Redesign</h3>
                        <p class="text-sm text-gray-600">Client: ABC Corporation</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Modern responsive website with improved UX design and functionality.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">65%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-blue-600 rounded-full" style="width: 65%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Oct 1, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Dec 15, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$15,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Card 2 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Mobile App Development</h3>
                        <p class="text-sm text-gray-600">Client: XYZ Limited</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Native iOS and Android application with real-time sync capabilities.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">100%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Aug 15, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Oct 30, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$45,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Card 3 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">System Integration</h3>
                        <p class="text-sm text-gray-600">Client: Tech Innovations</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Integration of legacy systems with modern cloud infrastructure.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">40%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-yellow-600 rounded-full" style="width: 40%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Nov 1, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Jan 10, 2026</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$75,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Card 4 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Data Analytics Platform</h3>
                        <p class="text-sm text-gray-600">Client: Analytics Pro</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Advanced analytics platform with machine learning capabilities.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">55%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-purple-600 rounded-full" style="width: 55%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Sep 15, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Dec 31, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$120,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Card 5 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-red-500 to-red-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Database Migration</h3>
                        <p class="text-sm text-gray-600">Internal Project</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">In Progress</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Migration from on-premises database to cloud infrastructure.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">80%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-red-600 rounded-full" style="width: 80%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Oct 10, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Nov 20, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$35,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Project Card 6 -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
            <div class="bg-gradient-to-r from-green-500 to-green-600 h-2"></div>
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Security Audit</h3>
                        <p class="text-sm text-gray-600">Internal Project</p>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">Completed</span>
                </div>

                <p class="text-gray-600 text-sm mb-4">Comprehensive security audit and vulnerability assessment.</p>

                <div class="mb-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-sm font-bold text-gray-900">100%</span>
                    </div>
                    <div class="w-full h-2 bg-gray-200 rounded-full">
                        <div class="h-2 bg-green-600 rounded-full" style="width: 100%"></div>
                    </div>
                </div>

                <div class="space-y-2 mb-4 pb-4 border-b border-gray-200">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Start Date:</span>
                        <span class="font-medium text-gray-900">Sep 1, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Due Date:</span>
                        <span class="font-medium text-gray-900">Oct 15, 2025</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Budget:</span>
                        <span class="font-medium text-gray-900">$20,000</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    <button class="flex-1 px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium">View Details</button>
                    <button class="px-3 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
