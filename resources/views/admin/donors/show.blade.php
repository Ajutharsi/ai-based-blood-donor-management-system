<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;
    --white:#fff;--off:#F7F3F3;--text:#1A0A0B;--muted:#6B3B40;
    --border:rgba(200,25,42,0.12);--gray:#F4F1F1;--gray-b:#E4DEDE;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --sb:68px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR */
  .sidebar{width:var(--sb);background:var(--red-dark);display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(255,255,255,0.15);border-radius:50%;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:white;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;flex:1;width:100%;align-items:center;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.45);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.8);}
  .sb-item.active{background:rgba(255,255,255,0.15);}
  .sb-item.active svg{stroke:white;}
  .sb-tooltip{position:absolute;left:56px;background:#1A0A0B;color:white;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;z-index:99;}
  .sb-item:hover .sb-tooltip{opacity:1;}
  .sb-bottom{margin-top:auto;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .topbar-left{display:flex;align-items:center;gap:12px;}
  .back-btn{display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--muted);text-decoration:none;padding:0.4rem 0.9rem;border:1px solid var(--gray-b);border-radius:7px;transition:all 0.2s;}
  .back-btn:hover{border-color:var(--red);color:var(--red);}
  .back-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .topbar-right{display:flex;align-items:center;gap:10px;}
  .admin-pill{display:flex;align-items:center;gap:8px;background:var(--red-light);border:1px solid var(--border);border-radius:20px;padding:0.35rem 0.9rem 0.35rem 0.5rem;}
  .admin-avatar{width:24px;height:24px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;color:white;}
  .admin-name{font-size:0.78rem;font-weight:500;color:var(--red-dark);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--red);color:var(--red);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* PAGE LAYOUT */
  .page-grid{display:grid;grid-template-columns:300px 1fr;gap:16px;}

  /* PROFILE CARD */
  .profile-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .pc-top{background:var(--red-dark);padding:2rem 1.5rem;text-align:center;position:relative;}
  .pc-bg{position:absolute;inset:0;pointer-events:none;}
  .pc-circle{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.08);}
  .pcc1{width:180px;height:180px;top:-50px;right:-40px;}
  .pcc2{width:120px;height:120px;bottom:-30px;left:-30px;}
  .pc-avatar{width:72px;height:72px;border-radius:50%;background:rgba(255,255,255,0.2);border:3px solid rgba(255,255,255,0.3);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:white;margin:0 auto 0.85rem;position:relative;z-index:2;}
  .pc-name{font-family:'Playfair Display',serif;font-size:1.15rem;font-weight:700;color:white;position:relative;z-index:2;margin-bottom:4px;}
  .pc-email{font-size:0.75rem;color:rgba(255,255,255,0.55);position:relative;z-index:2;margin-bottom:10px;}
  .pc-blood{display:inline-block;background:var(--red);color:white;font-size:0.78rem;font-weight:600;padding:4px 14px;border-radius:20px;position:relative;z-index:2;}
  .pc-body{padding:1.1rem 1.25rem;}
  .pc-row{display:flex;align-items:center;justify-content:space-between;padding:0.55rem 0;border-bottom:1px solid rgba(200,25,42,0.06);}
  .pc-row:last-child{border-bottom:none;}
  .pc-key{font-size:0.75rem;color:var(--muted);}
  .pc-val{font-size:0.8rem;font-weight:500;color:var(--text);text-align:right;}

  /* ACTION BUTTONS */
  .action-btns{display:flex;flex-direction:column;gap:8px;padding:0 1.25rem 1.25rem;}
  .action-btn{width:100%;padding:0.65rem;border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.82rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:8px;text-decoration:none;}
  .action-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
  .ab-toggle-on{background:var(--green-bg);color:var(--green);border:1px solid var(--green-b);}
  .ab-toggle-on:hover{background:var(--green);color:white;}
  .ab-toggle-off{background:#FFF7ED;color:#C2410C;border:1px solid #FED7AA;}
  .ab-toggle-off:hover{background:#C2410C;color:white;}
  .ab-delete{background:#FEF2F2;color:#991B1B;border:1px solid #FECACA;}
  .ab-delete:hover{background:#991B1B;color:white;}
  .ab-back{background:var(--gray);color:var(--muted);border:1px solid var(--gray-b);}
  .ab-back:hover{border-color:var(--red);color:var(--red);}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;margin-bottom:14px;}
  .card-hd{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.1rem;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}

  /* INFO GRID */
  .info-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
  .info-item{background:var(--gray);border-radius:9px;padding:0.9rem 1rem;}
  .info-key{font-size:0.7rem;color:var(--muted);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:4px;}
  .info-val{font-size:0.9rem;font-weight:500;color:var(--text);}
  .info-val.large{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;}

  /* AI BREAKDOWN */
  .ai-metrics{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:1rem;}
  .ai-metric{border:1px solid var(--border);border-radius:10px;padding:1rem;text-align:center;}
  .am-val{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;color:var(--text);margin-bottom:2px;}
  .am-label{font-size:0.7rem;color:var(--muted);margin-bottom:8px;}
  .am-bar{background:var(--gray);border-radius:4px;height:5px;overflow:hidden;}
  .am-fill{height:5px;border-radius:4px;}
  .fill-g{background:var(--green);}
  .fill-r{background:var(--red);}
  .am-status{font-size:0.65rem;font-weight:500;margin-top:5px;}
  .st-ok{color:var(--green);}
  .st-warn{color:var(--red);}

  /* BADGE */
  .badge{display:inline-block;font-size:0.72rem;font-weight:500;padding:4px 10px;border-radius:20px;}
  .b-elig{background:var(--green-bg);color:var(--green);}
  .b-not{background:#FFF7ED;color:#C2410C;}

  /* CONFIDENCE BAR */
  .conf-wrap{display:flex;align-items:center;gap:10px;margin-top:0.75rem;}
  .conf-track{flex:1;background:var(--gray);border-radius:5px;height:8px;overflow:hidden;}
  .conf-fill{height:8px;border-radius:5px;transition:width 0.6s;}
  .conf-val{font-size:0.82rem;font-weight:500;color:var(--text);min-width:40px;text-align:right;}

  /* MEDICAL NOTES */
  .notes-box{background:var(--amber-bg);border:1px solid var(--amber-b);border-radius:9px;padding:1rem;font-size:0.82rem;color:#78350F;line-height:1.65;}
  .notes-box.empty{background:var(--gray);border-color:var(--gray-b);color:var(--muted);}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('admin.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tooltip">Dashboard</span>
    </a>
    <a href="{{ route('admin.donors.index') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      <span class="sb-tooltip">Donors</span>
    </a>
    <a href="{{ route('admin.hospitals.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
      <span class="sb-tooltip">Hospitals</span>
    </a>
    <a href="{{ route('admin.ai-predictions.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tooltip">AI Predictions</span>
    </a>
    <a href="{{ route('admin.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tooltip">Settings</span>
    </a>
  </div>
  <div class="sb-bottom">
    <div class="sb-avatar">{{ strtoupper(substr(auth('admin')->user()->name,0,2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="topbar-left">
      <a href="{{ route('admin.donors.index') }}" class="back-btn">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Donors
      </a>
      <div class="topbar-title">Donor Profile</div>
    </div>
    <div class="topbar-right">
      <div class="admin-pill">
        <div class="admin-avatar">{{ strtoupper(substr(auth('admin')->user()->name,0,2)) }}</div>
        <span class="admin-name">{{ auth('admin')->user()->name }}</span>
      </div>
      <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    @php
      $initials  = strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1));
      $age       = $donor->age       ?? 0;
      $weight    = $donor->weight_kg ?? 0;
      $hemo      = $donor->hemoglobin ?? 0;
      $ageBar    = $age    > 0 ? min(100, round(($age    / 65)  * 100)) : 0;
      $weightBar = $weight > 0 ? min(100, round(($weight / 100) * 100)) : 0;
      $hemoBar   = $hemo   > 0 ? min(100, round(($hemo   / 18)  * 100)) : 0;
      $confColor = ($donor->ai_confidence ?? 0) >= 80
                    ? 'var(--green)'
                    : (($donor->ai_confidence ?? 0) >= 50 ? 'var(--amber)' : 'var(--red)');
    @endphp

    <div class="page-grid">

      {{-- LEFT — PROFILE CARD --}}
      <div>
        <div class="profile-card">
          <div class="pc-top">
            <div class="pc-bg">
              <div class="pc-circle pcc1"></div>
              <div class="pc-circle pcc2"></div>
            </div>
            <div class="pc-avatar" @if($donor->profile_image) style="background-image:url('{{ \Illuminate\Support\Facades\Storage::url($donor->profile_image) }}');background-size:cover;background-position:center;" @endif>
              @unless($donor->profile_image)
                {{ $initials }}
              @endunless
            </div>
            <div class="pc-name">{{ $donor->full_name }}</div>
            <div class="pc-email">{{ $donor->email }}</div>
            <span class="pc-blood">{{ $donor->blood_group ?? 'N/A' }}</span>
          </div>
          <div class="pc-body">
            <div class="pc-row">
              <span class="pc-key">ID</span>
              <span class="pc-val">#{{ $donor->id }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Phone</span>
              <span class="pc-val">{{ $donor->phone ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Gender</span>
              <span class="pc-val">{{ $donor->gender ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">NIC</span>
              <span class="pc-val">{{ $donor->nic ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">City</span>
              <span class="pc-val">{{ $donor->city ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">District</span>
              <span class="pc-val">{{ $donor->district ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Donation Center</span>
              <span class="pc-val">{{ $donor->donation_center ?? '—' }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Registered</span>
              <span class="pc-val">{{ $donor->created_at->format('d M Y') }}</span>
            </div>
            <div class="pc-row">
              <span class="pc-key">Status</span>
              <span class="badge {{ $donor->is_eligible ? 'b-elig' : 'b-not' }}">
                {{ $donor->is_eligible ? 'Eligible' : 'Not Eligible' }}
              </span>
            </div>
          </div>

          {{-- ACTION BUTTONS --}}
          <div class="action-btns">
            {{-- TOGGLE ELIGIBILITY --}}
            <form method="POST" action="{{ route('admin.donors.toggle', $donor->id) }}" style="margin:0;">
              @csrf
              <button type="submit"
                      class="action-btn {{ $donor->is_eligible ? 'ab-toggle-off' : 'ab-toggle-on' }}"
                      onclick="return confirm('{{ $donor->is_eligible ? 'Mark as NOT eligible?' : 'Mark as ELIGIBLE?' }}')">
                <svg viewBox="0 0 24 24">
                  @if($donor->is_eligible)
                    <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                  @else
                    <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/>
                  @endif
                </svg>
                {{ $donor->is_eligible ? 'Mark as Not Eligible' : 'Mark as Eligible' }}
              </button>
            </form>

            {{-- DELETE --}}
            <form method="POST" action="{{ route('admin.donors.destroy', $donor->id) }}" style="margin:0;">
              @csrf
              @method('DELETE')
              <button type="submit"
                      class="action-btn ab-delete"
                      onclick="return confirm('Permanently delete {{ $donor->full_name }}? This cannot be undone.')">
                <svg viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                Delete Donor
              </button>
            </form>

            <a href="{{ route('admin.donors.index') }}" class="action-btn ab-back">
              <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
              Back to List
            </a>
          </div>
        </div>
      </div>

      {{-- RIGHT COLUMN --}}
      <div>

        {{-- HEALTH INFORMATION --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">Health Information</div>
              <div class="card-s">Registered health data used for AI prediction</div>
            </div>
          </div>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-key">Age</div>
              <div class="info-val large">{{ $age > 0 ? $age : '—' }}<span style="font-size:0.9rem;font-family:'DM Sans';font-weight:300;">{{ $age > 0 ? ' yrs' : '' }}</span></div>
            </div>
            <div class="info-item">
              <div class="info-key">Weight</div>
              <div class="info-val large">{{ $weight > 0 ? $weight : '—' }}<span style="font-size:0.9rem;font-family:'DM Sans';font-weight:300;">{{ $weight > 0 ? ' kg' : '' }}</span></div>
            </div>
            <div class="info-item">
              <div class="info-key">Hemoglobin</div>
              <div class="info-val large">{{ $hemo > 0 ? number_format($hemo,1) : '—' }}<span style="font-size:0.9rem;font-family:'DM Sans';font-weight:300;">{{ $hemo > 0 ? ' g/dL' : '' }}</span></div>
            </div>
            <div class="info-item">
              <div class="info-key">Total Donations</div>
              <div class="info-val large">{{ $donor->total_donations ?? 0 }}</div>
            </div>
            <div class="info-item">
              <div class="info-key">Date of Birth</div>
              <div class="info-val">{{ $donor->date_of_birth ? \Carbon\Carbon::parse($donor->date_of_birth)->format('d M Y') : '—' }}</div>
            </div>
            <div class="info-item">
              <div class="info-key">Last Donation</div>
              <div class="info-val">{{ $donor->last_donation_date ? \Carbon\Carbon::parse($donor->last_donation_date)->format('d M Y') : 'Never' }}</div>
            </div>
          </div>
        </div>

        {{-- AI PREDICTION BREAKDOWN --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">AI Prediction Breakdown</div>
              <div class="card-s">{{ $modelMetrics['model'] ?? 'AI' }} model analysis</div>
            </div>
            <span class="badge {{ $donor->is_eligible ? 'b-elig' : 'b-not' }}">
              {{ $donor->is_eligible ? 'Eligible' : 'Not Eligible' }}
            </span>
          </div>

          {{-- METRIC BARS --}}
          <div class="ai-metrics">
            <div class="ai-metric">
              <div class="am-val">{{ $age > 0 ? $age : '—' }}</div>
              <div class="am-label">Age (years)</div>
              <div class="am-bar">
                <div class="am-fill {{ $age >= 18 ? 'fill-g' : 'fill-r' }}" style="width:{{ $ageBar }}%"></div>
              </div>
              <div class="am-status {{ $age >= 18 ? 'st-ok' : 'st-warn' }}">
                {{ $age >= 18 ? '✓ Pass (18+)' : '✗ Fail (18+)' }}
              </div>
            </div>
            <div class="ai-metric">
              <div class="am-val">{{ $weight > 0 ? $weight : '—' }}</div>
              <div class="am-label">Weight (kg)</div>
              <div class="am-bar">
                <div class="am-fill {{ $weight >= 50 ? 'fill-g' : 'fill-r' }}" style="width:{{ $weightBar }}%"></div>
              </div>
              <div class="am-status {{ $weight >= 50 ? 'st-ok' : 'st-warn' }}">
                {{ $weight >= 50 ? '✓ Pass (50+)' : '✗ Fail (50+)' }}
              </div>
            </div>
            <div class="ai-metric">
              <div class="am-val">{{ $hemo > 0 ? number_format($hemo,1) : '—' }}</div>
              <div class="am-label">Hemoglobin</div>
              <div class="am-bar">
                <div class="am-fill {{ $hemo >= 12 ? 'fill-g' : 'fill-r' }}" style="width:{{ $hemoBar }}%"></div>
              </div>
              <div class="am-status {{ $hemo >= 12 ? 'st-ok' : 'st-warn' }}">
                {{ $hemo >= 12 ? '✓ Pass (12+)' : '✗ Fail (12+)' }}
              </div>
            </div>
          </div>

          {{-- AI CONFIDENCE --}}
          <div style="background:var(--gray);border-radius:9px;padding:1rem 1.1rem;">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.5rem;">
              <span style="font-size:0.78rem;font-weight:500;color:var(--text);">AI Confidence Score</span>
              <span style="font-size:0.78rem;font-weight:500;color:var(--text);">
                {{ $donor->ai_confidence > 0 ? number_format($donor->ai_confidence,2).'%' : 'Not calculated' }}
              </span>
            </div>
            <div class="conf-wrap" style="margin-top:0;">
              <div class="conf-track">
                <div class="conf-fill" style="width:{{ $donor->ai_confidence ?? 0 }}%;background:{{ $confColor }};"></div>
              </div>
            </div>
           <div style="font-size:0.7rem;color:var(--muted);margin-top:6px;">
  Model: {{ $modelMetrics['model'] ?? 'unavailable' }} ·
  Last AI check:
  @if($donor->last_ai_check)
    {{ \Carbon\Carbon::parse($donor->last_ai_check)->format('d M Y, h:i A') }}
  @else
    At registration
  @endif
</div>
          </div>
        </div>

        {{-- ANOMALY DETECTION --}}
<div style="margin-top:1rem;padding:1rem 1.1rem;background:{{ $donor->is_anomaly ? '#FEF2F2' : 'var(--green-bg)' }};border:1px solid {{ $donor->is_anomaly ? '#FECACA' : 'var(--green-b)' }};border-radius:9px;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
    <div style="display:flex;align-items:center;gap:7px;">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none"
           stroke="{{ $donor->is_anomaly ? '#C8192A' : 'var(--green)' }}"
           stroke-width="2" stroke-linecap="round">
        @if($donor->is_anomaly)
          <path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
          <line x1="12" y1="9" x2="12" y2="13"/>
          <line x1="12" y1="17" x2="12.01" y2="17"/>
        @else
          <path d="M22 11.08V12a10 10 0 11-5.93-9.14"/>
          <polyline points="22 4 12 14.01 9 11.01"/>
        @endif
      </svg>
      <span style="font-size:0.78rem;font-weight:500;color:{{ $donor->is_anomaly ? '#991B1B' : 'var(--green)' }};">
        Anomaly Detection — {{ $donor->is_anomaly ? 'Suspicious Profile' : 'Normal Profile' }}
      </span>
    </div>
    <span style="font-size:0.68rem;font-weight:500;padding:2px 8px;border-radius:20px;background:{{ $donor->is_anomaly ? '#FECACA' : 'var(--green-b)' }};color:{{ $donor->is_anomaly ? '#991B1B' : 'var(--green)' }};">
      Isolation Forest
    </span>
  </div>
  <div style="font-size:0.75rem;color:{{ $donor->is_anomaly ? '#991B1B' : '#166534' }};line-height:1.5;">
    @if($donor->is_anomaly)
      This donor's health data falls outside the normal range of registered donors.
      Admin review recommended. Anomaly score: {{ number_format($donor->anomaly_score, 2) }}
    @else
      Donor health profile is within normal range. No suspicious patterns detected.
    @endif
  </div>
</div>

        {{-- MEDICAL NOTES --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">Medical Notes & Conditions</div>
              <div class="card-s">Additional health information provided by donor</div>
            </div>
          </div>
          @if($donor->medical_condition && $donor->medical_condition !== '')
            <div style="margin-bottom:0.75rem;">
              <span style="font-size:0.72rem;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;">Condition</span>
              <div style="font-size:0.85rem;font-weight:500;color:var(--text);margin-top:3px;">{{ $donor->medical_condition }}</div>
            </div>
          @endif
          <div class="notes-box {{ !$donor->medical_notes ? 'empty' : '' }}">
            {{ $donor->medical_notes ?? 'No additional health notes provided.' }}
          </div>
        </div>

        {{-- DONATION HISTORY --}}
        <div class="card">
          <div class="card-hd">
            <div>
              <div class="card-t">Donation History</div>
              <div class="card-s">{{ $donor->donations->count() }} recorded donation{{ $donor->donations->count() !== 1 ? 's' : '' }}</div>
            </div>
          </div>

          @if($donor->donations->count() > 0)
            <div class="info-grid" style="grid-template-columns:1fr;gap:0;">
              @foreach($donor->donations as $d)
                <div class="pc-row">
                  <span class="pc-key">{{ $d->donation_date->format('d M Y') }}</span>
                  <span class="pc-val">{{ $d->blood_group ?? '—' }} · {{ $d->donation_center ?? '—' }} · {{ $d->units }} unit{{ $d->units !== 1 ? 's' : '' }}</span>
                </div>
              @endforeach
            </div>
          @else
            <div class="notes-box empty">No donations recorded yet.</div>
          @endif

          <form method="POST" action="{{ route('admin.donors.donations.store', $donor->id) }}" style="margin-top:1rem;display:grid;grid-template-columns:1fr 1fr auto;gap:8px;align-items:end;">
            @csrf
            <div class="field">
              <label style="font-size:0.72rem;color:var(--muted);">Donation Date</label>
              <input type="date" name="donation_date" max="{{ now()->format('Y-m-d') }}" required
                     style="width:100%;padding:0.5rem 0.7rem;border:1px solid var(--gray-b);border-radius:7px;font-size:0.82rem;">
            </div>
            <div class="field">
              <label style="font-size:0.72rem;color:var(--muted);">Units</label>
              <input type="number" name="units" value="1" min="1" max="5"
                     style="width:100%;padding:0.5rem 0.7rem;border:1px solid var(--gray-b);border-radius:7px;font-size:0.82rem;">
            </div>
            <button type="submit" class="action-btn ab-toggle-on" style="white-space:nowrap;">Record Donation</button>
          </form>
          @error('donation_date')<span class="error-msg" style="color:var(--red);font-size:0.72rem;">{{ $message }}</span>@enderror
        </div>

      </div>
    </div>
  </div>
</div>