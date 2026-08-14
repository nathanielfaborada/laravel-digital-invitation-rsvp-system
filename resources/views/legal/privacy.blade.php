<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy — {{ config('app.name', 'Invitr') }}</title>
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

            <h1 class="text-lg sm:text-3xl font-bold text-gray-900 mb-1 sm:mb-2">Privacy Policy</h1>
            <p class="text-xs sm:text-sm text-gray-400 mb-4 sm:mb-8">Last updated: {{ date('F d, Y') }}</p>

            <div class="text-xs sm:text-base text-gray-600 space-y-4 sm:space-y-6">

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">1. Information We Collect</h2>
                    <p>We collect information you provide directly, including:</p>
                    <ul class="list-disc list-inside space-y-1 mt-2">
                        <li>Account information (name, email, password) when Hosts register</li>
                        <li>Event details (title, date, venue, description, cover image)</li>
                        <li>Guest information added by Hosts (name, email, phone number)</li>
                        <li>RSVP responses submitted by guests</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">2. How We Use Your Information</h2>
                    <p>We use collected information to:</p>
                    <ul class="list-disc list-inside space-y-1 mt-2">
                        <li>Create and manage event invitations</li>
                        <li>Send invitation emails to guests on behalf of Hosts</li>
                        <li>Process and display RSVP responses</li>
                        <li>Maintain and improve the Service</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">3. Data Sharing & Disclosure</h2>
                    <p>We do not sell, rent, or trade your personal information. Information is shared only in the following limited circumstances:</p>
                    <ul class="list-disc list-inside space-y-1 mt-2">
                        <li><strong>Between Hosts and Guests:</strong> Event details and RSVP responses are shared between the Host and invited guests for that event.</li>
                        <li><strong>Service Providers:</strong> We may use third-party services (e.g., email delivery) that process data on our behalf under confidentiality agreements.</li>
                        <li><strong>Legal Requirements:</strong> We may disclose information if required by law or in response to valid legal requests.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">4. Data Retention & Deletion</h2>
                    <p>We retain event and guest data for as long as the Host's account is active. Hosts may delete individual events or guests at any time. When an event is deleted, associated guest lists and RSVP responses are permanently removed.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">5. Security</h2>
                    <p>We implement appropriate technical and organizational measures to protect your personal data against unauthorized access, alteration, disclosure, or destruction.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">6. Cookies</h2>
                    <p>The Service uses session cookies to keep Hosts logged in and to maintain basic site functionality. We do not use cookies for advertising purposes.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">7. Changes to This Policy</h2>
                    <p>We may update this Privacy Policy from time to time. Continued use of the Service after changes constitutes acceptance of the updated policy.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">8. Contact</h2>
                    <p>If you have questions about this Privacy Policy or how your data is handled, please contact us through the Service's support channel.</p>
                </section>

            </div>
        </div>
    </div>

    <x-public-footer />
    <x-coffee-modal />

</body>
</html>