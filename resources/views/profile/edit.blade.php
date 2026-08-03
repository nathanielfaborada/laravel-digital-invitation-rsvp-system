<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile Settings') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-12">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>

            <div class="p-4 sm:p-8 bg-white shadow-sm rounded-xl border border-red-100">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>