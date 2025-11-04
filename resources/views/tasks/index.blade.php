@extends('layouts.app')

@section('title', 'Tasks')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tasks</h1>
            <p class="text-gray-600 mt-2">Manage project tasks and assignments</p>
        </div>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
            + New Task
        </button>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search tasks..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Status</option>
                    <option>To Do</option>
                    <option>In Progress</option>
                    <option>Review</option>
                    <option>Completed</option>
                </select>
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Priority</option>
                    <option>High</option>
                    <option>Medium</option>
                    <option>Low</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Tasks View -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- To Do Column -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">To Do</h2>
                <span class="bg-gray-200 text-gray-700 px-2 py-1 rounded text-xs font-medium">5</span>
            </div>

            <div class="space-y-3">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-gray-400">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Setup project repository</h3>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">High</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Website Redesign</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 8, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User&size=24&background=3B82F6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-blue-400">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Create API documentation</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Medium</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Mobile App Development</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 12, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User2&size=24&background=8B5CF6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 3 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-green-400">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Database schema design</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">Low</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">System Integration</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 15, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User3&size=24&background=10B981&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 4 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-yellow-400">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Write unit tests</h3>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">High</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Data Analytics Platform</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 10, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User4&size=24&background=F59E0B&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 5 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-purple-400">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Security vulnerability scan</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Medium</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Database Migration</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 18, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User5&size=24&background=EF4444&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">In Progress</h2>
                <span class="bg-blue-200 text-blue-700 px-2 py-1 rounded text-xs font-medium">3</span>
            </div>

            <div class="space-y-3">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-blue-600">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">UI/UX mockups design</h3>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">High</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">Website Redesign</p>
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-500">Progress</span>
                            <span class="text-xs font-medium">60%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full">
                            <div class="h-1.5 bg-blue-600 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 6, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User&size=24&background=3B82F6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-blue-600">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Backend API development</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Medium</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">Mobile App Development</p>
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-500">Progress</span>
                            <span class="text-xs font-medium">45%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full">
                            <div class="h-1.5 bg-blue-600 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 20, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User2&size=24&background=8B5CF6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 3 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-blue-600">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Frontend integration</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">Low</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-2">System Integration</p>
                    <div class="mb-3">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-xs text-gray-500">Progress</span>
                            <span class="text-xs font-medium">30%</span>
                        </div>
                        <div class="w-full h-1.5 bg-gray-200 rounded-full">
                            <div class="h-1.5 bg-blue-600 rounded-full" style="width: 30%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 25, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User3&size=24&background=10B981&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- Review Column -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Review</h2>
                <span class="bg-yellow-200 text-yellow-700 px-2 py-1 rounded text-xs font-medium">2</span>
            </div>

            <div class="space-y-3">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-yellow-600">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">Code review checklist</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Medium</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Mobile App Development</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 5, 2025</span>
                        <div class="flex -space-x-2">
                            <img src="https://ui-avatars.com/api/?name=User&size=24&background=3B82F6&color=fff" alt="User" class="w-6 h-6 rounded-full border-2 border-white">
                            <img src="https://ui-avatars.com/api/?name=User2&size=24&background=8B5CF6&color=fff" alt="User" class="w-6 h-6 rounded-full border-2 border-white">
                        </div>
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-yellow-600">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900">QA testing results</h3>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">High</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Website Redesign</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Due: Nov 7, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User4&size=24&background=F59E0B&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>
            </div>
        </div>

        <!-- Completed Column -->
        <div class="bg-gray-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-semibold text-gray-900">Completed</h2>
                <span class="bg-green-200 text-green-700 px-2 py-1 rounded text-xs font-medium">12</span>
            </div>

            <div class="space-y-3">
                <!-- Task Card 1 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-green-600 opacity-75">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 line-through">Project kickoff meeting</h3>
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs font-medium">Low</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Website Redesign</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Completed: Oct 28, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User&size=24&background=3B82F6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 2 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-green-600 opacity-75">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 line-through">Requirements gathering</h3>
                        <span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded text-xs font-medium">Medium</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">Mobile App Development</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Completed: Oct 25, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User2&size=24&background=8B5CF6&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>

                <!-- Task Card 3 -->
                <div class="bg-white rounded-lg p-4 shadow-sm hover:shadow-md transition cursor-pointer border-l-4 border-green-600 opacity-75">
                    <div class="flex items-start justify-between mb-2">
                        <h3 class="font-medium text-gray-900 line-through">Technology stack selection</h3>
                        <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs font-medium">High</span>
                    </div>
                    <p class="text-xs text-gray-600 mb-3">System Integration</p>
                    <div class="flex items-center justify-between">
                        <span class="text-xs text-gray-500">Completed: Oct 20, 2025</span>
                        <img src="https://ui-avatars.com/api/?name=User3&size=24&background=10B981&color=fff" alt="User" class="w-6 h-6 rounded-full">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
