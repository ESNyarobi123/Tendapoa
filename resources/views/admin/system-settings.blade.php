@extends('layouts.app')

@section('title', 'System Settings - Admin')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 to-purple-100 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        ⚙️ System Settings
                    </h1>
                    <p class="text-gray-600">Configure platform-wide settings and preferences</p>
                </div>
                <div class="flex space-x-4">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.system-settings.update') }}" method="POST" class="space-y-8">
            @csrf

            <!-- Platform Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">🏢 Platform Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Platform Name
                        </label>
                        <input type="text" name="platform_name" value="Tendapoa" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Platform Version
                        </label>
                        <input type="text" name="platform_version" value="1.0.0" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="mt-6">
                    <label class="block text-sm font-bold text-gray-700 mb-2">
                        Platform Description
                    </label>
                    <textarea name="platform_description" rows="3" 
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">Tendapoa - Your trusted platform for connecting job seekers with employers</textarea>
                </div>
            </div>

            <!-- User Management Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">👥 User Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            New User Registration
                        </label>
                        <select name="user_registration" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="enabled">Enabled</option>
                            <option value="disabled">Disabled</option>
                            <option value="approval_required">Approval Required</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Email Verification Required
                        </label>
                        <select name="email_verification" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="required">Required</option>
                            <option value="optional">Optional</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Default User Role
                        </label>
                        <select name="default_role" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="muhitaji">Muhitaji (Job Poster)</option>
                            <option value="mfanyakazi">Mfanyakazi (Worker)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            User Suspension Policy
                        </label>
                        <select name="suspension_policy" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="manual">Manual Only</option>
                            <option value="automatic">Automatic (3 strikes)</option>
                            <option value="hybrid">Hybrid (Manual + Auto)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Job Management Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">💼 Job Management</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Job Posting Fee (Tsh)
                        </label>
                        <input type="number" name="job_posting_fee" value="0" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Commission Rate (%)
                        </label>
                        <input type="number" name="commission_rate" value="5" step="0.1" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Job Auto-Expiry (Days)
                        </label>
                        <input type="number" name="job_expiry_days" value="30" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Max Jobs Per User
                        </label>
                        <input type="number" name="max_jobs_per_user" value="10" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Payment Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">💳 Payment Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Payment Gateway
                        </label>
                        <select name="payment_gateway" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="zenopay">ZenoPay</option>
                            <option value="mpesa">M-Pesa</option>
                            <option value="tigopesa">Tigo Pesa</option>
                            <option value="airtelmoney">Airtel Money</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Minimum Withdrawal (Tsh)
                        </label>
                        <input type="number" name="min_withdrawal" value="10000" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Withdrawal Processing Time (Hours)
                        </label>
                        <input type="number" name="withdrawal_processing_hours" value="24" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Transaction Fee (%)
                        </label>
                        <input type="number" name="transaction_fee" value="2.5" step="0.1" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">🔒 Security Settings</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Session Timeout (Minutes)
                        </label>
                        <input type="number" name="session_timeout" value="120" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Max Login Attempts
                        </label>
                        <input type="number" name="max_login_attempts" value="5" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-6">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Password Reset Timeout (Minutes)
                        </label>
                        <input type="number" name="password_reset_timeout" value="60" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            Two-Factor Authentication
                        </label>
                        <select name="two_factor_auth" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="disabled">Disabled</option>
                            <option value="optional">Optional</option>
                            <option value="required">Required</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notification Settings -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">🔔 Notification Settings</h2>
                
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Email Notifications</h3>
                            <p class="text-sm text-gray-500">Send email notifications for important events</p>
                        </div>
                        <input type="checkbox" name="email_notifications" checked 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">SMS Notifications</h3>
                            <p class="text-sm text-gray-500">Send SMS notifications for urgent events</p>
                        </div>
                        <input type="checkbox" name="sms_notifications" 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </div>

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Push Notifications</h3>
                            <p class="text-sm text-gray-500">Send push notifications to mobile devices</p>
                        </div>
                        <input type="checkbox" name="push_notifications" checked 
                               class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                    </div>
                </div>
            </div>

            <!-- Save Button -->
            <div class="flex justify-end">
                <button type="submit" 
                        class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105">
                    💾 Save All Settings
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
