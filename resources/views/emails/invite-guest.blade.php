<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
</head>
<body style="margin: 0; padding: 20px; background-color: #f3f4f6; font-family: Arial, sans-serif;">

    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center">
                
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="500" 
                       background="{{ $guest->event->cover_image ? $guest->event->cover_image : 'https://res.cloudinary.com/wyofiygs/image/upload/v1786343694/Untitled_design_6_it913f.png' }}" 
                       style="width: 500px; background-image: url('{{ $guest->event->cover_image ? $guest->event->cover_image : 'https://res.cloudinary.com/wyofiygs/image/upload/v1786343694/Untitled_design_6_it913f.png' }}'); background-size: cover; background-position: center top; background-repeat: no-repeat; border-radius: 12px; overflow: hidden; background-color: #ffffff;">
                    <tr>
                        <td align="center" style="padding: 40px 30px;">
                            
                            <p style="color: #6b7280; text-transform: uppercase; font-size: 12px; margin: 0 0 10px 0; letter-spacing: 1px;">YOU'RE INVITED</p>
                            
                            <h1 style="color: #1f2937; font-size: 26px; margin: 0 0 15px 0; font-weight: bold;">
                                {{ $guest->event->title }}
                            </h1>

                            <p style="color: #4b5563; font-size: 15px; margin: 0 0 25px 0; line-height: 1.5;">
                                Dear <strong>{{ $guest->name }}</strong>, we'd love for you to join us!
                            </p>

                            <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 25px; border-top: 1px solid rgba(229, 231, 235, 0.8); border-bottom: 1px solid rgba(229, 231, 235, 0.8); background-color: rgba(255, 255, 255, 0.85); border-radius: 8px;">
                                <tr>
                                    <td style="padding: 12px 15px; color: #6b7280; font-size: 12px; text-transform: uppercase;">DATE</td>
                                    <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #1f2937; font-size: 14px;">
                                        {{ \Carbon\Carbon::parse($guest->event->event_date)->format('F d, Y') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 15px; color: #6b7280; font-size: 12px; text-transform: uppercase;">TIME</td>
                                    <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #1f2937; font-size: 14px;">
                                        {{ \Carbon\Carbon::parse($guest->event->event_time)->format('h:i A') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 15px; color: #6b7280; font-size: 12px; text-transform: uppercase;">VENUE</td>
                                    <td style="padding: 12px 15px; text-align: right; font-weight: bold; color: #1f2937; font-size: 14px;">
                                        {{ $guest->event->venue }}
                                    </td>
                                </tr>
                            </table>
                            <a href="{{ url('/invite/' . $guest->unique_code) }}" style="display: inline-block; background-color: #4f46e5; color: #ffffff; padding: 12px 30px; border-radius: 25px; text-decoration: none; font-weight: bold; font-size: 14px;">
                                View Invitation & RSVP
                            </a>

                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>
</html>