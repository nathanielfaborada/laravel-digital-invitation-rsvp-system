<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f3f4f6; padding: 20px;">

    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden;">

        @if ($guest->event->cover_image)
            <img src="{{ asset('storage/' . $guest->event->cover_image) }}" alt="{{ $guest->event->title }}" style="width: 100%; height: 200px; object-fit: cover;">
        @endif

        <div style="padding: 30px; text-align: center;">
            <p style="color: #6b7280; text-transform: uppercase; font-size: 12px; margin-bottom: 5px;">You're Invited</p>
            <h1 style="color: #1f2937; font-size: 24px; margin-bottom: 15px;">{{ $guest->event->title }}</h1>

            <p style="color: #4b5563; margin-bottom: 20px;">
                Dear {{ $guest->name }}, we'd love for you to join us!
            </p>

            <table style="width: 100%; margin-bottom: 20px; border-top: 1px solid #e5e7eb; border-bottom: 1px solid #e5e7eb; padding: 15px 0;">
                <tr>
                    <td style="padding: 10px 0; color: #6b7280; font-size: 12px; text-transform: uppercase;">Date</td>
                    <td style="padding: 10px 0; text-align: right; font-weight: bold; color: #1f2937;">
                        {{ \Carbon\Carbon::parse($guest->event->event_date)->format('F d, Y') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #6b7280; font-size: 12px; text-transform: uppercase;">Time</td>
                    <td style="padding: 10px 0; text-align: right; font-weight: bold; color: #1f2937;">
                        {{ \Carbon\Carbon::parse($guest->event->event_time)->format('h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 10px 0; color: #6b7280; font-size: 12px; text-transform: uppercase;">Venue</td>
                    <td style="padding: 10px 0; text-align: right; font-weight: bold; color: #1f2937;">
                        {{ $guest->event->venue }}
                    </td>
                </tr>
            </table>

            <a href="{{ url('/invite/' . $guest->unique_code) }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold;">
                View Invitation & RSVP
            </a>
        </div>
    </div>

</body>
</html>