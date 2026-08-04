<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About — LifeLink</title>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,900;1,700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
  <style>
    *{margin:0;padding:0;box-sizing:border-box;}
    :root{
      --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;--primary-mid:#60A5FA;
      --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;--gray-b:#E2E8F0;--gray-l:#F1F5F9;
    }
    html{scroll-behavior:smooth;}
    body{font-family:'DM Sans',sans-serif;background:var(--white);color:var(--text);overflow-x:hidden;}

    /* ── NAV ── */
    nav{position:fixed;top:0;left:0;right:0;z-index:100;padding:0 5%;height:70px;display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,0.95);backdrop-filter:blur(12px);border-bottom:1px solid var(--gray-b);}
    .nav-logo{display:flex;align-items:center;gap:0.6rem;text-decoration:none;}
    .logo-dot{width:34px;height:34px;background:var(--primary);border-radius:9px;display:flex;align-items:center;justify-content:center;}
    .logo-dot svg{width:17px;height:17px;fill:white;}
    .logo-text{font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);}
    .nav-links{display:flex;align-items:center;gap:2rem;}
    .nav-links a{font-size:0.85rem;color:var(--muted);text-decoration:none;transition:color 0.2s;}
    .nav-links a:hover,.nav-links a.active{color:var(--primary);}
    .nav-btns{display:flex;gap:0.75rem;align-items:center;}
    .btn-ghost{padding:0.45rem 1.1rem;border:1.5px solid var(--gray-b);border-radius:8px;font-size:0.83rem;color:var(--text);text-decoration:none;transition:all 0.2s;}
    .btn-ghost:hover{border-color:var(--primary);color:var(--primary);}
    .btn-primary{padding:0.45rem 1.25rem;background:var(--primary);border-radius:8px;font-size:0.83rem;color:white;text-decoration:none;transition:background 0.2s;}
    .btn-primary:hover{background:var(--primary-dark);}

    /* ── HERO ── */
    .hero{padding:140px 5% 80px;background:linear-gradient(160deg,#0F172A 0%,var(--primary-dark) 60%,#172554 100%);position:relative;overflow:hidden;}
    .hero::before{content:'';position:absolute;top:-60px;right:-60px;width:400px;height:400px;border-radius:50%;background:rgba(255,255,255,0.03);}
    .hero::after{content:'';position:absolute;bottom:-80px;left:10%;width:300px;height:300px;border-radius:50%;background:rgba(255,255,255,0.02);}
    .hero-inner{max-width:680px;position:relative;}
    .hero-tag{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.35rem 0.9rem;font-size:0.75rem;color:rgba(255,255,255,0.8);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:1.5rem;}
    .hero-tag span{width:6px;height:6px;background:#06B6D4;border-radius:50%;display:inline-block;}
    .hero h1{font-family:'Playfair Display',serif;font-size:clamp(2.5rem,5vw,3.8rem);font-weight:900;color:white;line-height:1.1;margin-bottom:1.25rem;}
    .hero h1 em{font-style:italic;color:rgba(165,243,252,0.9);}
    .hero p{font-size:1.05rem;color:rgba(255,255,255,0.7);line-height:1.7;max-width:560px;}

    /* ── SECTIONS ── */
    section{padding:80px 5%;}
    .section-inner{max-width:1100px;margin:0 auto;}
    .section-tag{font-size:0.7rem;font-weight:600;color:var(--primary);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:0.75rem;}
    .section-title{font-family:'Playfair Display',serif;font-size:clamp(1.8rem,3vw,2.5rem);font-weight:700;line-height:1.2;margin-bottom:1rem;}
    .section-sub{font-size:1rem;color:var(--muted);line-height:1.7;max-width:600px;}

    /* ── MISSION ── */
    .mission{background:var(--off);}
    .mission-grid{display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:center;}
    .mission-visual{position:relative;}
    .mv-card{background:white;border-radius:16px;padding:2rem;border:1px solid var(--gray-b);position:relative;}
    .mv-big-num{font-family:'Playfair Display',serif;font-size:5rem;font-weight:900;color:var(--primary-light);line-height:1;margin-bottom:0.5rem;}
    .mv-big-num span{color:var(--primary);}
    .mv-label{font-size:0.82rem;color:var(--muted);line-height:1.5;}
    .mv-cards{display:flex;flex-direction:column;gap:1rem;}
    .mv-stat{background:white;border-radius:12px;padding:1.25rem 1.5rem;border:1px solid var(--gray-b);display:flex;align-items:center;gap:1rem;}
    .mv-stat-icon{width:38px;height:38px;background:var(--primary-light);border-radius:9px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .mv-stat-icon svg{width:18px;height:18px;stroke:var(--primary);}
    .mv-stat-val{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;}
    .mv-stat-lbl{font-size:0.72rem;color:var(--muted);}
    .mission-text p{font-size:0.95rem;color:var(--muted);line-height:1.8;margin-bottom:1rem;}

    /* ── VALUES ── */
    .values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem;}
    .value-card{background:var(--off);border-radius:14px;padding:2rem;border:1px solid var(--gray-b);transition:all 0.3s;position:relative;overflow:hidden;}
    .value-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:var(--primary);transform:scaleX(0);transform-origin:left;transition:transform 0.3s;}
    .value-card:hover::before{transform:scaleX(1);}
    .value-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(29,78,216,0.1);}
    .vc-num{font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:900;color:var(--primary-light);margin-bottom:0.75rem;line-height:1;}
    .vc-num span{color:var(--primary);}
    .vc-title{font-weight:600;font-size:0.95rem;margin-bottom:0.5rem;}
    .vc-text{font-size:0.83rem;color:var(--muted);line-height:1.6;}

    /* ── TEAM ── */
    .team{background:var(--off);}
    .team-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-top:3rem;}
    .team-card{background:white;border-radius:14px;overflow:hidden;border:1px solid var(--gray-b);transition:all 0.3s;}
    .team-card:hover{transform:translateY(-4px);box-shadow:0 12px 40px rgba(0,0,0,0.08);}
    .tc-avatar{height:120px;display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:white;}
    .tc-av1{background:linear-gradient(135deg,#0D9488,#115E59);}
    .tc-av2{background:linear-gradient(135deg,#1D4ED8,#1E3A8A);}
    .tc-av3{background:linear-gradient(135deg,#15803D,#14532D);}
    .tc-av4{background:linear-gradient(135deg,#B45309,#92400E);}
    .tc-av5{background:linear-gradient(135deg,#7C3AED,#4C1D95);}
    .tc-av6{background:linear-gradient(135deg,#0E7490,#164E63);}
    .tc-body{padding:1.25rem;}
    .tc-name{font-weight:600;font-size:0.92rem;margin-bottom:0.2rem;}
    .tc-role{font-size:0.75rem;color:var(--primary);font-weight:500;margin-bottom:0.5rem;}
    .tc-bio{font-size:0.78rem;color:var(--muted);line-height:1.5;}

    /* ── TECH ── */
    .tech-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-top:3rem;}
    .tech-card{background:var(--off);border-radius:12px;padding:1.5rem;border:1px solid var(--gray-b);text-align:center;transition:all 0.25s;}
    .tech-card:hover{border-color:var(--primary-mid);background:var(--primary-light);}
    .tech-icon{font-size:1.8rem;margin-bottom:0.6rem;}
    .tech-name{font-weight:600;font-size:0.85rem;margin-bottom:0.2rem;}
    .tech-desc{font-size:0.72rem;color:var(--muted);}

    /* ── TIMELINE ── */
    .timeline{position:relative;padding-left:2rem;margin-top:2.5rem;}
    .timeline::before{content:'';position:absolute;left:0;top:0;bottom:0;width:2px;background:var(--primary-mid);}
    .tl-item{position:relative;padding-bottom:2rem;}
    .tl-item:last-child{padding-bottom:0;}
    .tl-dot{position:absolute;left:-2.4rem;top:0.2rem;width:14px;height:14px;background:var(--primary);border-radius:50%;border:3px solid white;box-shadow:0 0 0 2px var(--primary-mid);}
    .tl-year{font-size:0.72rem;font-weight:700;color:var(--primary);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:0.3rem;}
    .tl-title{font-weight:600;font-size:0.92rem;margin-bottom:0.3rem;}
    .tl-text{font-size:0.82rem;color:var(--muted);line-height:1.6;}

    /* ── FOOTER ── */
    footer{background:#0F172A;padding:3rem 5% 2rem;}
    .footer-inner{max-width:1100px;margin:0 auto;}
    .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:2.5rem;}
    .footer-brand .logo-text{color:white;}
    .footer-brand p{font-size:0.82rem;color:rgba(255,255,255,0.45);line-height:1.7;margin-top:0.75rem;}
    .footer-col h4{font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;}
    .footer-col a{display:block;font-size:0.82rem;color:rgba(255,255,255,0.55);text-decoration:none;margin-bottom:0.5rem;transition:color 0.2s;}
    .footer-col a:hover{color:white;}
    .footer-bottom{border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;}
    .footer-copy{font-size:0.75rem;color:rgba(255,255,255,0.3);}

    /* ── ANIMATIONS ── */
    @keyframes fadeUp{from{opacity:0;transform:translateY(30px);}to{opacity:1;transform:translateY(0);}}
    .hero-inner > *{animation:fadeUp 0.7s ease both;}
    .hero-inner > *:nth-child(1){animation-delay:0.1s;}
    .hero-inner > *:nth-child(2){animation-delay:0.25s;}
    .hero-inner > *:nth-child(3){animation-delay:0.4s;}

    @media(max-width:768px){
      .mission-grid,.values-grid,.team-grid,.tech-grid,.footer-grid{grid-template-columns:1fr;}
      .nav-links{display:none;}
    }
    
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
  </style>
</head>
<body>

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
  <div class="hero-inner">
    <div class="hero-tag"><span></span> Our Story</div>
    <h1>Built to save <em>lives</em> with intelligence</h1>
    <p>LifeLink is Sri Lanka's first AI-powered blood donor management system — connecting eligible donors with hospitals faster, smarter, and more accurately than ever before.</p>
  </div>
</section>

<!-- MISSION -->
<section class="mission">
  <div class="section-inner">
    <div class="mission-grid">
      @php
        $accuracyWhole = null; $accuracyDecimal = null;
        if (!empty($modelMetrics['accuracy'])) {
          $accuracyParts   = explode('.', number_format($modelMetrics['accuracy'], 2));
          $accuracyWhole   = $accuracyParts[0];
          $accuracyDecimal = $accuracyParts[1] ?? '00';
        }
      @endphp
      <div class="mission-visual">
        <div class="mv-cards">
          <div class="mv-card">
            <div class="mv-big-num">
              @if($accuracyWhole !== null)
                <span>{{ $accuracyWhole }}</span>.{{ $accuracyDecimal }}%
              @else
                <span>—</span>
              @endif
            </div>
            <div class="mv-label">AI model accuracy using {{ $modelMetrics['model'] ?? 'our current model' }} on {{ number_format($modelMetrics['trained_on_rows'] ?? 0) }} real donor records — our core prediction engine.</div>
          </div>
          <div class="mv-stat">
            <div class="mv-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg></div>
            <div><div class="mv-stat-val">{{ number_format($modelMetrics['trained_on_rows'] ?? 0) }}</div><div class="mv-stat-lbl">Donor records trained on</div></div>
          </div>
          <div class="mv-stat">
            <div class="mv-stat-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg></div>
            <div><div class="mv-stat-val">{{ $hospitalCount ?? 0 }}</div><div class="mv-stat-lbl">Partner hospitals across Sri Lanka</div></div>
          </div>
        </div>
      </div>
      <div class="mission-text">
        <div class="section-tag">Our Mission</div>
        <h2 class="section-title">Making every drop count</h2>
        <p>Blood shortages cost lives — not because donors don't exist, but because connecting the right donor to the right hospital at the right time has always been a manual, slow process.</p>
        <p>LifeLink changes that. Our AI analyses key health indicators in real time and predicts donor eligibility with
          @if($accuracyWhole !== null)
            {{ $accuracyWhole }}.{{ $accuracyDecimal }}%
          @else
            high
          @endif
          accuracy — so hospitals get matched to eligible donors in seconds, not hours.</p>
        <p>Built as a final-year research project at the University of Sri Lanka, LifeLink combines machine learning, modern web technology, and a passion for public health into one unified platform.</p>
      </div>
    </div>
  </div>
</section>

<!-- VALUES -->
<section>
  <div class="section-inner">
    <div class="section-tag">What We Stand For</div>
    <h2 class="section-title">Our core values</h2>
    <div class="values-grid">
      <div class="value-card">
        <div class="vc-num"><span>01</span></div>
        <div class="vc-title">Accuracy First</div>
        <div class="vc-text">Every eligibility decision is backed by a trained machine learning model — not guesswork. We rigorously validated our model on a 70/30 train-test split before deployment.</div>
      </div>
      <div class="value-card">
        <div class="vc-num"><span>02</span></div>
        <div class="vc-title">Privacy by Design</div>
        <div class="vc-text">Donor health data is encrypted, never sold, and only used for eligibility matching. Your blood group and medical details are yours — we're just the bridge.</div>
      </div>
      <div class="value-card">
        <div class="vc-num"><span>03</span></div>
        <div class="vc-title">Speed &amp; Reliability</div>
        <div class="vc-text">In emergencies, every minute matters. Our FastAPI prediction engine returns eligibility results in under 200ms — faster than a human can read a form.</div>
      </div>
      <div class="value-card">
        <div class="vc-num"><span>04</span></div>
        <div class="vc-title">Inclusive Access</div>
        <div class="vc-text">Built for Sri Lanka — all 25 districts, all NBTS donation centres, all 8 blood groups. Whether you're in Colombo or Jaffna, LifeLink works for you.</div>
      </div>
      <div class="value-card">
        <div class="vc-num"><span>05</span></div>
        <div class="vc-title">Open Science</div>
        <div class="vc-text">Our models, training data structure, and evaluation metrics are documented openly. We believe in reproducible research and transparent AI.</div>
      </div>
      <div class="value-card">
        <div class="vc-num"><span>06</span></div>
        <div class="vc-title">Human Impact</div>
        <div class="vc-text">Each donation can save up to 3 lives. Every line of code we write, every model we train, every feature we build — it's all in service of that single truth.</div>
      </div>
    </div>
  </div>
</section>

<!-- TECHNOLOGY -->
<section style="background:var(--off);">
  <div class="section-inner">
    <div class="section-tag">Technology Stack</div>
    <h2 class="section-title">How it's built</h2>
    <p class="section-sub">A full-stack system combining a Laravel web application with a Python AI microservice — connected in real time.</p>
    <div class="tech-grid">
      <div class="tech-card">
        <div class="tech-icon">🐘</div>
        <div class="tech-name">Laravel 12</div>
        <div class="tech-desc">Web framework, auth, routing, DB</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🐍</div>
        <div class="tech-name">Python + FastAPI</div>
        <div class="tech-desc">AI prediction microservice</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🤖</div>
        <div class="tech-name">scikit-learn</div>
        <div class="tech-desc">{{ $modelMetrics['model'] ?? 'Logistic Regression / k-NN' }} — evaluated head-to-head</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🗄️</div>
        <div class="tech-name">MySQL</div>
        <div class="tech-desc">Donor & hospital data storage</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🎨</div>
        <div class="tech-name">HTML / CSS / JS</div>
        <div class="tech-desc">Responsive frontend UI</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🔐</div>
        <div class="tech-name">Laravel Guards</div>
        <div class="tech-desc">Multi-role auth (Donor/Hospital/Admin)</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">📊</div>
        <div class="tech-name">pandas + numpy</div>
        <div class="tech-desc">Data preprocessing & feature engineering</div>
      </div>
      <div class="tech-card">
        <div class="tech-icon">🚀</div>
        <div class="tech-name">Uvicorn</div>
        <div class="tech-desc">ASGI server for the AI API</div>
      </div>
    </div>
  </div>
</section>

<!-- JOURNEY -->
<section>
  <div class="section-inner">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:5rem;align-items:start;">
      <div>
        <div class="section-tag">Project Journey</div>
        <h2 class="section-title">How LifeLink came to be</h2>
        <p class="section-sub">From a research idea to a working full-stack AI system.</p>
      </div>
      <div class="timeline">
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">Phase 1</div>
          <div class="tl-title">Research & Dataset Collection</div>
          <div class="tl-text">Collected and cleaned 10,000 donor records. Analysed eligibility criteria from the National Blood Transfusion Service of Sri Lanka.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">Phase 2</div>
          <div class="tl-title">Model Training & Evaluation</div>
          <div class="tl-text">Compared Logistic Regression and k-NN classifiers on a held-out test set with precision, recall, and F1 scoring — k-NN won and is the model in production.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">Phase 3</div>
          <div class="tl-title">FastAPI Microservice</div>
          <div class="tl-text">Wrapped the trained model in a FastAPI endpoint. Real-time prediction in under 200ms with graceful fallback if the AI service is unavailable.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">Phase 4</div>
          <div class="tl-title">Laravel Full-Stack Integration</div>
          <div class="tl-text">Built the complete web platform — multi-role authentication, donor registration, hospital dashboard, admin panel, and live AI integration.</div>
        </div>
        <div class="tl-item">
          <div class="tl-dot"></div>
          <div class="tl-year">Now</div>
          <div class="tl-title">Live & Expanding</div>
          <div class="tl-text">LifeLink is live and being extended with SMS notifications, hospital blood stock management, and real-time donor-hospital matching.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer>
  <div class="footer-inner">
    <div class="footer-grid">
      <div class="footer-brand">
        <div class="nav-logo" style="display:flex;align-items:center;gap:0.6rem;margin-bottom:0.5rem;">
          <div class="logo-dot"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
          <span class="logo-text">LifeLink</span>
        </div>
        <p>Sri Lanka's AI-powered blood donor management system. Connecting donors and hospitals since 2025.</p>
      </div>
      <div class="footer-col">
        <h4>Platform</h4>
        <a href="/">Home</a>
        <a href="/about">About</a>
        <a href="/find-donors">Find Donors</a>
        <a href="/contact">Contact</a>
      </div>
      <div class="footer-col">
        <h4>Donors</h4>
        <a href="{{ route('donor.register') }}">Register</a>
        <a href="{{ route('donor.login') }}">Sign In</a>
        <a href="/about">How it Works</a>
      </div>
      <div class="footer-col">
        <h4>Legal</h4>
        <a href="/privacy">Privacy Policy</a>
        <a href="/terms">Terms of Service</a>
      </div>
    </div>
    <div class="footer-bottom">
      <div class="footer-copy">© 2025 LifeLink. Built for the National Blood Transfusion Service of Sri Lanka.</div>
      <div class="footer-copy">A Final Year Research Project</div>
    </div>
  </div>
</footer>

</body>
</html>