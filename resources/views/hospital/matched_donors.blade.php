<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F1F5F9;--gray-b:#E2E8F0;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-dark:#92400E;--amber-bg:#FFFBEB;--amber-b:#FDE68A;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --secondary:#0D9488;--secondary-dark:#115E59;
    --accent:#06B6D4;--accent-light:#ECFEFF;--accent-dark:#0E7490;
    --sb:66px;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);display:flex;min-height:100vh;}

  /* SIDEBAR */
  .sidebar{width:var(--sb);background:#0F172A;display:flex;flex-direction:column;align-items:center;padding:1.25rem 0;position:fixed;top:0;left:0;height:100vh;z-index:50;}
  .sb-logo{width:36px;height:36px;background:rgba(59,130,246,0.25);border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:2rem;}
  .sb-logo svg{width:18px;height:18px;fill:none;stroke:#93C5FD;stroke-width:1.75;}
  .sb-nav{display:flex;flex-direction:column;gap:4px;align-items:center;flex:1;width:100%;}
  .sb-item{width:44px;height:44px;border-radius:10px;display:flex;align-items:center;justify-content:center;transition:all 0.2s;position:relative;text-decoration:none;}
  .sb-item svg{width:20px;height:20px;stroke:rgba(255,255,255,0.3);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .sb-item:hover svg{stroke:rgba(255,255,255,0.7);}
  .sb-item.active{background:rgba(59,130,246,0.2);}
  .sb-item.active svg{stroke:#93C5FD;}
  .sb-tip{position:absolute;left:54px;background:#0F172A;color:#E2E8F0;font-size:0.72rem;padding:4px 10px;border-radius:5px;white-space:nowrap;opacity:0;pointer-events:none;transition:opacity 0.15s;border:1px solid rgba(255,255,255,0.08);z-index:99;}
  .sb-item:hover .sb-tip{opacity:1;}
  .sb-bot{margin-top:auto;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(59,130,246,0.3);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:#93C5FD;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .tb-left{display:flex;align-items:center;gap:12px;}
  .back-btn{display:flex;align-items:center;gap:6px;font-size:0.8rem;color:var(--muted);text-decoration:none;padding:0.4rem 0.9rem;border:1px solid var(--gray-b);border-radius:7px;transition:all 0.2s;}
  .back-btn:hover{border-color:var(--primary);color:var(--primary);}
  .back-btn svg{width:14px;height:14px;stroke:currentColor;fill:none;stroke-width:2;}
  .tb-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--text);}
  .tb-right{display:flex;align-items:center;gap:10px;}
  .hosp-pill{display:flex;align-items:center;gap:8px;background:#EFF6FF;border:1px solid #BFDBFE;border-radius:20px;padding:0.3rem 0.9rem 0.3rem 0.5rem;}
  .hosp-av{width:24px;height:24px;border-radius:6px;background:var(--blue);display:flex;align-items:center;justify-content:center;}
  .hosp-av svg{width:13px;height:13px;stroke:white;fill:none;stroke-width:2;}
  .hosp-name{font-size:0.78rem;font-weight:500;color:var(--blue);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}
  .logout-btn:hover{border-color:var(--primary);color:var(--primary);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* REQUEST SUMMARY BANNER */
  .req-banner{border-radius:16px;padding:1.5rem 2rem;margin-bottom:1.5rem;display:flex;align-items:center;gap:1.5rem;position:relative;overflow:hidden;}
  .rb-urgent{background:#0F172A;}
  .rb-standard{background:#0F172A;}
  .rb-critical{background:#92400E;}
  .rb-bg{position:absolute;inset:0;pointer-events:none;}
  .rbc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.06);}
  .rbc1{width:300px;height:300px;top:-100px;right:-60px;}
  .rbc2{width:180px;height:180px;bottom:-60px;right:160px;}
  .rb-icon{width:52px;height:52px;border-radius:50%;background:rgba(255,255,255,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;position:relative;z-index:2;}
  .rb-icon svg{width:24px;height:24px;fill:white;}
  .rb-text{flex:1;position:relative;z-index:2;}
  .rb-label{font-size:0.68rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:rgba(255,255,255,0.55);margin-bottom:4px;}
  .rb-title{font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:white;margin-bottom:4px;}
  .rb-sub{font-size:0.82rem;color:rgba(255,255,255,0.55);font-weight:300;}
  .rb-meta{display:flex;gap:8px;margin-top:0.75rem;position:relative;z-index:2;flex-wrap:wrap;}
  .rb-tag{background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:6px;padding:0.35rem 0.8rem;font-size:0.75rem;color:rgba(255,255,255,0.8);}
  .rb-right{position:relative;z-index:2;text-align:center;flex-shrink:0;}
  .rb-count{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:white;line-height:1;}
  .rb-count-label{font-size:0.7rem;color:rgba(255,255,255,0.5);letter-spacing:0.06em;text-transform:uppercase;margin-top:2px;}

  /* LAYOUT */
  .main-grid{display:grid;grid-template-columns:1fr 300px;gap:14px;}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .card-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}

  /* AI HEADER */
  .ai-header{display:flex;align-items:center;gap:10px;padding:1rem 1.25rem;background:var(--off);border-bottom:1px solid var(--border);border-radius:12px 12px 0 0;margin:-1.25rem -1.5rem 1.25rem;}
  .ai-icon{width:32px;height:32px;background:var(--primary);border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .ai-icon svg{width:16px;height:16px;stroke:white;fill:none;stroke-width:2;}
  .ai-hd-t{font-size:0.875rem;font-weight:500;color:var(--text);}
  .ai-hd-s{font-size:0.72rem;color:var(--muted);}
  .ai-status{margin-left:auto;font-size:0.7rem;font-weight:500;padding:3px 9px;border-radius:20px;background:var(--green-bg);color:var(--green);}

  /* DONOR MATCH CARDS */
  .match-list{display:flex;flex-direction:column;gap:10px;}
  .match-card{border:1px solid var(--border);border-radius:11px;padding:1rem 1.1rem;transition:all 0.2s;position:relative;}
  .match-card:hover{border-color:rgba(29,78,216,0.3);background:var(--primary-light);}
  .match-card.top{border-color:var(--primary);background:var(--primary-light);}
  .top-badge{position:absolute;top:-1px;right:12px;font-size:0.6rem;font-weight:500;background:var(--primary);color:white;padding:2px 9px;border-radius:0 0 6px 6px;letter-spacing:0.05em;text-transform:uppercase;}
  .mc-row1{display:flex;align-items:center;gap:10px;margin-bottom:0.65rem;}
  .mc-av{width:36px;height:36px;border-radius:50%;background:var(--primary-light);color:var(--primary-dark);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:500;flex-shrink:0;}
  .mc-info{flex:1;}
  .mc-name{font-size:0.875rem;font-weight:500;color:var(--text);}
  .mc-meta{font-size:0.7rem;color:var(--muted);}
  .mc-blood{display:inline-block;background:var(--primary-light);color:var(--primary-dark);font-size:0.72rem;font-weight:600;padding:2px 8px;border-radius:5px;}
  .mc-score-wrap{display:flex;align-items:center;gap:6px;margin-bottom:0.5rem;}
  .mc-score-label{font-size:0.68rem;color:var(--muted);min-width:55px;}
  .mc-score-bar{flex:1;background:var(--gray-b);border-radius:3px;height:5px;overflow:hidden;}
  .mc-score-fill{height:5px;border-radius:3px;background:var(--accent);}
  .mc-score-val{font-size:0.72rem;font-weight:500;color:var(--text);min-width:30px;text-align:right;}
  .mc-tags{display:flex;gap:5px;flex-wrap:wrap;margin-bottom:0.65rem;}
  .mc-tag{font-size:0.65rem;padding:2px 7px;border-radius:4px;}
  .tag-g{background:var(--green-bg);color:#166534;}
  .tag-b{background:var(--blue-bg);color:#1D4ED8;}
  .tag-gray{background:var(--gray);color:var(--muted);}
  .mc-actions{display:flex;gap:7px;}
  .mc-btn{flex:1;padding:0.45rem;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:0.75rem;font-weight:400;cursor:pointer;transition:all 0.18s;border:1px solid var(--gray-b);background:white;color:var(--muted);display:flex;align-items:center;justify-content:center;gap:5px;text-decoration:none;}
  .mc-btn svg{width:12px;height:12px;stroke:currentColor;fill:none;stroke-width:2;}
  .mc-btn:hover{border-color:var(--primary);color:var(--primary);}
  .mc-btn.primary{background:var(--primary);color:white;border-color:var(--primary);}
  .mc-btn.primary:hover{background:var(--primary-dark);}

  /* EMPTY */
  .empty-state{text-align:center;padding:3rem 1.5rem;}
  .empty-state svg{width:48px;height:48px;stroke:var(--gray-b);fill:none;stroke-width:1.5;margin-bottom:1rem;}
  .empty-state h3{font-family:'Playfair Display',serif;font-size:1.1rem;color:var(--text);margin-bottom:0.5rem;}
  .empty-state p{font-size:0.82rem;color:var(--muted);line-height:1.6;}

  /* RIGHT PANEL */
  .right-col{display:flex;flex-direction:column;gap:14px;}

  /* REQUEST DETAIL */
  .req-detail-card{background:white;border:1px solid var(--border);border-radius:14px;overflow:hidden;}
  .rd-top{background:var(--primary-dark);padding:1.25rem;text-align:center;}
  .rd-blood{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:white;line-height:1;}
  .rd-blood-label{font-size:0.72rem;color:rgba(255,255,255,0.55);letter-spacing:0.06em;text-transform:uppercase;margin-top:4px;}
  .rd-body{padding:1rem 1.25rem;}
  .rd-row{display:flex;align-items:center;justify-content:space-between;padding:0.5rem 0;border-bottom:1px solid rgba(29,78,216,0.06);}
  .rd-row:last-child{border-bottom:none;}
  .rd-key{font-size:0.75rem;color:var(--muted);}
  .rd-val{font-size:0.8rem;font-weight:500;color:var(--text);}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;}
  .b-std{background:var(--gray);color:var(--muted);}
  .b-urg{background:var(--amber-bg);color:var(--amber);}
  .b-crit{background:var(--amber-b);color:var(--amber-dark);}
  .b-pen{background:var(--blue-bg);color:var(--blue);}

  /* FULFILL BUTTON */
  .fulfill-btn{width:100%;padding:0.7rem;border:none;border-radius:8px;background:var(--green);color:white;font-family:'DM Sans',sans-serif;font-size:0.85rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:7px;margin-top:0.75rem;}
  .fulfill-btn:hover{background:#15803D;}
  .fulfill-btn svg{width:15px;height:15px;stroke:white;fill:none;stroke-width:2;}
  .new-req-btn{width:100%;padding:0.65rem;border:1px solid var(--border);border-radius:8px;background:transparent;font-family:'DM Sans',sans-serif;font-size:0.82rem;color:var(--muted);cursor:pointer;transition:all 0.2s;text-decoration:none;display:flex;align-items:center;justify-content:center;gap:6px;margin-top:8px;}
  .new-req-btn:hover{border-color:var(--primary);color:var(--primary);}
  .new-req-btn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}

  /* TIPS CARD */
  .tip-item{display:flex;gap:10px;padding:0.65rem 0;border-bottom:1px solid rgba(29,78,216,0.06);}
  .tip-item:last-child{border-bottom:none;}
  .tip-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;margin-top:5px;}
  .tip-text{font-size:0.78rem;color:var(--muted);line-height:1.55;}
</style>

{{-- SIDEBAR --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('hospital.dashboard') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tip">Dashboard</span>
    </a>
    <a href="{{ route('hospital.request.create') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tip">New Request</span>
    </a>
    <a href="{{ route('hospital.requests.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/><line x1="9" y1="15" x2="13" y2="15"/></svg>
      <span class="sb-tip">All Requests</span>
    </a>
    <a href="{{ route('hospital.appointments.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/><path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01"/></svg>
      <span class="sb-tip">Appointments</span>
    </a>
    <a href="{{ route('hospital.inventory.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M21 8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4a2 2 0 001-1.73V8z"/><path d="M3.27 6.96L12 12.01l8.73-5.05M12 22.08V12"/></svg>
      <span class="sb-tip">Blood Inventory</span>
    </a>
    <a href="{{ route('hospital.profile.edit') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tip">Settings</span>
    </a>
  </div>
  <div class="sb-bot">
    <div class="sb-avatar">{{ strtoupper(substr($hospital->name,0,2)) }}</div>
  </div>
</div>

{{-- MAIN --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="tb-left">
      <a href="{{ route('hospital.dashboard') }}" class="back-btn">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Back to Dashboard
      </a>
      <div class="tb-title">AI Matched Donors</div>
    </div>
    <div class="tb-right">
      <div class="hosp-pill">
        <div class="hosp-av"><svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg></div>
        <span class="hosp-name">{{ $hospital->name }}</span>
      </div>
      <form method="POST" action="{{ route('hospital.logout') }}" style="margin:0;">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </div>
  </div>

  <div class="content">

    {{-- REQUEST SUMMARY BANNER --}}
    @php
      $bannerClass = $bloodRequest->urgency === 'critical' ? 'rb-critical' : 'rb-standard';
      $matchCount  = $matched_donors->count();
    @endphp
    <div class="req-banner {{ $bannerClass }}">
      <div class="rb-bg"><div class="rbc rbc1"></div><div class="rbc rbc2"></div></div>
      <div class="rb-icon">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
      </div>
      <div class="rb-text">
        <div class="rb-label">Blood Request Submitted · AI Matching Complete</div>
        <div class="rb-title">{{ $bloodRequest->blood_group }} Blood Required</div>
        <div class="rb-sub">{{ $bloodRequest->units_needed }} unit{{ $bloodRequest->units_needed > 1 ? 's' : '' }} needed · {{ $hospital->name }}</div>
        <div class="rb-meta">
          <span class="rb-tag">{{ ucfirst($bloodRequest->urgency) }}</span>
          <span class="rb-tag">{{ $bloodRequest->ward ?? 'General' }}</span>
          <span class="rb-tag">Request #{{ $bloodRequest->id }}</span>
          @if($bloodRequest->required_by)
            <span class="rb-tag">Due: {{ \Carbon\Carbon::parse($bloodRequest->required_by)->format('d M Y') }}</span>
          @endif
        </div>
      </div>
      <div class="rb-right">
        <div class="rb-count">{{ $matchCount }}</div>
        <div class="rb-count-label">Donors Found</div>
      </div>
    </div>

    <div class="main-grid">

      {{-- MATCHED DONORS LIST --}}
      <div class="card">
        <div class="ai-header">
          <div class="ai-icon">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
          </div>
          <div>
            <div class="ai-hd-t">AI Matched Donors</div>
            <div class="ai-hd-s">Ranked by match score — blood compatibility · district · AI signals · {{ ucfirst($bloodRequest->urgency) }} urgency</div>
          </div>
          <span class="ai-status">{{ $matchCount }} eligible found</span>
        </div>

        @if($matched_donors->isNotEmpty())
          <div style="display:flex;align-items:center;gap:8px;margin-bottom:1rem;">
            <span style="font-size:0.72rem;color:var(--muted);">Sort by:</span>
            @php $sortLinks = ['match_score' => 'Match Score', 'distance' => 'Distance', 'confidence' => 'AI Confidence']; @endphp
            @foreach($sortLinks as $key => $label)
              <a href="{{ route('hospital.requests.show', $bloodRequest->id) }}?sort={{ $key }}"
                 style="font-size:0.75rem;padding:0.3rem 0.8rem;border-radius:20px;text-decoration:none;border:1px solid var(--gray-b,var(--border));
                        {{ ($sort ?? 'match_score') === $key ? 'background:var(--primary-light);color:var(--primary-dark);font-weight:600;border-color:var(--border);' : 'color:var(--muted);' }}">
                {{ $label }}
              </a>
            @endforeach
          </div>
        @endif

        @if($matched_donors->isEmpty())
          <div class="empty-state">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            <h3>No Eligible Donors Found</h3>
            <p>There are currently no eligible donors with blood group {{ $bloodRequest->blood_group }}.<br>
            The system will notify you when a matching donor registers.</p>
          </div>
        @else
          <div class="match-list">
            @foreach($matched_donors as $index => $donor)
              @php
                $initials    = strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1));
                $confidence  = $donor->ai_confidence ?? 0;
                $donations   = $donor->total_donations ?? 0;
                $matchScore  = $donor->match_score ?? 0;
                $exactMatch  = $donor->match_breakdown['exact_blood_match'] ?? true;
                $sameDistrict= $donor->match_breakdown['same_district'] ?? false;
              @endphp
              <div class="match-card {{ $index === 0 ? 'top' : '' }}">
                @if($index === 0)
                  <div class="top-badge">Best Match</div>
                @endif

                <div class="mc-row1">
                  <div class="mc-av">{{ $initials }}</div>
                  <div class="mc-info">
                    <div class="mc-name">{{ $donor->full_name }}</div>
                    <div class="mc-meta">
                      Age {{ $donor->age ?? '—' }} · {{ $donor->city ?? $donor->district ?? 'Unknown' }}
                      · {{ $donations }} donation{{ $donations !== 1 ? 's' : '' }}
                    </div>
                  </div>
                  <span class="mc-blood">{{ $donor->blood_group }}</span>
                </div>

                {{-- MATCH SCORE BAR (primary ranking signal) --}}
                <div class="mc-score-wrap">
                  <span class="mc-score-label" style="font-weight:600;color:var(--accent-dark);">Match</span>
                  <div class="mc-score-bar">
                    <div class="mc-score-fill" style="width:{{ $matchScore }}%;background:var(--accent-dark);"></div>
                  </div>
                  <span class="mc-score-val" style="color:var(--accent-dark);font-weight:600;">{{ number_format($matchScore,1) }}%</span>
                </div>

                {{-- AI SCORE BAR --}}
                <div class="mc-score-wrap">
                  <span class="mc-score-label">AI score</span>
                  <div class="mc-score-bar">
                    <div class="mc-score-fill" style="width:{{ $confidence }}%"></div>
                  </div>
                  <span class="mc-score-val">{{ number_format($confidence,1) }}%</span>
                </div>

{{-- RESPONSE PROBABILITY --}}
@if($donor->response_probability > 0)
  @php
    $respColor = $donor->response_level === 'high'
      ? 'var(--green)'
      : ($donor->response_level === 'medium' ? 'var(--amber)' : 'var(--amber-dark)');
  @endphp
  <div class="mc-score-wrap">
    <span class="mc-score-label" style="color:{{ $respColor }}">Response</span>
    <div class="mc-score-bar">
      <div style="height:5px;border-radius:3px;background:{{ $respColor }};width:{{ $donor->response_probability }}%"></div>
    </div>
    <span class="mc-score-val" style="color:{{ $respColor }}">
      {{ number_format($donor->response_probability,1) }}%
    </span>
  </div>
@endif

                {{-- TAGS --}}
                <div class="mc-tags">
                  @php $donorResponse = $donorResponses->get($donor->id); @endphp
                  @if($donorResponse)
                    @if($donorResponse->status === 'available')
                      <span class="mc-tag tag-g">✓ Confirmed available</span>
                    @else
                      <span class="mc-tag tag-gray">Responded: not available</span>
                    @endif
                  @endif
                  <span class="mc-tag tag-g">Eligible</span>
                  @if($exactMatch)
                    <span class="mc-tag tag-g">Exact {{ $donor->blood_group }} match</span>
                  @else
                    <span class="mc-tag tag-b">Compatible donor</span>
                  @endif
                  @if($sameDistrict)
                    <span class="mc-tag tag-g">Same district</span>
                  @elseif($donor->district)
                    <span class="mc-tag tag-gray">{{ $donor->district }}</span>
                  @endif
                  @if($donor->distance_km !== null)
                    <span class="mc-tag tag-b">{{ number_format($donor->distance_km, 1) }} km · {{ $donor->travel_category }}</span>
                  @else
                    <span class="mc-tag tag-gray">Distance unknown</span>
                  @endif
                  @if($donor->hemoglobin)
                    <span class="mc-tag tag-b">Hemo {{ number_format($donor->hemoglobin,1) }}</span>
                  @endif
                  @if($donor->weight_kg)
                    <span class="mc-tag tag-b">{{ $donor->weight_kg }} kg</span>
                  @endif
                  @if($donations >= 5)
                    <span class="mc-tag tag-g">Experienced</span>
                  @elseif($donations === 0)
                    <span class="mc-tag tag-gray">First time</span>
                  @endif
                </div>

                {{-- ACTIONS --}}
                <div class="mc-actions">
                  @if($donor->phone)
                    <a href="tel:{{ $donor->phone }}" class="mc-btn">
                      <svg viewBox="0 0 24 24"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.77 19.79 19.79 0 01.95 1.14 2 2 0 012.91.96h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L7.09 8.77a16 16 0 006.29 6.29l1.17-1.17a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/></svg>
                      Call
                    </a>
                  @endif
                  <a href="mailto:{{ $donor->email }}" class="mc-btn">
                    <svg viewBox="0 0 24 24"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    Email
                  </a>
                  <form method="POST" action="{{ route('hospital.requests.notify', [$bloodRequest->id, $donor->id]) }}" style="flex:1;margin:0;">
                    @csrf
                    <button type="submit" class="mc-btn primary" style="width:100%;">
                      <svg viewBox="0 0 24 24"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                      Notify
                    </button>
                  </form>
                </div>
              </div>
            @endforeach
          </div>
        @endif
      </div>

      {{-- RIGHT PANEL --}}
      <div class="right-col">

        {{-- REQUEST DETAIL --}}
        <div class="req-detail-card">
          <div class="rd-top">
            <div class="rd-blood">{{ $bloodRequest->blood_group }}</div>
            <div class="rd-blood-label">Blood Group</div>
          </div>
          <div class="rd-body">
            <div class="rd-row">
              <span class="rd-key">Units Needed</span>
              <span class="rd-val">{{ $bloodRequest->units_needed }}</span>
            </div>
            <div class="rd-row">
              <span class="rd-key">Urgency</span>
              <span class="badge {{ $bloodRequest->urgency === 'critical' ? 'b-crit' : ($bloodRequest->urgency === 'urgent' ? 'b-urg' : 'b-std') }}">
                {{ ucfirst($bloodRequest->urgency) }}
              </span>
            </div>
            <div class="rd-row">
              <span class="rd-key">Ward</span>
              <span class="rd-val">{{ $bloodRequest->ward ?? 'General' }}</span>
            </div>
            <div class="rd-row">
              <span class="rd-key">Required By</span>
              <span class="rd-val">
                {{ $bloodRequest->required_by ? \Carbon\Carbon::parse($bloodRequest->required_by)->format('d M Y') : 'ASAP' }}
              </span>
            </div>
            <div class="rd-row">
              <span class="rd-key">Status</span>
              <span class="badge b-pen">Pending</span>
            </div>
            <div class="rd-row">
              <span class="rd-key">Request ID</span>
              <span class="rd-val">#{{ $bloodRequest->id }}</span>
            </div>
            @if($bloodRequest->notes)
              <div style="margin-top:0.75rem;padding:0.75rem;background:var(--off);border-radius:7px;font-size:0.78rem;color:var(--muted);line-height:1.55;">
                {{ $bloodRequest->notes }}
              </div>
            @endif
          </div>

          {{-- MARK AS FULFILLED --}}
          <div style="padding:0 1.25rem 1.25rem;">
            <form method="POST" action="{{ route('hospital.requests.fulfill', $bloodRequest->id) }}">
              @csrf
              <button type="submit" class="fulfill-btn"
                      onclick="return confirm('Mark this request as fulfilled?')">
                <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                Mark as Fulfilled
              </button>
            </form>
            <a href="{{ route('hospital.request.create') }}" class="new-req-btn">
              <svg viewBox="0 0 24 24"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
              New Blood Request
            </a>
          </div>
        </div>

        {{-- TIPS --}}
        <div class="card">
          <div class="card-hd">
            <div class="card-t">How to Contact Donors</div>
          </div>
          <div class="tip-item">
            <div class="tip-dot" style="background:var(--secondary);"></div>
            <div class="tip-text">Click <strong>Notify</strong> to email the donor about your blood request, with a link for them to confirm their availability.</div>
          </div>
          <div class="tip-item">
            <div class="tip-dot" style="background:var(--blue);"></div>
            <div class="tip-text">Click <strong>Call</strong> to directly call the donor's registered phone number.</div>
          </div>
          <div class="tip-item">
            <div class="tip-dot" style="background:var(--green);"></div>
            <div class="tip-text">Once a donor confirms, mark the request as <strong>Fulfilled</strong> to close it.</div>
          </div>
          <div class="tip-item">
            <div class="tip-dot" style="background:var(--amber);"></div>
            <div class="tip-text">Donors are ranked by <strong>match score</strong> — blood compatibility, district, and AI-predicted reliability — contact the top match first.</div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
