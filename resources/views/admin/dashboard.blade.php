<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;
    --white:#fff;--off:#F7F3F3;--text:#1A0A0B;--muted:#6B3B40;
    --border:rgba(200,25,42,0.12);--gray:#F4F1F1;--gray-b:#E4DEDE;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;
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
  .sb-bottom{margin-top:auto;display:flex;flex-direction:column;align-items:center;gap:8px;padding-bottom:0.5rem;}
  .sb-avatar{width:34px;height:34px;border-radius:50%;background:rgba(255,255,255,0.2);display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;color:white;}

  /* MAIN */
  .main{margin-left:var(--sb);flex:1;display:flex;flex-direction:column;}

  /* TOPBAR */
  .topbar{background:white;border-bottom:1px solid var(--border);padding:0 2rem;height:58px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:40;}
  .topbar-left{display:flex;flex-direction:column;}
  .topbar-label{font-size:0.7rem;color:var(--muted);letter-spacing:0.07em;text-transform:uppercase;}
  .topbar-title{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);line-height:1.1;}
  .topbar-right{display:flex;align-items:center;gap:12px;}
  .admin-pill{display:flex;align-items:center;gap:8px;background:var(--red-light);border:1px solid var(--border);border-radius:20px;padding:0.35rem 0.9rem 0.35rem 0.5rem;}
  .admin-avatar{width:24px;height:24px;border-radius:50%;background:var(--red);display:flex;align-items:center;justify-content:center;font-size:0.65rem;font-weight:500;color:white;}
  .admin-name{font-size:0.78rem;font-weight:500;color:var(--red-dark);}
  .logout-btn{padding:0.4rem 1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;transition:all 0.2s;}
  .logout-btn:hover{border-color:var(--red);color:var(--red);}

  /* CONTENT */
  .content{padding:1.75rem 2rem;}

  /* ALERTS */
  .alert-success{background:var(--green-bg);border:1px solid var(--green-b);border-radius:8px;padding:0.85rem 1.1rem;margin-bottom:1.25rem;font-size:0.85rem;color:var(--green);}

  /* STAT CARDS */
  .stat-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:1.75rem;}
  .stat-card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .stat-card-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;}
  .stat-icon{width:38px;height:38px;border-radius:9px;display:flex;align-items:center;justify-content:center;}
  .si-red{background:var(--red-light);}  .si-red svg{stroke:var(--red);}
  .si-blue{background:#EFF6FF;}          .si-blue svg{stroke:#2563EB;}
  .si-green{background:#F0FDF4;}         .si-green svg{stroke:#16A34A;}
  .si-amber{background:#FFFBEB;}         .si-amber svg{stroke:#D97706;}
  .stat-icon svg{width:18px;height:18px;fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .stat-trend{font-size:0.72rem;font-weight:500;padding:3px 8px;border-radius:20px;}
  .trend-up{background:#F0FDF4;color:#16A34A;}
  .trend-neu{background:var(--gray);color:var(--muted);}
  .trend-down{background:#FFF7ED;color:#C2410C;}
  .stat-num{font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:var(--text);line-height:1;margin-bottom:4px;}
  .stat-label{font-size:0.78rem;color:var(--muted);font-weight:300;}
  .stat-sub{font-size:0.7rem;color:var(--muted);margin-top:6px;}

  /* GRID */
  .grid-2{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:1.75rem;}
  .grid-3-1{display:grid;grid-template-columns:2fr 1fr;gap:14px;margin-bottom:1.75rem;}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .card-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.25rem;}
  .card-title{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-sub{font-size:0.72rem;color:var(--muted);margin-top:2px;}
  .card-action{font-size:0.75rem;color:var(--red);text-decoration:none;}

  /* BAR CHART */
  .bar-chart{display:flex;flex-direction:column;gap:10px;}
  .bar-row{display:flex;align-items:center;gap:10px;}
  .bar-label{font-size:0.78rem;color:var(--muted);width:32px;flex-shrink:0;}
  .bar-track{flex:1;background:var(--gray);border-radius:4px;height:8px;overflow:hidden;}
  .bar-fill{height:8px;border-radius:4px;background:var(--red);}
  .bar-val{font-size:0.75rem;font-weight:500;color:var(--text);width:34px;text-align:right;flex-shrink:0;}

  /* DONUT */
  .donut-wrap{display:flex;align-items:center;gap:1.25rem;}
  .donut-legend{display:flex;flex-direction:column;gap:8px;}
  .dl-row{display:flex;align-items:center;gap:8px;font-size:0.78rem;}
  .dl-dot{width:10px;height:10px;border-radius:50%;flex-shrink:0;}
  .dl-label{color:var(--muted);}
  .dl-val{font-weight:500;color:var(--text);margin-left:auto;padding-left:1rem;}

  /* TABLE */
  .table-wrap{overflow-x:auto;}
  table{width:100%;border-collapse:collapse;font-size:0.825rem;}
  thead th{padding:0.6rem 1rem;text-align:left;font-size:0.7rem;font-weight:500;letter-spacing:0.07em;text-transform:uppercase;color:var(--muted);border-bottom:1px solid var(--border);background:var(--gray);white-space:nowrap;}
  thead th:first-child{border-radius:8px 0 0 0;}
  thead th:last-child{border-radius:0 8px 0 0;}
  tbody tr{border-bottom:1px solid rgba(200,25,42,0.06);transition:background 0.15s;}
  tbody tr:last-child{border-bottom:none;}
  tbody tr:hover{background:var(--red-light);}
  td{padding:0.75rem 1rem;color:var(--text);vertical-align:middle;}
  .td-name{display:flex;align-items:center;gap:10px;}
  .td-avatar{width:30px;height:30px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:0.7rem;font-weight:500;flex-shrink:0;background:var(--red-light);color:var(--red-dark);}
  .td-name-text{font-weight:400;color:var(--text);}
  .td-name-sub{font-size:0.7rem;color:var(--muted);}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;white-space:nowrap;}
  .b-elig{background:#F0FDF4;color:#166534;}
  .b-not{background:#FFF7ED;color:#C2410C;}
  .blood-pill{display:inline-block;background:var(--red-light);color:var(--red-dark);font-size:0.72rem;font-weight:500;padding:3px 8px;border-radius:5px;}

  /* ACTIVITY */
  .activity-list{display:flex;flex-direction:column;}
  .act-item{display:flex;gap:12px;padding:0.75rem 0;border-bottom:1px solid rgba(200,25,42,0.06);}
  .act-item:last-child{border-bottom:none;}
  .act-dot-wrap{display:flex;flex-direction:column;align-items:center;padding-top:4px;}
  .act-dot{width:8px;height:8px;border-radius:50%;flex-shrink:0;}
  .act-line{flex:1;width:1px;background:var(--border);margin-top:4px;}
  .act-item:last-child .act-line{display:none;}
  .ad-red{background:var(--red);}
  .ad-green{background:#16A34A;}
  .ad-blue{background:#2563EB;}
  .act-text{font-size:0.8rem;color:var(--text);line-height:1.5;}
  .act-time{font-size:0.7rem;color:var(--muted);margin-top:2px;}

  /* EMPTY */
  .empty-state{text-align:center;padding:2rem;color:var(--muted);font-size:0.85rem;}

  @keyframes pulse {
  0%, 100% { opacity: 1; }
  50%       { opacity: 0.3; }
}
</style>

{{-- ── SIDEBAR ── --}}
<div class="sidebar">
  <div class="sb-logo">
    <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
  </div>
  <div class="sb-nav">
    <a href="{{ route('admin.dashboard') }}" class="sb-item active">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      <span class="sb-tooltip">Dashboard</span>
    </a>
    <a href="{{ route('admin.donors.index') }}" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"/></svg>
      <span class="sb-tooltip">Donors</span>
    </a>
    <a href="#" class="sb-item">
      <svg viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M12 8v8M8 12h8"/></svg>
      <span class="sb-tooltip">Hospitals</span>
    </a>
    <a href="#" class="sb-item">
      <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      <span class="sb-tooltip">AI Predictions</span>
    </a>
    <a href="#" class="sb-item">
      <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
      <span class="sb-tooltip">Settings</span>
    </a>
  </div>
  <div class="sb-bottom">
    <div class="sb-avatar">
      {{ strtoupper(substr(auth('admin')->user()->name, 0, 2)) }}
    </div>
  </div>
</div>

{{-- ── MAIN ── --}}
<div class="main">

  {{-- TOPBAR --}}
  <div class="topbar">
    <div class="topbar-left">
      <div class="topbar-label">Admin Panel</div>
      <div class="topbar-title">Dashboard Overview</div>
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

    {{-- ALERTS --}}
    @if(session('success'))
      <div class="alert-success">{{ session('success') }}</div>
    @endif

    {{-- ── STAT CARDS ── --}}
    @php
      $eligiblePct = $stats['total_donors'] > 0
        ? round(($stats['eligible_donors'] / $stats['total_donors']) * 100)
        : 0;
    @endphp


    {{-- AI SCHEDULER STATUS --}}
@php
  $lastCheck = \App\Models\Donor::whereNotNull('last_ai_check')
                 ->latest('last_ai_check')->value('last_ai_check');
@endphp
<div style="background:var(--blue-bg);border:1px solid var(--blue-b);border-radius:10px;padding:0.85rem 1.25rem;margin-bottom:1.5rem;display:flex;align-items:center;justify-content:space-between;">
  <div style="display:flex;align-items:center;gap:10px;">
    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="var(--blue)" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
    <div>
      <div style="font-size:0.8rem;font-weight:500;color:var(--blue);">AI Eligibility Scheduler</div>
      <div style="font-size:0.72rem;color:#1E40AF;">Runs daily at midnight · Re-checks all donors using Logistic Regression</div>
    </div>
  </div>
  <div style="text-align:right;">
    <div style="font-size:0.72rem;color:var(--blue);font-weight:500;">Last run</div>
    <div style="font-size:0.78rem;color:#1E40AF;">
      {{ $lastCheck ? \Carbon\Carbon::parse($lastCheck)->diffForHumans() : 'Not run yet' }}
    </div>
  </div>
</div>

    {{-- ── KNN BLOOD SHORTAGE ALERTS ── --}}
@if(count($shortageAlerts) > 0)
<div style="margin-bottom:1.75rem;">

  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
    <div style="display:flex;align-items:center;gap:8px;">
      <div style="width:8px;height:8px;border-radius:50%;background:var(--red);animation:pulse 2s infinite;"></div>
      <span style="font-size:0.78rem;font-weight:500;color:var(--text);">Blood Shortage Alerts</span>
      <span style="font-size:0.7rem;background:var(--red-light);color:var(--red-dark);padding:2px 8px;border-radius:20px;font-weight:500;">
        {{ count($shortageAlerts) }} alert{{ count($shortageAlerts) > 1 ? 's' : '' }}
      </span>
      @if($aiUsed)
        <span style="font-size:0.68rem;background:#EFF6FF;color:#1D4ED8;padding:2px 8px;border-radius:20px;border:1px solid #BFDBFE;">
          ⚡ KNN AI
        </span>
      @endif
    </div>
    <span style="font-size:0.72rem;color:var(--muted);">{{ now()->format('d M Y, h:i A') }}</span>
  </div>

  <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:10px;">
    @foreach($shortageAlerts as $alert)
      @php
        $isCritical = $alert['level'] === 'critical';
        $bg     = $isCritical ? '#FEF2F2' : '#FFFBEB';
        $border = $isCritical ? '#FECACA' : '#FDE68A';
        $color  = $isCritical ? '#991B1B' : '#92400E';
        $label  = $isCritical ? 'CRITICAL' : 'LOW STOCK';
        $dotClr = $isCritical ? '#C8192A' : '#D97706';
      @endphp
      <div style="background:{{ $bg }};border:1px solid {{ $border }};border-radius:12px;padding:1rem 1.1rem;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:0.75rem;">

          {{-- BLOOD GROUP --}}
          <div style="width:44px;height:44px;border-radius:9px;background:white;border:1px solid {{ $border }};display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;color:{{ $color }};flex-shrink:0;">
            {{ $alert['blood_group'] }}
          </div>

          <div style="flex:1;">
            <div style="font-size:0.65rem;font-weight:600;letter-spacing:0.08em;color:{{ $dotClr }};margin-bottom:2px;">{{ $label }}</div>
            <div style="font-size:0.82rem;font-weight:500;color:{{ $color }};">{{ $alert['message'] }}</div>
          </div>
        </div>

        {{-- STATS ROW --}}
        <div style="display:flex;gap:8px;margin-bottom:0.65rem;">
          <div style="flex:1;background:white;border-radius:6px;padding:0.4rem 0.6rem;text-align:center;border:1px solid {{ $border }};">
            <div style="font-size:0.7rem;color:{{ $color }};opacity:0.7;">Eligible</div>
            <div style="font-size:0.9rem;font-weight:600;color:{{ $color }};">{{ $alert['eligible_count'] }}</div>
          </div>
          <div style="flex:1;background:white;border-radius:6px;padding:0.4rem 0.6rem;text-align:center;border:1px solid {{ $border }};">
            <div style="font-size:0.7rem;color:{{ $color }};opacity:0.7;">Total</div>
            <div style="font-size:0.9rem;font-weight:600;color:{{ $color }};">{{ $alert['total_donors'] }}</div>
          </div>
          <div style="flex:1;background:white;border-radius:6px;padding:0.4rem 0.6rem;text-align:center;border:1px solid {{ $border }};">
            <div style="font-size:0.7rem;color:{{ $color }};opacity:0.7;">Requests</div>
            <div style="font-size:0.9rem;font-weight:600;color:{{ $color }};">{{ $alert['requests_month'] }}</div>
          </div>
        </div>

        {{-- KNN CONFIDENCE BAR --}}
        @if($alert['confidence'] > 0)
          <div style="font-size:0.68rem;color:{{ $color }};opacity:0.7;margin-bottom:4px;">
            KNN Confidence: {{ number_format($alert['confidence'],1) }}%
          </div>
          <div style="background:white;border-radius:3px;height:4px;overflow:hidden;border:1px solid {{ $border }};">
            <div style="height:4px;border-radius:3px;background:{{ $dotClr }};width:{{ $alert['confidence'] }}%;"></div>
          </div>
        @endif
      </div>
    @endforeach
  </div>
</div>
@endif

{{-- ALL GOOD --}}
@if(count($shortageAlerts) === 0)
  <div style="background:var(--green-bg);border:1px solid var(--green-b);border-radius:12px;padding:1rem 1.5rem;margin-bottom:1.75rem;display:flex;align-items:center;gap:12px;">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--green)" stroke-width="2" stroke-linecap="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
    <div>
      <div style="font-size:0.85rem;font-weight:500;color:var(--green);">
        All Blood Groups Sufficient
        @if($aiUsed)<span style="font-size:0.7rem;background:white;color:#166534;padding:2px 8px;border-radius:20px;margin-left:6px;border:1px solid var(--green-b);">⚡ KNN AI</span>@endif
      </div>
      <div style="font-size:0.75rem;color:#166534;margin-top:1px;">No shortage alerts · {{ now()->format('d M Y, h:i A') }}</div>
    </div>
  </div>
@endif


{{-- ── BLOOD DEMAND FORECASTING ── --}}
<div style="margin-bottom:1.75rem;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
    <div style="display:flex;align-items:center;gap:8px;">
      <span style="font-size:0.78rem;font-weight:500;color:var(--text);">Blood Demand Forecast</span>
      <span style="font-size:0.68rem;background:var(--blue-bg);color:var(--blue);padding:2px 8px;border-radius:20px;border:1px solid var(--blue-b);">
        ⚡ Linear Regression AI
      </span>
    </div>
    <span style="font-size:0.72rem;color:var(--muted);">Predicted demand for next week</span>
  </div>

  <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:10px;">
    @foreach($demandForecasts as $bg => $forecast)
      @php
        $level  = $forecast['demand_level'];
        $bg_col = $level === 'high'   ? '#FEF2F2'      : ($level === 'medium' ? '#FFFBEB'      : '#F0FDF4');
        $border = $level === 'high'   ? '#FECACA'      : ($level === 'medium' ? '#FDE68A'      : '#BBF7D0');
        $color  = $level === 'high'   ? '#991B1B'      : ($level === 'medium' ? '#92400E'      : '#166534');
        $label  = $level === 'high'   ? '↑ HIGH'       : ($level === 'medium' ? '→ MEDIUM'     : '↓ LOW');
        $trend  = $forecast['trend']  === 'increasing' ? '↑ Rising'  : '↓ Falling';
      @endphp
      <div style="background:{{ $bg_col }};border:1px solid {{ $border }};border-radius:12px;padding:1rem;">

        {{-- BLOOD GROUP + LEVEL --}}
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
          <span style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:{{ $color }};">
            {{ $bg }}
          </span>
          <span style="font-size:0.65rem;font-weight:600;letter-spacing:0.06em;color:{{ $color }};">
            {{ $label }}
          </span>
        </div>

        {{-- PREDICTED COUNT --}}
        <div style="font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:{{ $color }};line-height:1;margin-bottom:2px;">
          {{ number_format($forecast['predicted_requests'], 1) }}
        </div>
        <div style="font-size:0.7rem;color:{{ $color }};opacity:0.7;margin-bottom:0.75rem;">
          predicted requests
        </div>

        {{-- MINI HISTORY BARS --}}
        <div style="display:flex;align-items:flex-end;gap:3px;height:24px;margin-bottom:0.5rem;">
          @foreach($forecast['week_history'] as $w => $count)
            @php
              $maxH = max(array_merge($forecast['week_history'], [1]));
              $h    = $maxH > 0 ? round(($count / $maxH) * 24) : 2;
              $h    = max($h, 2);
            @endphp
            <div style="flex:1;background:{{ $color }};opacity:{{ 0.4 + ($w * 0.15) }};border-radius:2px;height:{{ $h }}px;"></div>
          @endforeach
          {{-- Predicted bar --}}
          @php
            $maxH  = max(array_merge($forecast['week_history'], [1]));
            $predH = $maxH > 0 ? round(($forecast['predicted_requests'] / $maxH) * 24) : 2;
            $predH = max($predH, 2);
          @endphp
          <div style="flex:1;background:{{ $color }};border-radius:2px;height:{{ $predH }}px;border:1px dashed {{ $color }};"></div>
        </div>

        <div style="display:flex;justify-content:space-between;font-size:0.65rem;color:{{ $color }};opacity:0.7;">
          <span>4w ago</span>
          <span>Next ↑</span>
        </div>

        {{-- TREND --}}
        <div style="margin-top:0.5rem;font-size:0.7rem;color:{{ $color }};font-weight:500;">
          {{ $trend }} · Linear Regression
        </div>
      </div>
    @endforeach
  </div>
</div>



{{-- ── DONOR CLUSTER ANALYSIS ── --}}
@if(isset($clusterResult['clusters']) && count($clusterResult['clusters']) > 0)
<div style="margin-bottom:1.75rem;">
  <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:0.75rem;">
    <div style="display:flex;align-items:center;gap:8px;">
      <span style="font-size:0.78rem;font-weight:500;color:var(--text);">Donor Cluster Analysis</span>
      <span style="font-size:0.68rem;background:#F5F3FF;color:#5B21B6;padding:2px 8px;border-radius:20px;border:1px solid #DDD6FE;">
        ⚡ K-Means AI
      </span>
    </div>
    <span style="font-size:0.72rem;color:var(--muted);">
      {{ $clusterResult['total_donors'] ?? 0 }} donors grouped into {{ $clusterResult['n_clusters'] ?? 0 }} clusters
    </span>
  </div>

  <div style="display:grid;grid-template-columns:repeat({{ min(count($clusterResult['clusters']), 4) }},1fr);gap:12px;">
    @foreach($clusterResult['clusters'] as $cluster)
      @php
        $c = $cluster['color'];
        $bg     = $c==='green' ? '#F0FDF4' : ($c==='blue' ? '#EFF6FF' : ($c==='red' ? '#FEF2F2' : '#FFFBEB'));
        $border = $c==='green' ? '#BBF7D0' : ($c==='blue' ? '#BFDBFE' : ($c==='red' ? '#FECACA' : '#FDE68A'));
        $color  = $c==='green' ? '#166534' : ($c==='blue' ? '#1E3A8A' : ($c==='red' ? '#991B1B' : '#92400E'));
        $dot    = $c==='green' ? '#16A34A' : ($c==='blue' ? '#1D4ED8' : ($c==='red' ? '#C8192A' : '#D97706'));
      @endphp
      <div style="background:{{ $bg }};border:1px solid {{ $border }};border-radius:14px;padding:1.25rem;">

        {{-- HEADER --}}
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:1rem;">
          <div style="width:10px;height:10px;border-radius:50%;background:{{ $dot }};flex-shrink:0;"></div>
          <span style="font-size:0.78rem;font-weight:500;color:{{ $color }};">
            {{ $cluster['label'] }}
          </span>
        </div>

        {{-- DONOR COUNT --}}
        <div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:{{ $color }};line-height:1;margin-bottom:3px;">
          {{ $cluster['donor_count'] }}
        </div>
        <div style="font-size:0.72rem;color:{{ $color }};opacity:0.7;margin-bottom:1rem;">
          donors in this cluster
        </div>

        {{-- DESCRIPTION --}}
        <div style="font-size:0.75rem;color:{{ $color }};margin-bottom:0.875rem;line-height:1.5;opacity:0.85;">
          {{ $cluster['description'] }}
        </div>

        {{-- STATS --}}
        <div style="display:flex;flex-direction:column;gap:5px;">
          <div style="display:flex;justify-content:space-between;font-size:0.72rem;">
            <span style="color:{{ $color }};opacity:0.7;">Avg Age</span>
            <span style="font-weight:500;color:{{ $color }};">{{ $cluster['avg_age'] }} yrs</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.72rem;">
            <span style="color:{{ $color }};opacity:0.7;">Avg Donations</span>
            <span style="font-weight:500;color:{{ $color }};">{{ $cluster['avg_donations'] }}</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.72rem;">
            <span style="color:{{ $color }};opacity:0.7;">Avg Hemoglobin</span>
            <span style="font-weight:500;color:{{ $color }};">{{ $cluster['avg_hemoglobin'] }} g/dL</span>
          </div>
          <div style="display:flex;justify-content:space-between;font-size:0.72rem;padding-top:5px;border-top:1px solid {{ $border }};">
            <span style="color:{{ $color }};opacity:0.7;">Eligible</span>
            <span style="font-weight:600;color:{{ $dot }};">
              {{ $cluster['eligible_count'] }} / {{ $cluster['donor_count'] }}
            </span>
          </div>
        </div>
      </div>
    @endforeach
  </div>
</div>
@endif

    <div class="stat-grid">
      <div class="stat-card">
        <div class="stat-card-top">
          <div class="stat-icon si-red">
            <svg viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
          </div>
          <span class="stat-trend trend-up">+{{ $stats['new_this_month'] }} this month</span>
        </div>
        <div class="stat-num">{{ $stats['total_donors'] }}</div>
        <div class="stat-label">Total Donors</div>
        <div class="stat-sub">{{ $stats['eligible_donors'] }} eligible · {{ $stats['not_eligible'] }} not eligible</div>
      </div>

      <div class="stat-card">
        <div class="stat-card-top">
          <div class="stat-icon si-green">
            <svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          </div>
          <span class="stat-trend trend-up">{{ $eligiblePct }}%</span>
        </div>
        <div class="stat-num">{{ $stats['eligible_donors'] }}</div>
        <div class="stat-label">Eligible Donors</div>
        <div class="stat-sub">Ready to donate now</div>
      </div>

      <div class="stat-card">
        <div class="stat-card-top">
          <div class="stat-icon si-amber">
            <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
          </div>
          <span class="stat-trend trend-down">{{ $stats['not_eligible'] }} not eligible</span>
        </div>
        <div class="stat-num">{{ $stats['new_this_month'] }}</div>
        <div class="stat-label">New This Month</div>
        <div class="stat-sub">Registered donors</div>
      </div>

      <div class="stat-card">
        <div class="stat-card-top">
          <div class="stat-icon si-blue">
            <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
          </div>
          <span class="stat-trend trend-up">95%</span>
        </div>
        <div class="stat-num">94.99<span style="font-size:1rem;font-family:'DM Sans',sans-serif;font-weight:300">%</span></div>
        <div class="stat-label">AI Accuracy</div>
        <div class="stat-sub">Logistic Regression model</div>
      </div>
    </div>

    {{-- ── CHARTS ROW ── --}}
    @php
      // Blood group counts from real donors
      $bloodGroups = ['O+','A+','B+','AB+','O-','A-','B-','AB-'];
      $bloodCounts = [];
      $maxCount    = 1;
      foreach ($bloodGroups as $bg) {
          $count = \App\Models\Donor::where('blood_group', $bg)->count();
          $bloodCounts[$bg] = $count;
          if ($count > $maxCount) $maxCount = $count;
      }
    @endphp

    <div class="grid-2">
      {{-- BLOOD GROUP BAR CHART --}}
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Blood Group Distribution</div>
            <div class="card-sub">Donor count by blood type</div>
          </div>
          <a class="card-action" href="{{ route('admin.donors.index') }}">View all →</a>
        </div>
        <div class="bar-chart">
          @foreach($bloodCounts as $bg => $count)
            @php $pct = $maxCount > 0 ? round(($count / $maxCount) * 100) : 0; @endphp
            <div class="bar-row">
              <span class="bar-label">{{ $bg }}</span>
              <div class="bar-track"><div class="bar-fill" style="width:{{ $pct }}%"></div></div>
              <span class="bar-val">{{ $count }}</span>
            </div>
          @endforeach
        </div>
      </div>

      {{-- ELIGIBILITY DONUT --}}
      <div class="card">
        <div class="card-header">
          <div>
            <div class="card-title">Eligibility Status</div>
            <div class="card-sub">AI prediction breakdown</div>
          </div>
        </div>
        @php
          $total   = $stats['total_donors'] ?: 1;
          $elPct   = round(($stats['eligible_donors'] / $total) * 239);
          $notPct  = round(($stats['not_eligible']    / $total) * 239);
        @endphp
        <div class="donut-wrap">
          <svg width="110" height="110" viewBox="0 0 110 110" style="flex-shrink:0;">
            <circle cx="55" cy="55" r="38" fill="none" stroke="#F4F1F1" stroke-width="14"/>
            <circle cx="55" cy="55" r="38" fill="none" stroke="#C8192A" stroke-width="14"
              stroke-dasharray="{{ $elPct }} 239"
              stroke-dashoffset="0" stroke-linecap="butt"
              transform="rotate(-90 55 55)"/>
            <circle cx="55" cy="55" r="38" fill="none" stroke="#FCA5A5" stroke-width="14"
              stroke-dasharray="{{ $notPct }} 239"
              stroke-dashoffset="-{{ $elPct }}" stroke-linecap="butt"
              transform="rotate(-90 55 55)"/>
            <text x="55" y="51" text-anchor="middle" font-family="Playfair Display,serif" font-size="14" font-weight="700" fill="#1A0A0B">{{ $eligiblePct }}%</text>
            <text x="55" y="62" text-anchor="middle" font-family="DM Sans,sans-serif" font-size="8" fill="#6B3B40">eligible</text>
          </svg>
          <div class="donut-legend">
            <div class="dl-row"><span class="dl-dot" style="background:#C8192A"></span><span class="dl-label">Eligible</span><span class="dl-val">{{ $stats['eligible_donors'] }}</span></div>
            <div class="dl-row"><span class="dl-dot" style="background:#FCA5A5"></span><span class="dl-label">Not eligible</span><span class="dl-val">{{ $stats['not_eligible'] }}</span></div>
            <div class="dl-row"><span class="dl-dot" style="background:#E4DEDE"></span><span class="dl-label">Total</span><span class="dl-val">{{ $stats['total_donors'] }}</span></div>
          </div>
        </div>
      </div>
    </div>

    {{-- ── RECENT DONORS TABLE + ACTIVITY ── --}}
    <div class="grid-3-1">

      {{-- RECENT DONORS --}}
      <div class="card" style="padding-bottom:0;">
        <div class="card-header">
          <div>
            <div class="card-title">Recent Donors</div>
            <div class="card-sub">Latest 5 registered donors</div>
          </div>
          <a class="card-action" href="{{ route('admin.donors.index') }}">View all →</a>
        </div>
        <div class="table-wrap">
          <table>
            <thead>
              <tr>
                <th>Donor</th>
                <th>Blood</th>
                <th>Age</th>
                <th>District</th>
                <th>Status</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse($recent_donors as $donor)
                <tr>
                  <td>
                    <div class="td-name">
                      <div class="td-avatar">
                        {{ strtoupper(substr($donor->first_name,0,1).substr($donor->last_name,0,1)) }}
                      </div>
                      <div>
                        <div class="td-name-text">{{ $donor->full_name }}</div>
                        <div class="td-name-sub">{{ $donor->email }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="blood-pill">{{ $donor->blood_group ?? '—' }}</span></td>
                  <td>{{ $donor->age ?? '—' }}</td>
                  <td>{{ $donor->district ?? '—' }}</td>
                  <td>
                    <span class="badge {{ $donor->is_eligible ? 'b-elig' : 'b-not' }}">
                      {{ $donor->is_eligible ? 'Eligible' : 'Not Eligible' }}
                    </span>
                  </td>
                  <td>
                    <a href="{{ route('admin.donors.show', $donor->id) }}"
                       style="font-size:0.75rem;color:var(--red);text-decoration:none;">
                      View →
                    </a>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="empty-state">No donors registered yet.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      {{-- ACTIVITY FEED --}}
      <div class="card">
        <div class="card-header">
          <div class="card-title">Recent Activity</div>
        </div>
        <div class="activity-list">
          @forelse($recent_donors as $donor)
            <div class="act-item">
              <div class="act-dot-wrap">
                <div class="act-dot {{ $donor->is_eligible ? 'ad-green' : 'ad-red' }}"></div>
                <div class="act-line"></div>
              </div>
              <div>
                <div class="act-text">
                  <strong>{{ $donor->first_name }}</strong> registered
                  {{ $donor->is_eligible ? '— Eligible ✓' : '— Not eligible' }}
                </div>
                <div class="act-time">{{ $donor->created_at->diffForHumans() }}</div>
              </div>
            </div>
          @empty
            <div class="empty-state">No recent activity.</div>
          @endforelse
        </div>

        {{-- QUICK STATS --}}
        <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);">
          <div style="font-size:0.78rem;font-weight:500;color:var(--text);margin-bottom:0.75rem;">Quick Stats</div>
          <div style="display:flex;flex-direction:column;gap:8px;">
            <div style="display:flex;justify-content:space-between;font-size:0.775rem;">
              <span style="color:var(--muted);">O+ donors</span>
              <span style="font-weight:500;">{{ $bloodCounts['O+'] ?? 0 }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.775rem;">
              <span style="color:var(--muted);">A+ donors</span>
              <span style="font-weight:500;">{{ $bloodCounts['A+'] ?? 0 }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.775rem;">
              <span style="color:var(--muted);">AI eligible rate</span>
              <span style="font-weight:500;color:var(--green);">{{ $eligiblePct }}%</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.775rem;">
              <span style="color:var(--muted);">New this month</span>
              <span style="font-weight:500;color:var(--blue);">{{ $stats['new_this_month'] }}</span>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

{{-- Donor dashboard, Admin dashboard, Hospital dashboard --}}
@include('components.chatbot')