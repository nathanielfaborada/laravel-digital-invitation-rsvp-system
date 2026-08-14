<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 grid grid-cols-1 md:grid-cols-2 gap-6 items-start">
        <!-- Left Column: Profile Information -->
        <div class="space-y-6">
            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Right Column: Update Password & Delete Account -->
        <div class="space-y-6">
            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-4 sm:p-6 bg-white shadow-sm rounded-xl border border-red-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>