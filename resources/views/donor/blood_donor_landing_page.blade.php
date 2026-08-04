
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap');

  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;
    --primary-dark:#1E3A8A;
    --primary-light:#EFF6FF;
    --primary-mid:#60A5FA;
    --white:#FFFFFF;
    --off:#F8FAFC;
    --text:#1E293B;
    --muted:#64748B;
    --border:rgba(29,78,216,0.15);
  }
  body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--text);overflow-x:hidden;}

  /* NAV */
  nav{
    display:flex;align-items:center;justify-content:space-between;
    padding:1.2rem 3rem;border-bottom:1px solid var(--border);
    position:sticky;top:0;background:rgba(255,255,255,0.96);
    backdrop-filter:blur(8px);z-index:100;
  }
  .logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:var(--primary-dark);}
  .logo-icon{width:34px;height:34px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;}
  .logo-icon svg{width:18px;height:18px;fill:white;}
  .nav-links{display:flex;align-items:center;gap:2rem;}
  .nav-links a{text-decoration:none;color:var(--muted);font-size:0.875rem;font-weight:400;letter-spacing:0.02em;transition:color 0.2s;}
  .nav-links a:hover{color:var(--primary);}
  .nav-btns{display:flex;gap:10px;align-items:center;}
  .btn-ghost{padding:0.5rem 1.25rem;border:1px solid var(--border);border-radius:6px;background:transparent;color:var(--primary-dark);font-family:'DM Sans',sans-serif;font-size:0.875rem;cursor:pointer;transition:all 0.2s;}
  .btn-ghost:hover{background:var(--primary-light);}
  .btn-primary{padding:0.5rem 1.25rem;border:none;border-radius:6px;background:var(--primary);color:white;font-family:'DM Sans',sans-serif;font-size:0.875rem;font-weight:500;cursor:pointer;transition:all 0.2s;}
  .btn-primary:hover{background:var(--primary-dark);}

  /* HERO */
  .hero{
    display:grid;grid-template-columns:1fr 1fr;gap:0;
    min-height:88vh;
  }
  .hero-left{
    padding:5rem 3rem 4rem 3rem;
    display:flex;flex-direction:column;justify-content:center;
    background:var(--white);
  }
  .hero-badge{
    display:inline-flex;align-items:center;gap:8px;
    background:var(--primary-light);border:1px solid var(--border);
    border-radius:20px;padding:0.35rem 1rem;
    font-size:0.75rem;font-weight:500;color:var(--primary-dark);
    letter-spacing:0.08em;text-transform:uppercase;
    width:fit-content;margin-bottom:1.75rem;
  }
  .badge-dot{width:6px;height:6px;border-radius:50%;background:var(--primary);animation:pulse 2s infinite;}
  @keyframes pulse{0%,100%{opacity:1;}50%{opacity:0.4;}}
  .hero h1{
    font-family:'Playfair Display',serif;
    font-size:3.5rem;font-weight:900;line-height:1.1;
    color:var(--text);margin-bottom:1.5rem;
  }
  .hero h1 span{color:var(--primary);}
  .hero p{
    font-size:1.05rem;font-weight:300;color:var(--muted);
    line-height:1.75;max-width:440px;margin-bottom:2.5rem;
  }
  .hero-actions{display:flex;gap:12px;align-items:center;}
  .btn-hero{
    padding:0.85rem 2rem;border-radius:8px;
    font-family:'DM Sans',sans-serif;font-size:0.95rem;
    font-weight:500;cursor:pointer;transition:all 0.2s;
    text-decoration:none;display:inline-block;
  }
  .btn-hero-primary{background:var(--primary);color:white;border:none;}
  .btn-hero-primary:hover{background:var(--primary-dark);transform:translateY(-1px);}
  .btn-hero-outline{background:transparent;color:var(--primary-dark);border:1px solid var(--border);}
  .btn-hero-outline:hover{background:var(--primary-light);}
  .hero-right{
    background:var(--primary-dark);
    position:relative;overflow:hidden;
    display:flex;align-items:center;justify-content:center;
    padding:3rem;
  }
  .hero-bg-circles{position:absolute;inset:0;pointer-events:none;}
  .hc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.08);}
  .hc1{width:500px;height:500px;top:-80px;right:-80px;}
  .hc2{width:350px;height:350px;bottom:-50px;left:-50px;}
  .hc3{width:200px;height:200px;top:50%;left:50%;transform:translate(-50%,-50%);}

  /* HERO CARD */
  .hero-card{
    position:relative;z-index:2;
    background:rgba(255,255,255,0.06);
    border:1px solid rgba(255,255,255,0.12);
    border-radius:20px;padding:2rem;width:100%;max-width:340px;
    backdrop-filter:blur(4px);
  }
  .card-title{color:rgba(255,255,255,0.6);font-size:0.75rem;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:1.25rem;}
  .donor-item{
    display:flex;align-items:center;gap:12px;
    padding:0.75rem;border-radius:10px;
    background:rgba(255,255,255,0.05);margin-bottom:8px;
  }
  .donor-avatar{
    width:36px;height:36px;border-radius:50%;
    display:flex;align-items:center;justify-content:center;
    font-weight:500;font-size:0.8rem;flex-shrink:0;
  }
  .av1{background:rgba(255,255,255,0.15);color:white;}
  .av2{background:rgba(29,78,216,0.4);color:#BFDBFE;}
  .av3{background:rgba(255,255,255,0.1);color:white;}
  .donor-info{flex:1;}
  .donor-name{font-size:0.875rem;font-weight:500;color:white;margin-bottom:2px;}
  .donor-meta{font-size:0.75rem;color:rgba(255,255,255,0.5);}
  .donor-badge{
    font-size:0.7rem;font-weight:500;padding:3px 8px;border-radius:4px;
  }
  .badge-eli{background:rgba(74,222,128,0.15);color:#86EFAC;}
  .badge-not{background:rgba(255,255,255,0.08);color:rgba(255,255,255,0.4);}
  .ai-label{
    margin-top:1.25rem;padding:0.75rem;border-radius:8px;
    background:rgba(29,78,216,0.25);border:1px solid rgba(29,78,216,0.3);
    display:flex;align-items:center;gap:10px;
  }
  .ai-icon{width:28px;height:28px;border-radius:6px;background:var(--primary);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .ai-text{font-size:0.75rem;color:rgba(255,255,255,0.85);}
  .ai-text strong{color:white;display:block;font-size:0.8rem;margin-bottom:1px;}

  /* STATS */
  .stats{
    display:grid;grid-template-columns:repeat(4,1fr);
    border-top:1px solid var(--border);border-bottom:1px solid var(--border);
  }
  .stat-item{
    padding:2.5rem 2rem;text-align:center;
    border-right:1px solid var(--border);
  }
  .stat-item:last-child{border-right:none;}
  .stat-num{
    font-family:'Playfair Display',serif;
    font-size:2.75rem;font-weight:700;color:var(--primary);
    line-height:1;margin-bottom:0.5rem;
  }
  .stat-label{font-size:0.8rem;font-weight:400;color:var(--muted);letter-spacing:0.04em;}

  /* HOW IT WORKS */
  .section{padding:5rem 3rem;}
  .section-label{
    font-size:0.75rem;font-weight:500;letter-spacing:0.12em;
    text-transform:uppercase;color:var(--primary);margin-bottom:0.75rem;
  }
  .section-title{
    font-family:'Playfair Display',serif;
    font-size:2.25rem;font-weight:700;color:var(--text);
    margin-bottom:1rem;line-height:1.2;
  }
  .section-sub{font-size:0.95rem;color:var(--muted);font-weight:300;max-width:480px;line-height:1.7;}

  .steps{display:grid;grid-template-columns:repeat(4,1fr);gap:0;margin-top:3.5rem;position:relative;}
  .step{padding:0 1.5rem 0 0;position:relative;}
  .step-num{
    width:44px;height:44px;border-radius:50%;
    border:1px solid var(--border);background:var(--white);
    display:flex;align-items:center;justify-content:center;
    font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:var(--primary);
    margin-bottom:1.25rem;position:relative;z-index:2;
  }
  .step-line{
    position:absolute;top:22px;left:44px;right:1.5rem;
    height:1px;background:var(--border);z-index:1;
  }
  .step:last-child .step-line{display:none;}
  .step-title{font-size:0.95rem;font-weight:500;color:var(--text);margin-bottom:0.5rem;}
  .step-desc{font-size:0.825rem;color:var(--muted);line-height:1.65;font-weight:300;}

  /* FEATURES */
  .features-section{background:var(--off);padding:5rem 3rem;}
  .features-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3.5rem;}
  .feature-card{
    background:var(--white);border:1px solid var(--border);
    border-radius:16px;padding:1.75rem;
    transition:all 0.25s;
  }
  .feature-card:hover{border-color:rgba(29,78,216,0.3);transform:translateY(-2px);}
  .feature-icon{
    width:44px;height:44px;border-radius:10px;
    background:var(--primary-light);display:flex;align-items:center;justify-content:center;
    margin-bottom:1.25rem;
  }
  .feature-icon svg{width:22px;height:22px;stroke:var(--primary);fill:none;stroke-width:1.75;stroke-linecap:round;stroke-linejoin:round;}
  .feature-title{font-size:1rem;font-weight:500;color:var(--text);margin-bottom:0.5rem;}
  .feature-desc{font-size:0.825rem;color:var(--muted);line-height:1.7;font-weight:300;}
  .feature-tag{
    display:inline-block;margin-top:1rem;
    font-size:0.7rem;font-weight:500;letter-spacing:0.06em;
    text-transform:uppercase;color:var(--primary);
    background:var(--primary-light);padding:3px 10px;border-radius:4px;
  }

  /* CTA */
  .cta-section{
    background:var(--primary-dark);padding:5rem 3rem;
    text-align:center;position:relative;overflow:hidden;
  }
  .cta-bg{position:absolute;inset:0;pointer-events:none;}
  .cta-circle{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.06);}
  .cc1{width:600px;height:600px;top:-200px;left:-100px;}
  .cc2{width:500px;height:500px;bottom:-200px;right:-100px;}
  .cta-section h2{
    font-family:'Playfair Display',serif;
    font-size:2.5rem;font-weight:700;color:white;
    margin-bottom:1rem;position:relative;z-index:2;
  }
  .cta-section p{
    font-size:1rem;color:rgba(255,255,255,0.65);
    font-weight:300;max-width:460px;margin:0 auto 2.25rem;
    line-height:1.7;position:relative;z-index:2;
  }
  .cta-btns{display:flex;gap:12px;justify-content:center;position:relative;z-index:2;}
  .btn-cta-white{padding:0.85rem 2rem;border-radius:8px;background:white;color:var(--primary-dark);border:none;font-family:'DM Sans',sans-serif;font-size:0.95rem;font-weight:500;cursor:pointer;transition:all 0.2s;}
  .btn-cta-white:hover{background:var(--primary-light);}
  .btn-cta-outline{padding:0.85rem 2rem;border-radius:8px;background:transparent;color:white;border:1px solid rgba(255,255,255,0.25);font-family:'DM Sans',sans-serif;font-size:0.95rem;font-weight:400;cursor:pointer;transition:all 0.2s;}
  .btn-cta-outline:hover{border-color:rgba(255,255,255,0.5);}

  /* FOOTER */
  footer{padding:2rem 3rem;border-top:1px solid var(--border);display:flex;align-items:center;justify-content:space-between;}
  .footer-copy{font-size:0.8rem;color:var(--muted);}
  .footer-links{display:flex;gap:1.5rem;}
  .footer-links a{font-size:0.8rem;color:var(--muted);text-decoration:none;}
  .footer-links a:hover{color:var(--primary);}
</style>



<!-- NAV -->
<nav>
  <div class="logo">
    <div class="logo-icon">
      <svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
    </div>
    LifeLink
  </div>
  <div class="nav-links">
    <a href="{{ route('home') }}">Home</a>
    <a href="{{route('find-donors')}}">Find Donors</a>
    <a href="{{ route('hospital.login') }}">Hospitals</a>
     <a href="{{ route('about') }}">About</a>
      <a href="{{ route('contact') }}">Contact</a>
  </div>
  <div class="nav-btns">
    <a href="{{ route('donor.login') }}" class="btn-ghost">Sign In</a>
    <a href="{{ route('donor.register') }}" class="btn-primary">Register as Donor</a>
</div>
</nav>

<!-- HERO -->
<section class="hero">
  <div class="hero-left">
    <div class="hero-badge">
      <span class="badge-dot"></span>
      AI-Powered Donor Matching
    </div>
    <h1>Saving Lives with <span>Smarter</span> Blood Donation</h1>
    <p>An intelligent platform that connects hospitals with eligible blood donors instantly — powered by machine learning that predicts donor suitability with
      @if(!empty($modelMetrics['accuracy']))
        {{ number_format($modelMetrics['accuracy'], 2) }}%
      @else
        high
      @endif
      accuracy.</p>
    <div class="hero-actions">
    
<a class="btn-hero btn-hero-primary" href="{{ route('donor.register')}}">Donate Blood Today</a>
<a class="btn-hero btn-hero-outline" href="{{ route('donor.login')}}">Sign In →</a>

    </div>
  </div>
  <div class="hero-right">
    <div class="hero-bg-circles">
      <div class="hc hc1"></div>
      <div class="hc hc2"></div>
      <div class="hc hc3"></div>
    </div>
    <div class="hero-card">
      <div class="card-title">AI Donor Eligibility — Live</div>
      <div class="donor-item">
        <div class="donor-avatar av1">KP</div>
        <div class="donor-info">
          <div class="donor-name">Kasun Perera</div>
          <div class="donor-meta">O+ · Age 28 · Colombo</div>
        </div>
        <span class="donor-badge badge-eli">Eligible</span>
      </div>
      <div class="donor-item">
        <div class="donor-avatar av2">ND</div>
        <div class="donor-info">
          <div class="donor-name">Nimal De Silva</div>
          <div class="donor-meta">A+ · Age 35 · Gampaha</div>
        </div>
        <span class="donor-badge badge-eli">Eligible</span>
      </div>
      <div class="donor-item">
        <div class="donor-avatar av3">SR</div>
        <div class="donor-info">
          <div class="donor-name">Saman Rajapaksa</div>
          <div class="donor-meta">B+ · Age 22 · Kandy</div>
        </div>
        <span class="donor-badge badge-not">Cooldown</span>
      </div>
      <div class="ai-label">
        <div class="ai-icon">
          <svg width="16" height="16" fill="none" stroke="white" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>
        </div>
        <div class="ai-text">
          <strong>
            {{ $modelMetrics['model'] ?? 'AI model' }}
            @if(!empty($modelMetrics['accuracy']))
              · {{ number_format($modelMetrics['accuracy'], 2) }}% accuracy
            @endif
          </strong>
          Prediction based on age, weight & hemoglobin
        </div>
      </div>
    </div>
  </div>
</section>

<!-- STATS -->
<div class="stats">
  <div class="stat-item">
    <div class="stat-num">{{ number_format($donorCount ?? 0) }}</div>
    <div class="stat-label">Registered Donors</div>
  </div>
  <div class="stat-item">
    <div class="stat-num">{{ !empty($modelMetrics['accuracy']) ? number_format($modelMetrics['accuracy'], 1) . '%' : '—' }}</div>
    <div class="stat-label">AI Prediction Accuracy</div>
  </div>
  <div class="stat-item">
    <div class="stat-num">{{ $hospitalCount ?? 0 }}</div>
    <div class="stat-label">Partner Hospitals</div>
  </div>
  <div class="stat-item">
    <div class="stat-num">8</div>
    <div class="stat-label">Blood Groups Covered</div>
  </div>
</div>

<!-- HOW IT WORKS -->
<section class="section">
  <div class="section-label">How it works</div>
  <div class="section-title">From registration to donation<br>in four simple steps</div>
  <div class="section-sub">Our AI handles the complex matching so donors and hospitals can focus on what matters — saving lives.</div>
  <div class="steps">
    <div class="step">
      <div class="step-num">1</div>
      <div class="step-line"></div>
      <div class="step-title">Donor registers</div>
      <div class="step-desc">Fill in your age, blood group, weight, and health details. Takes under 2 minutes.</div>
    </div>
    <div class="step">
      <div class="step-num">2</div>
      <div class="step-line"></div>
      <div class="step-title">AI assesses eligibility</div>
      <div class="step-desc">Our {{ $modelMetrics['model'] ?? 'AI' }} model instantly predicts if you're eligible to donate.</div>
    </div>
    <div class="step">
      <div class="step-num">3</div>
      <div class="step-line"></div>
      <div class="step-title">Hospital requests blood</div>
      <div class="step-desc">A hospital enters the required blood group and the system finds matches automatically.</div>
    </div>
    <div class="step">
      <div class="step-num">4</div>
      <div class="step-line"></div>
      <div class="step-title">Donor is notified</div>
      <div class="step-desc">Eligible donors receive an instant SMS or email alert with donation details.</div>
    </div>
  </div>
</section>

<!-- FEATURES -->
<section class="features-section">
  <div class="section-label">Platform features</div>
  <div class="section-title">Everything built for<br>modern healthcare</div>
  <div class="features-grid">
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h11"/></svg>
      </div>
      <div class="feature-title">AI Eligibility Prediction</div>
      <div class="feature-desc">Machine learning models trained on real donor data predict eligibility based on hemoglobin, weight, and age
        @if(!empty($modelMetrics['accuracy']))
          with {{ number_format($modelMetrics['accuracy'], 1) }}% accuracy
        @endif
      .</div>
      <span class="feature-tag">{{ $modelMetrics['model'] ?? 'Logistic Regression / k-NN' }}</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      </div>
      <div class="feature-title">Smart Donor Matching</div>
      <div class="feature-desc">Hospitals get an instant ranked list of the most suitable nearby donors matching the required blood group.</div>
      <span class="feature-tag">Real-time Matching</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><path d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 01-3.46 0"/></svg>
      </div>
      <div class="feature-title">Instant Notifications</div>
      <div class="feature-desc">Eligible donors are alerted automatically via SMS and email the moment a matching blood request is raised.</div>
      <span class="feature-tag">SMS + Email</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
      </div>
      <div class="feature-title">Admin Dashboard</div>
      <div class="feature-desc">Full oversight of donors, hospitals, blood requests, and AI predictions from a single control panel.</div>
      <span class="feature-tag">Role-based Access</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg>
      </div>
      <div class="feature-title">Donation History Tracking</div>
      <div class="feature-desc">Tracks every donor's donation history and enforces the 56-day cooldown period automatically to ensure donor safety.</div>
      <span class="feature-tag">Safety First</span>
    </div>
    <div class="feature-card">
      <div class="feature-icon">
        <svg viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <div class="feature-title">Secure & Private</div>
      <div class="feature-desc">All donor health data is encrypted and stored securely. Role-based login for donors, hospitals, and admins.</div>
      <span class="feature-tag">Laravel Auth + MySQL</span>
    </div>
  </div>
</section>

<!-- CTA -->
<section class="cta-section">
  <div class="cta-bg">
    <div class="cta-circle cc1"></div>
    <div class="cta-circle cc2"></div>
  </div>
  <h2>One donation saves three lives.</h2>
  <p>Join thousands of donors already registered on LifeLink. It only takes a minute to sign up and make a difference.</p>
  <div class="cta-btns">
    <a href="{{ route('donor.register') }}" class="btn-cta-white">Register as Donor</a>
    <a href="{{ route('donor.login') }}" class="btn-cta-outline">Donor Login →</a>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="logo" style="font-size:1rem;">
    <div class="logo-icon" style="width:28px;height:28px;">
      <svg viewBox="0 0 24 24" style="width:14px;height:14px;fill:white;"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg>
    </div>
    LifeLink
  </div>
  <div class="footer-copy">© 2026 LifeLink Blood Donor Management System</div>
  <div class="footer-links">
    <a href="{{ route('privacy') }}">Privacy</a>
    <a href="{{ route('terms') }}">Terms</a>
    <a href="{{ route('contact') }}">Contact</a>
  </div>
</footer>
{{-- Donor dashboard, Admin dashboard, Hospital dashboard --}}
@include('components.chatbot')