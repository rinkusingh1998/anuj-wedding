<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New enquiry</title>
</head>
<body style="margin:0;padding:24px;background:#f6efe6;font-family:Arial,Helvetica,sans-serif;color:#2a1a12;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:640px;margin:0 auto;background:#ffffff;border-radius:14px;overflow:hidden;border:1px solid #e8dccd;">
        <tr>
            <td style="background:#4a2418;color:#ffffff;padding:24px 28px;">
                <p style="margin:0 0 6px;font-size:12px;letter-spacing:2px;text-transform:uppercase;color:#d9ad67;">
                    New Website Enquiry
                </p>
                <h1 style="margin:0;font-size:22px;line-height:1.3;">
                    {{ $enquiry->name }}
                </h1>
                <p style="margin:6px 0 0;font-size:14px;color:#f3e6d5;">
                    Received {{ $enquiry->created_at?->format('d M Y, h:i A') }}
                </p>
            </td>
        </tr>

        <tr>
            <td style="padding:24px 28px;">

                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="font-size:14px;line-height:1.6;">
                    @foreach ([
                        'Phone' => $enquiry->phone,
                        'Email' => $enquiry->email,
                        'Service' => $enquiry->service,
                        'Event type' => $enquiry->event_type,
                        'Event date' => $enquiry->event_date,
                        'Event time' => $enquiry->event_time,
                        'City' => $enquiry->city,
                        'Venue / address' => $enquiry->event_address,
                        'Budget' => $enquiry->budget,
                        'Source page' => $enquiry->source_page,
                        'Source URL' => $enquiry->source_url,
                    ] as $label => $value)
                        @if (filled($value))
                            <tr>
                                <td style="padding:6px 0;width:150px;color:#7a6455;vertical-align:top;">
                                    {{ $label }}
                                </td>
                                <td style="padding:6px 0;color:#2a1a12;vertical-align:top;">
                                    {{ $value }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </table>

                @if (filled($enquiry->message))
                    <div style="margin-top:20px;padding:16px 18px;background:#fbf6ef;border-left:3px solid #bd8a3d;border-radius:8px;">
                        <p style="margin:0 0 6px;font-size:12px;letter-spacing:1px;text-transform:uppercase;color:#9b4a2f;">
                            Requirement
                        </p>
                        <p style="margin:0;font-size:14px;line-height:1.7;white-space:pre-line;">
                            {{ $enquiry->message }}
                        </p>
                    </div>
                @endif

                <p style="margin:24px 0 0;">
                    <a href="{{ url('/admin/enquiries/'.$enquiry->getKey().'/edit') }}"
                       style="display:inline-block;background:#9b4a2f;color:#ffffff;text-decoration:none;padding:12px 22px;border-radius:999px;font-size:14px;">
                        Open in admin panel
                    </a>
                </p>

            </td>
        </tr>

        <tr>
            <td style="padding:16px 28px 24px;border-top:1px solid #f0e6d8;font-size:12px;color:#8a7360;">
                This notification was sent automatically from the website enquiry form.
            </td>
        </tr>
    </table>

</body>
</html>
