<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
</head>
<body style="font-family: 'DM Sans', Arial, sans-serif; background:#F7F3F3; margin:0; padding:0;">
  <table width="100%" cellpadding="0" cellspacing="0" style="padding:2rem 0;">
    <tr>
      <td align="center">
        <table width="480" cellpadding="0" cellspacing="0" style="background:white; border-radius:14px; overflow:hidden; border:1px solid rgba(29,78,216,0.12);">
          <tr>
            <td style="background:#1E3A8A; padding:1.5rem 2rem;">
              <span style="color:white; font-size:1.3rem; font-weight:700;">LifeLink</span>
            </td>
          </tr>
          <tr>
            <td style="padding:1.75rem 2rem;">
              <p style="font-size:1.1rem; color:#1A0A0B; margin:0 0 1rem;">Hi {{ $donor->first_name }},</p>

              <p style="font-size:0.95rem; color:#1A0A0B; line-height:1.6; margin:0 0 1rem;">
                <strong>{{ $hospital->name }}</strong> has an active blood request that matches your profile:
              </p>

              <table width="100%" cellpadding="8" cellspacing="0" style="background:#EFF6FF; border-radius:8px; font-size:0.9rem; color:#1E3A8A; margin-bottom:1.25rem;">
                <tr>
                  <td><strong>Blood Group</strong></td>
                  <td>{{ $bloodRequest->blood_group }}</td>
                </tr>
                <tr>
                  <td><strong>Units Needed</strong></td>
                  <td>{{ $bloodRequest->units_needed }}</td>
                </tr>
                <tr>
                  <td><strong>Urgency</strong></td>
                  <td>{{ ucfirst($bloodRequest->urgency) }}</td>
                </tr>
                <tr>
                  <td><strong>Ward</strong></td>
                  <td>{{ $bloodRequest->ward ?? 'General' }}</td>
                </tr>
              </table>

              <p style="font-size:0.95rem; color:#1A0A0B; line-height:1.6; margin:0 0 1.5rem;">
                If you're able to help, please log in to your LifeLink dashboard and let us know whether
                you're available for this request.
              </p>

              <table cellpadding="0" cellspacing="0">
                <tr>
                  <td style="background:#1D4ED8; border-radius:8px;">
                    <a href="{{ route('donor.requests.index') }}"
                       style="display:inline-block; padding:0.75rem 1.5rem; color:white; text-decoration:none; font-size:0.9rem; font-weight:500;">
                      View & Respond to This Request
                    </a>
                  </td>
                </tr>
              </table>

              <p style="font-size:0.78rem; color:#6B3B40; margin-top:1.75rem;">
                You're receiving this because a hospital identified you as a compatible, eligible donor for
                this request. Thank you for being part of LifeLink.
              </p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
