<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Privacy Policy — {{ config('app.name', 'Invitr') }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-50 min-h-screen">

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
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">3. Email Delivery</h2>
                    <p>We use a third-party email service provider (Brevo) to deliver invitation emails. Guest email addresses are shared with this provider solely for the purpose of sending invitations.</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">4. Data Storage & Security</h2>
                    <p>Your information is stored in a secure database and is not sold or shared with third parties beyond what is necessary to operate the Service (such as email delivery).</p>
                </section>

                <section>
                    <h2 class="text-sm sm:text-lg font-semibold text-gray-800 mb-1 sm:mb-2">5. Guest Rights</h2>
                    <p>Guests who wish to have their information removed from an event may contact the Host directly, or reach out to us for assistance.</p>
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

    

</body>
<x-public-footer />
</html>