<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terms & Conditions — {{ config('app.name', 'Invitr') }}</title>
    <!-- Alpine.js Cloak Protection -->
    <style>
        [x-cloak] { display: none !important; }
    </style>
    @vite(['resources/css/app.css'])
</head>
<body 
    x-data="{ showCoffeeModal: false }"
    class="bg-gray-50 min-h-screen"
>

    <x-public-nav />

    <div class="max-w-7xl mx-auto px-3 sm:px-6 py-4 sm:py-8">
        <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm p-4 sm:p-10">

            <h1 class="text-lg sm:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Terms & Conditions</h1>
            <p class="text-xs sm:text-sm text-gray-400 mb-4 sm:mb-8">Last updated: {{ date('F d, Y') }}</p>

            <div class="text-xs sm:text-base text-gray-600 space-y-4 sm:space-y-6">

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">1. Acceptance of Terms</h2>
                    <p>By accessing or using {{ config('app.name', 'Invitr') }} ("the Service"), you agree to be bound by these Terms & Conditions. If you do not agree, please do not use the Service.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">2. Description of Service</h2>
                    <p>{{ config('app.name', 'Invitr') }} allows users ("Hosts") to create digital event invitations, manage guest lists, and collect RSVP responses. Guests may access personalized invitation links to view event details and respond.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">3. User Accounts</h2>
                    <p>Hosts must register for an account to create and manage events. You are responsible for maintaining the confidentiality of your account credentials and for all activity under your account.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">4. Guest Information</h2>
                    <p>Hosts are responsible for obtaining appropriate consent before adding a guest's name, email, or phone number to the Service. Guest information is used solely to deliver invitations and collect RSVP responses.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">5. Acceptable Use</h2>
                    <p>You agree not to use the Service to send unsolicited messages, distribute harmful content, or violate any applicable laws. We reserve the right to suspend accounts that misuse the Service.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">6. Limitation of Liability</h2>
                    <p>The Service is provided "as is" without warranties of any kind. We are not liable for any indirect, incidental, or consequential damages arising from your use of the Service.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">7. Changes to Terms</h2>
                    <p>We may update these Terms from time to time. Continued use of the Service after changes constitutes acceptance of the updated Terms.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">8. Contact</h2>
                    <p>If you have questions about these Terms, please contact us through the Service's support channel.</p>
                </section>

            </div>
        </div>
    </div>

    <x-public-footer />
    <x-coffee-modal />

</body>
</html>