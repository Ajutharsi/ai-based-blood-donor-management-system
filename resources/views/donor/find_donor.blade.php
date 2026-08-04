<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find Donors — LifeLink</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    :root{
      --red:#C8192A;--red-dark:#8B0F1C;--red-light:#F9E8EA;--red-mid:#E8C0C4;
      --white:#fff;--off:#FDF7F7;--text:#1A0A0B;--muted:#7A5C60;--gray-b:#E8DFE0;--gray-l:#F4EFEF;
      --green:#15803D;--green-l:#DCFCE7;
    }
    html{scroll-behavior:smooth;}
    body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--text);overflow-x:hidden;}

    /* NAV */
    nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 5%;height:70px;display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid var(--gray-b);}
    .nav-logo{display:flex;align-items:center;gap:0.6rem;text-decoration:none;}
    .logo-dot{width:34px;height:34px;background:var(--red);border-radius:9px;display:flex;align-items:center;justify-content:center;}
    .logo-dot svg{width:17px;height:17px;fill:white;}
    .logo-text{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);}
    .nav-links{display:flex;align-items:center;gap:2rem;}
    .nav-links a{font-size:0.85rem;color:var(--muted);text-decoration:none;transition:color 0.2s;}
    .nav-links a:hover,.nav-links a.active{color:var(--red);}
    .nav-btns{display:flex;gap:0.75rem;align-items:center;}
    .btn-ghost{padding:0.45rem 1.1rem;border:1.5px solid var(--gray-b);border-radius:8px;font-size:0.83rem;color:var(--text);text-decoration:none;transition:all 0.2s;}
    .btn-ghost:hover{border-color:var(--red);color:var(--red);}
    .btn-primary{padding:0.45rem 1.25rem;background:var(--red);border-radius:8px;font-size:0.83rem;color:white;text-decoration:none;transition:background 0.2s;}
    .btn-primary:hover{background:var(--red-dark);}

    /* HERO */
    .hero{padding:130px 5% 0;background:linear-gradient(160deg,#1A0A0B 0%,var(--red-dark) 55%,#3D0A11 100%);position:relative;overflow:hidden;}
    .hero::before{content:'';position:absolute;top:-60px;right:-60px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,0.03);}
    .hero-content{max-width:640px;margin:0 auto;text-align:center;position:relative;padding-bottom:2rem;}
    .hero-tag{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.35rem 0.9rem;font-size:0.75rem;color:rgba(255,255,255,0.8);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:1.5rem;}
    .hero-tag span{width:6px;height:6px;background:#FF6B7A;border-radius:50%;display:inline-block;}
    .hero h1{font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.2rem);font-weight:900;color:white;line-height:1.15;margin-bottom:1rem;}
    .hero h1 em{font-style:italic;color:rgba(255,180,180,0.9);}
    .hero p{font-size:0.95rem;color:rgba(255,255,255,0.65);line-height:1.7;margin-bottom:2rem;}

    /* SEARCH CARD — floats over the hero/content boundary */
    .search-card{background:white;border-radius:16px;padding:2rem;box-shadow:0 20px 60px rgba(0,0,0,0.15);margin:0 5%;position:relative;z-index:10;top:-1px;border:1px solid var(--gray-b);}
    .search-inner{max-width:900px;margin:0 auto;}
    .search-form{display:flex;gap:1rem;align-items:flex-end;flex-wrap:wrap;}
    .sf-group{display:flex;flex-direction:column;gap:0.4rem;flex:1;min-width:160px;}
    .sf-group label{font-size:0.7rem;font-weight:700;color:var(--muted);text-transform:uppercase;letter-spacing:0.08em;}
    .sf-group select,.sf-group input{padding:0.7rem 1rem;border:1.5px solid var(--gray-b);border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.88rem;color:var(--text);background:var(--off);outline:none;transition:all 0.2s;}
    .sf-group select:focus,.sf-group input:focus{border-color:var(--red);background:white;}
    .btn-search{padding:0.7rem 2rem;background:var(--red);color:white;border:none;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all 0.2s;white-space:nowrap;display:flex;align-items:center;gap:0.5rem;}
    .btn-search:hover{background:var(--red-dark);transform:translateY(-1px);}
    .btn-search svg{width:16px;height:16px;}

    /* BLOOD GROUP PILLS (quick filter) */
    .blood-quick{max-width:900px;margin:1.5rem auto 0;display:flex;align-items:center;gap:0.6rem;flex-wrap:wrap;}
    .bq-label{font-size:0.72rem;font-weight:600;color:var(--muted);}
    .bq-pill{padding:0.3rem 0.8rem;border-radius:20px;font-size:0.78rem;font-weight:700;border:1.5px solid var(--gray-b);cursor:pointer;transition:all 0.2s;background:white;color:var(--text);text-decoration:none;}
    .bq-pill:hover,.bq-pill.active{background:var(--red-light);border-color:var(--red-mid);color:var(--red);}

    /* RESULTS SECTION */
    .results-section{padding:50px 5% 80px;}
    .results-inner{max-width:1100px;margin:0 auto;}
    .results-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;}
    .results-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;}
    .results-count{font-size:0.82rem;color:var(--muted);}

    /* DONOR CARDS */
    .donors-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;}
    .donor-card{background:white;border-radius:14px;border:1px solid var(--gray-b);overflow:hidden;transition:all 0.3s;}
    .donor-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(200,25,42,0.1);border-color:var(--red-mid);}
    .dc-top{padding:1.5rem;display:flex;align-items:center;gap:1rem;border-bottom:1px solid var(--gray-l);}
    .dc-av{width:48px;height:48px;border-radius:12px;background:var(--red-light);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--red-dark);flex-shrink:0;}
    .dc-name{font-weight:600;font-size:0.92rem;}
    .dc-loc{font-size:0.75rem;color:var(--muted);display:flex;align-items:center;gap:0.3rem;margin-top:0.2rem;}
    .dc-loc svg{width:11px;height:11px;stroke:var(--muted);}
    .blood-pill{display:inline-flex;padding:0.2rem 0.6rem;border-radius:6px;font-size:0.72rem;font-weight:700;background:var(--red-light);color:var(--red-dark);border:1px solid var(--red-mid);}
    .dc-body{padding:1.25rem;}
    .dc-metrics{display:grid;grid-template-columns:1fr 1fr 1fr;gap:0.75rem;margin-bottom:1rem;}
    .dc-metric label{font-size:0.62rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.06em;display:block;margin-bottom:0.2rem;}
    .dc-metric .val{font-size:0.88rem;font-weight:600;}
    .dc-ai{background:var(--green-l);border-radius:8px;padding:0.6rem 0.8rem;display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;}
    .dc-ai-label{font-size:0.72rem;color:var(--green);font-weight:600;}
    .dc-ai-val{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--green);}
    .badge-eligible{display:inline-flex;align-items:center;gap:0.3rem;padding:0.2rem 0.65rem;border-radius:20px;font-size:0.7rem;font-weight:600;background:var(--green-l);color:var(--green);}
    .dc-footer{display:flex;align-items:center;justify-content:space-between;}
    .dc-donations{font-size:0.75rem;color:var(--muted);}
    .btn-contact-donor{padding:0.4rem 1rem;background:var(--red-light);color:var(--red);border:1px solid var(--red-mid);border-radius:7px;font-family:'DM Sans',sans-serif;font-size:0.78rem;font-weight:600;cursor:pointer;transition:all 0.2s;}
    .btn-contact-donor:hover{background:var(--red);color:white;}

    /* EMPTY STATE */
    .empty{text-align:center;padding:5rem 2rem;}
    .empty-icon{width:64px;height:64px;background:var(--red-light);border-radius:16px;display:flex;align-items:center;justify-content:center;margin:0 auto 1.25rem;}
    .empty-icon svg{width:28px;height:28px;stroke:var(--red);}
    .empty-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;margin-bottom:0.5rem;}
    .empty-sub{font-size:0.88rem;color:var(--muted);max-width:380px;margin:0 auto;}

    /* PRIVACY NOTICE */
    .privacy-notice{background:var(--off);border:1px solid var(--gray-b);border-radius:10px;padding:1rem 1.25rem;margin-bottom:2rem;font-size:0.78rem;color:var(--muted);display:flex;align-items:flex-start;gap:0.6rem;}
    .privacy-notice svg{width:15px;height:15px;stroke:var(--muted);flex-shrink:0;margin-top:0.1rem;}

    /* PAGINATION */
    .pagination-row{display:flex;justify-content:center;gap:0.4rem;margin-top:2.5rem;}
    .pagination-row a,.pagination-row span{padding:0.45rem 0.85rem;border-radius:8px;font-size:0.82rem;text-decoration:none;border:1px solid var(--gray-b);color:var(--muted);background:white;transition:all 0.2s;}
    .pagination-row a:hover{border-color:var(--red);color:var(--red);}
    .pagination-row span.active{background:var(--red);color:white;border-color:var(--red);}

    /* CTA BANNER */
    .cta-banner{background:linear-gradient(135deg,#1A0A0B,var(--red-dark));border-radius:16px;padding:3rem;text-align:center;margin-top:3rem;}
    .cta-banner h3{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:white;margin-bottom:0.75rem;}
    .cta-banner p{font-size:0.9rem;color:rgba(255,255,255,0.65);margin-bottom:1.5rem;}
    .cta-banner a{padding:0.75rem 2rem;background:white;color:var(--red);border-radius:10px;font-weight:600;font-size:0.9rem;text-decoration:none;display:inline-block;transition:all 0.2s;}
    .cta-banner a:hover{background:var(--red-light);}

    /* FOOTER */
    footer{background:#1A0A0B;padding:3rem 5% 2rem;}
    .footer-inner{max-width:1100px;margin:0 auto;}
    .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:2.5rem;}
    .footer-brand p{font-size:0.82rem;color:rgba(255,255,255,0.45);line-height:1.7;margin-top:0.75rem;}
    .footer-col h4{font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;}
    .footer-col a{display:block;font-size:0.82rem;color:rgba(255,255,255,0.55);text-decoration:none;margin-bottom:0.5rem;transition:color 0.2s;}
    .footer-col a:hover{color:white;}
    .footer-bottom{border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem;display:flex;justify-content:space-between;}
    .footer-copy{font-size:0.75rem;color:rgba(255,255,255,0.3);}

    @media(max-width:768px){.donors-grid,.footer-grid{grid-template-columns:1fr;}.search-form{flex-direction:column;}.nav-links{display:none;}}
  </style>
</head>
<body>

<!-- NAV -->
<nav>
  <a class="nav-logo" href="/">
    <div class="logo-dot"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
    <span class="logo-text">LifeLink</span>
  </a>
  <div class="nav-links">
    <a href="/">Home</a>
    <a href="/about">About</a>
    <a href="/find-donors" class="active">Find Donors</a>
    <a href="/contact">Contact</a>
    <a href="/privacy">Privacy</a>
  </div>
  <div class="nav-btns">
    <a href="{{ route('donor.login') }}" class="btn-ghost">Sign In</a>
    <a href="{{ route('donor.register') }}" class="btn-primary">Register as Donor</a>
  </div>
</nav>

<!-- HERO -->
<div class="hero">
  <div class="hero-content">
    <div class="hero-tag"><span></span> AI-Matched Donors</div>
    <h1>Find an <em>eligible</em> donor near you</h1>
    <p>Search our database of verified, AI-screened donors by blood group and district. All donors shown are currently eligible based on our
      @if(!empty($modelMetrics['accuracy']))
        {{ number_format($modelMetrics['accuracy'], 2) }}% accurate {{ $modelMetrics['model'] }}
      @else
        AI eligibility
      @endif
      model.</p>
  </div>
</div>

<!-- SEARCH CARD -->
<div class="search-card">
  <div class="search-inner">
    <form method="GET" action="/find-donors" class="search-form">
      <div class="sf-group">
        <label>Blood Group</label>
        <select name="blood_group">
          <option value="">Any Blood Group</option>
          @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
            <option value="{{ $bg }}" {{ request('blood_group')===$bg?'selected':'' }}>{{ $bg }}</option>
          @endforeach
        </select>
      </div>
      <div class="sf-group">
        <label>District</label>
        <select name="district">
          <option value="">Any District</option>
          @foreach(['Colombo','Gampaha','Kandy','Galle','Matara','Kurunegala','Jaffna','Batticaloa','Anuradhapura','Ratnapura','Badulla','Trincomalee','Kegalle','Kalutara','Matale','Nuwara Eliya','Polonnaruwa','Ampara','Hambantota','Monaragala','Puttalam','Mannar','Vavuniya','Mullaitivu','Kilinochchi'] as $d)
            <option value="{{ $d }}" {{ request('district')===$d?'selected':'' }}>{{ $d }}</option>
          @endforeach
        </select>
      </div>
      <div class="sf-group" style="max-width:180px;">
        <label>Min. Donations</label>
        <select name="min_donations">
          <option value="">Any</option>
          <option value="1"  {{ request('min_donations')==='1' ?'selected':'' }}>1+ donations</option>
          <option value="3"  {{ request('min_donations')==='3' ?'selected':'' }}>3+ donations</option>
          <option value="5"  {{ request('min_donations')==='5' ?'selected':'' }}>5+ donations</option>
          <option value="10" {{ request('min_donations')==='10'?'selected':'' }}>10+ donations</option>
        </select>
      </div>
      <button type="submit" class="btn-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        Search
      </button>
    </form>

    <!-- Quick blood group filter -->
    <div class="blood-quick">
      <span class="bq-label">Quick filter:</span>
      @foreach(['A+','A-','B+','B-','O+','O-','AB+','AB-'] as $bg)
        <a href="/find-donors?blood_group={{ urlencode($bg) }}&district={{ request('district') }}"
           class="bq-pill {{ request('blood_group')===$bg?'active':'' }}">{{ $bg }}</a>
      @endforeach
      @if(request('blood_group'))
        <a href="/find-donors" class="bq-pill" style="color:var(--muted);">✕ Clear</a>
      @endif
    </div>
  </div>
</div>

<!-- RESULTS -->
<div class="results-section">
  <div class="results-inner">

    <div class="privacy-notice">
      <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      <span>Donor contact details are protected. Only first name, blood group, district, and eligibility status are shown publicly. Full contact requires hospital-level access. <a href="/privacy" style="color:var(--red);">Privacy Policy</a></span>
    </div>

    @if(isset($donors) && $donors->count() > 0)

      <div class="results-header">
        <div class="results-title">
          {{ $donors->total() }} eligible donor{{ $donors->total() !== 1 ? 's' : '' }} found
          @if(request('blood_group')) for <span style="color:var(--red);">{{ request('blood_group') }}</span>@endif
          @if(request('district')) in <span style="color:var(--red);">{{ request('district') }}</span>@endif
        </div>
        <div class="results-count">Sorted by AI confidence score</div>
      </div>

      <div class="donors-grid">
        @foreach($donors as $donor)
        <div class="donor-card">
          <div class="dc-top">
            <div class="dc-av">{{ strtoupper(substr($donor->first_name,0,1)) }}{{ strtoupper(substr($donor->last_name,0,1)) }}</div>
            <div style="flex:1;">
              <div style="display:flex;align-items:center;justify-content:space-between;">
                <div class="dc-name">{{ $donor->first_name }} {{ strtoupper(substr($donor->last_name,0,1)) }}.</div>
                <span class="blood-pill">{{ $donor->blood_group ?? '—' }}</span>
              </div>
              <div class="dc-loc">
                <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg>
                {{ $donor->city ?? '' }}{{ $donor->city && $donor->district ? ', ' : '' }}{{ $donor->district ?? 'Sri Lanka' }}
              </div>
            </div>
          </div>
          <div class="dc-body">
            <div class="dc-metrics">
              <div class="dc-metric">
                <label>Age</label>
                <div class="val">{{ $donor->age ?? '—' }}<span style="font-size:0.7rem;color:var(--muted);"> yrs</span></div>
              </div>
              <div class="dc-metric">
                <label>Weight</label>
                <div class="val">{{ $donor->weight_kg ?? '—' }}<span style="font-size:0.7rem;color:var(--muted);"> kg</span></div>
              </div>
              <div class="dc-metric">
                <label>Donations</label>
                <div class="val">{{ $donor->total_donations ?? 0 }}<span style="font-size:0.7rem;color:var(--muted);">×</span></div>
              </div>
            </div>
            @if($donor->ai_confidence > 0)
            <div class="dc-ai">
              <span class="dc-ai-label">AI Confidence</span>
              <span class="dc-ai-val">{{ number_format($donor->ai_confidence,1) }}%</span>
            </div>
            @endif
            <div class="dc-footer">
              <span class="badge-eligible">
                <svg viewBox="0 0 8 8" style="width:8px;height:8px;fill:#15803D;"><circle cx="4" cy="4" r="4"/></svg>
                Eligible Now
              </span>
              <button class="btn-contact-donor" onclick="alert('Hospital login required to contact donors. Please sign in as a hospital.')">Contact</button>
            </div>
          </div>
        </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="pagination-row">
        {{ $donors->appends(request()->query())->links('pagination::simple-bootstrap-4') }}
      </div>

    @elseif(request()->hasAny(['blood_group','district','min_donations']))

      <div class="empty">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        </div>
        <div class="empty-title">No eligible donors found</div>
        <div class="empty-sub">Try a different blood group or district. You can also broaden your search by removing some filters.</div>
      </div>

    @else

      <div class="empty">
        <div class="empty-icon">
          <svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
        </div>
        <div class="empty-title">Search for a donor</div>
        <div class="empty-sub">Select a blood group or district above to find eligible, AI-verified donors in your area.</div>
      </div>

    @endif

    <!-- CTA -->
    <div class="cta-banner">
      <h3>Are you a blood donor?</h3>
      <p>Register on LifeLink and help hospitals find you when they need you most. It takes less than 5 minutes.</p>
      <a href="{{ route('donor.register') }}">Register as a Donor →</a>
    </div>

  </div>
</div>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.5rem;">
          <div class="logo-dot"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
          <span style="font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:white;">LifeLink</span>
        </div>
        <p>Sri Lanka's AI-powered blood donor management system.</p>
      </div>
      <div class="footer-col"><h4>Platform</h4><a href="/">Home</a><a href="/about">About</a><a href="/find-donors">Find Donors</a><a href="/contact">Contact</a></div>
      <div class="footer-col"><h4>Donors</h4><a href="{{ route('donor.register') }}">Register</a><a href="{{ route('donor.login') }}">Sign In</a></div>
      <div class="footer-col"><h4>Legal</h4><a href="/privacy">Privacy Policy</a><a href="/terms">Terms of Service</a></div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2025 LifeLink. National Blood Transfusion Service of Sri Lanka.</div>
      <div class="footer-copy">A Final Year Research Project</div>
    </div>
  </div>
</footer>

</body>
</html>