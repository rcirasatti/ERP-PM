@extends('layouts.app')

@section('title', 'Invoices')

@section('content')
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Invoices</h1>
            <p class="text-gray-600 mt-2">Manage and track invoices</p>
        </div>
        <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
            + New Invoice
        </button>
    </div>

    <!-- KPI Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-blue-500">
            <p class="text-gray-600 text-sm">Total Invoiced</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">$345,000</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-green-500">
            <p class="text-gray-600 text-sm">Paid</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">$285,000</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-yellow-500">
            <p class="text-gray-600 text-sm">Pending</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">$45,000</p>
        </div>
        <div class="bg-white rounded-lg shadow-md p-4 border-l-4 border-red-500">
            <p class="text-gray-600 text-sm">Overdue</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">$15,000</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-md p-4 mb-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex-1">
                <input type="text" placeholder="Search invoices..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex gap-2">
                <select class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>All Status</option>
                    <option>Draft</option>
                    <option>Sent</option>
                    <option>Paid</option>
                    <option>Pending</option>
                    <option>Overdue</option>
                    <option>Cancelled</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Invoices Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Invoice #</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Client</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Project</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Due Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-600 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0001</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">ABC Corporation</p>
                            <p class="text-xs text-gray-600">john@abc.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">Website Redesign</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$25,000.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Dec 4, 2025</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0002</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">XYZ Limited</p>
                            <p class="text-xs text-gray-600">jane@xyz.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">Mobile App Development</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$55,000.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Dec 15, 2025</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0003</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">Tech Innovations</p>
                            <p class="text-xs text-gray-600">mike@tech.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">System Integration</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$85,000.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Dec 1, 2025</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Overdue</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0004</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">Analytics Pro</p>
                            <p class="text-xs text-gray-600">sarah@analytics.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">Data Analytics Platform</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$130,000.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Jan 5, 2026</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Draft</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0005</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">Global Ventures</p>
                            <p class="text-xs text-gray-600">robert@global.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">Database Migration</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$42,500.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Nov 25, 2025</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>

                <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 text-sm font-medium text-blue-600">INV-2025-0006</td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-gray-900">Enterprise Solutions</p>
                            <p class="text-xs text-gray-600">contact@enterprise.com</p>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">Security Audit</td>
                    <td class="px-6 py-4 text-sm font-medium text-gray-900">$20,000.00</td>
                    <td class="px-6 py-4 text-sm text-gray-600">Nov 10, 2025</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>
                    </td>
                    <td class="px-6 py-4 text-sm">
                        <button class="text-blue-600 hover:text-blue-800 mr-3">View</button>
                        <button class="text-gray-600 hover:text-gray-800">Download</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endsection
