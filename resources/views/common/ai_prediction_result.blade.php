
<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap');
  *{margin:0;padding:0;box-sizing:border-box;}
  :root{
    --primary:#1D4ED8;--primary-dark:#1E3A8A;--primary-light:#EFF6FF;
    --white:#fff;--off:#F8FAFC;--text:#1E293B;--muted:#64748B;
    --border:rgba(29,78,216,0.12);--gray:#F4F1F1;--gray-b:#E4DEDE;
    --green:#16A34A;--green-bg:#F0FDF4;--green-b:#BBF7D0;
    --amber:#D97706;--amber-bg:#FFFBEB;
    --blue:#1D4ED8;--blue-bg:#EFF6FF;--blue-b:#BFDBFE;
    --accent:#06B6D4;--accent-light:#ECFEFF;--accent-dark:#0E7490;
  }
  body{font-family:'DM Sans',sans-serif;background:var(--off);color:var(--text);min-height:100vh;}

  /* NAV */
  nav{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 3rem;background:white;border-bottom:1px solid var(--border);}
  .logo{display:flex;align-items:center;gap:10px;font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--primary-dark);}
  .logo-icon{width:32px;height:32px;background:var(--primary);border-radius:50%;display:flex;align-items:center;justify-content:center;}
  .logo-icon svg{width:16px;height:16px;fill:white;}
  .nav-right{display:flex;align-items:center;gap:10px;}
  .nav-pill{display:flex;align-items:center;gap:6px;background:var(--blue-bg);border:1px solid var(--blue-b);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.75rem;font-weight:500;color:var(--blue);}
  .nav-pill svg{width:13px;height:13px;stroke:var(--blue);fill:none;stroke-width:2;}
  .btn-back{padding:0.45rem 1.1rem;border:1px solid var(--gray-b);border-radius:7px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--muted);cursor:pointer;}

  /* PAGE */
  .page{max-width:900px;margin:0 auto;padding:2.5rem 2rem;}

  /* HEADER */
  .page-header{display:flex;align-items:center;justify-content:space-between;margin-bottom:2rem;}
  .ph-left{}
  .ph-label{font-size:0.7rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:var(--muted);margin-bottom:4px;}
  .ph-title{font-family:'Playfair Display',serif;font-size:1.75rem;font-weight:700;color:var(--text);}
  .ph-sub{font-size:0.85rem;color:var(--muted);font-weight:300;margin-top:4px;}
  .ph-right{display:flex;gap:8px;}
  .btn-sm{padding:0.5rem 1.1rem;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:0.8rem;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:6px;}
  .btn-sm svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
  .btn-outline{border:1px solid var(--gray-b);background:white;color:var(--muted);}
  .btn-outline:hover{border-color:var(--primary);color:var(--primary);}
  .btn-red{border:none;background:var(--primary);color:white;}
  .btn-red:hover{background:var(--primary-dark);}

  /* RESULT HERO */
  .result-hero{border-radius:18px;overflow:hidden;margin-bottom:1.5rem;display:grid;grid-template-columns:1fr auto;}
  .rh-left{background:var(--primary-dark);padding:2.25rem 2.5rem;position:relative;overflow:hidden;}
  .rhc{position:absolute;border-radius:50%;border:1px solid rgba(255,255,255,0.07);}
  .rhc1{width:350px;height:350px;top:-100px;right:-80px;}
  .rhc2{width:200px;height:200px;bottom:-60px;left:-40px;}
  .rh-badge{display:inline-flex;align-items:center;gap:6px;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.3rem 0.9rem;font-size:0.68rem;font-weight:500;color:rgba(255,255,255,0.8);letter-spacing:0.08em;text-transform:uppercase;margin-bottom:1rem;position:relative;z-index:2;}
  .rh-badge span{width:5px;height:5px;border-radius:50%;background:#86EFAC;display:inline-block;}
  .rh-verdict{font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:white;line-height:1.1;margin-bottom:0.6rem;position:relative;z-index:2;}
  .rh-sub{font-size:0.875rem;color:rgba(255,255,255,0.6);font-weight:300;max-width:340px;line-height:1.65;position:relative;z-index:2;}
  .rh-meta{display:flex;gap:12px;margin-top:1.5rem;position:relative;z-index:2;}
  .rh-meta-item{background:rgba(255,255,255,0.08);border:1px solid rgba(255,255,255,0.12);border-radius:8px;padding:0.55rem 0.9rem;font-size:0.75rem;color:rgba(255,255,255,0.7);}
  .rh-meta-item strong{color:white;display:block;font-size:0.85rem;margin-bottom:1px;}
  .rh-right{background:var(--primary);padding:2.25rem 2.5rem;display:flex;flex-direction:column;align-items:center;justify-content:center;min-width:200px;}
  .gauge-wrap{position:relative;width:140px;height:140px;}
  .gauge-svg{width:140px;height:140px;}
  .gauge-num{position:absolute;inset:0;display:flex;flex-direction:column;align-items:center;justify-content:center;}
  .gauge-val{font-family:'Playfair Display',serif;font-size:2.25rem;font-weight:900;color:white;line-height:1;}
  .gauge-pct{font-size:0.8rem;color:rgba(255,255,255,0.65);margin-top:2px;}
  .gauge-label{font-size:0.7rem;color:rgba(255,255,255,0.65);letter-spacing:0.08em;text-transform:uppercase;margin-top:0.75rem;text-align:center;}

  /* SECTION */
  .section-row{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;}
  .section-full{margin-bottom:14px;}

  /* CARD */
  .card{background:white;border:1px solid var(--border);border-radius:14px;padding:1.25rem 1.5rem;}
  .card-hd{display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:1.25rem;}
  .card-t{font-size:0.9rem;font-weight:500;color:var(--text);}
  .card-s{font-size:0.72rem;color:var(--muted);margin-top:2px;}
  .badge{display:inline-block;font-size:0.68rem;font-weight:500;padding:3px 9px;border-radius:20px;}
  .b-g{background:var(--green-bg);color:var(--green);}
  .b-r{background:var(--accent-light);color:var(--accent-dark);}
  .b-a{background:var(--amber-bg);color:var(--amber);}
  .b-b{background:var(--blue-bg);color:var(--blue);}

  /* FACTOR BARS */
  .factor-list{display:flex;flex-direction:column;gap:14px;}
  .factor-row{}
  .factor-top{display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;}
  .factor-name{font-size:0.82rem;font-weight:500;color:var(--text);display:flex;align-items:center;gap:7px;}
  .factor-icon{width:22px;height:22px;border-radius:5px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .fi-g{background:var(--green-bg);}  .fi-g svg{stroke:var(--green);}
  .fi-r{background:var(--amber-bg);} .fi-r svg{stroke:var(--amber);}
  .fi-a{background:var(--amber-bg);} .fi-a svg{stroke:var(--amber);}
  .fi-b{background:var(--blue-bg);} .fi-b svg{stroke:var(--blue);}
  .factor-icon svg{width:12px;height:12px;fill:none;stroke-width:2;stroke-linecap:round;}
  .factor-vals{display:flex;align-items:center;gap:8px;}
  .factor-val{font-size:0.8rem;font-weight:500;color:var(--text);}
  .factor-threshold{font-size:0.7rem;color:var(--muted);}
  .factor-track{background:var(--gray);border-radius:5px;height:8px;overflow:hidden;}
  .factor-fill{height:8px;border-radius:5px;transition:width 0.7s;}
  .ff-g{background:linear-gradient(90deg,#16A34A,#22C55E);}
  .ff-r{background:linear-gradient(90deg,var(--amber),#F59E0B);}
  .ff-a{background:linear-gradient(90deg,var(--amber),#F59E0B);}
  .factor-status{font-size:0.7rem;margin-top:5px;font-weight:500;}
  .fs-g{color:var(--green);}
  .fs-r{color:var(--amber);}
  .fs-a{color:var(--amber);}

  /* MODEL COMPARISON */
  .model-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px;}
  .model-card{border:1px solid var(--border);border-radius:10px;padding:1rem;position:relative;}
  .model-card.winner{border-color:var(--primary);background:var(--primary-light);}
  .winner-tag{position:absolute;top:-1px;right:10px;background:var(--primary);color:white;font-size:0.6rem;font-weight:500;padding:2px 8px;border-radius:0 0 6px 6px;letter-spacing:0.06em;text-transform:uppercase;}
  .model-name{font-size:0.82rem;font-weight:500;color:var(--text);margin-bottom:0.6rem;}
  .model-acc-wrap{display:flex;align-items:flex-end;gap:6px;margin-bottom:6px;}
  .model-acc{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:var(--text);line-height:1;}
  .model-acc-pct{font-size:0.8rem;color:var(--muted);margin-bottom:4px;}
  .model-bar-track{background:var(--gray);border-radius:4px;height:5px;overflow:hidden;margin-bottom:6px;}
  .model-bar-fill{height:5px;border-radius:4px;}
  .mf-red{background:var(--primary);}
  .mf-gray{background:var(--gray-b);}
  .model-detail{font-size:0.7rem;color:var(--muted);}

  /* DECISION FACTORS */
  .decision-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;}
  .dec-item{border:1px solid var(--border);border-radius:10px;padding:0.9rem 1rem;text-align:center;}
  .dec-icon{width:36px;height:36px;border-radius:9px;display:flex;align-items:center;justify-content:center;margin:0 auto 0.6rem;}
  .dec-icon svg{width:17px;height:17px;fill:none;stroke-width:1.75;stroke-linecap:round;}
  .dec-val{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--text);margin-bottom:2px;}
  .dec-label{font-size:0.7rem;color:var(--muted);}
  .dec-badge{margin-top:6px;}

  /* RECOMMENDATION BOX */
  .rec-box{border-radius:12px;padding:1.25rem 1.5rem;display:flex;align-items:flex-start;gap:14px;}
  .rec-elig{background:var(--green-bg);border:1px solid var(--green-b);}
  .rec-icon{width:40px;height:40px;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
  .rec-icon svg{width:20px;height:20px;fill:none;stroke-width:2;stroke-linecap:round;stroke-linejoin:round;}
  .ri-g{background:var(--green);} .ri-g svg{stroke:white;}
  .rec-text{}
  .rec-title{font-size:0.95rem;font-weight:500;color:var(--text);margin-bottom:4px;}
  .rec-desc{font-size:0.82rem;color:var(--muted);line-height:1.65;font-weight:300;}
  .rec-actions{display:flex;gap:8px;margin-top:1rem;}
  .rec-btn{padding:0.55rem 1.25rem;border-radius:7px;font-family:'DM Sans',sans-serif;font-size:0.8rem;font-weight:500;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;gap:6px;}
  .rec-btn svg{width:13px;height:13px;stroke:currentColor;fill:none;stroke-width:2;}
  .rb-g{background:var(--green);color:white;border:none;}
  .rb-g:hover{background:#15803D;}
  .rb-o{background:white;color:var(--green);border:1px solid var(--green-b);}
  .rb-o:hover{background:var(--green-bg);}

  /* TIMELINE */
  .timeline{display:flex;flex-direction:column;gap:0;}
  .tl-item{display:flex;gap:14px;padding-bottom:1.1rem;position:relative;}
  .tl-item:last-child{padding-bottom:0;}
  .tl-left{display:flex;flex-direction:column;align-items:center;}
  .tl-dot{width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;z-index:1;}
  .tl-dot svg{width:13px;height:13px;stroke:white;fill:none;stroke-width:2.5;stroke-linecap:round;}
  .td-g{background:var(--green);}
  .td-r{background:var(--accent);}
  .td-b{background:var(--blue);}
  .td-gray{background:var(--gray-b);}
  .td-gray svg{stroke:var(--muted);}
  .tl-line{flex:1;width:1px;background:var(--border);margin-top:4px;}
  .tl-item:last-child .tl-line{display:none;}
  .tl-body{padding-top:4px;flex:1;}
  .tl-t{font-size:0.85rem;font-weight:500;color:var(--text);margin-bottom:2px;}
  .tl-s{font-size:0.75rem;color:var(--muted);line-height:1.5;}
  .tl-date{font-size:0.7rem;color:var(--muted);margin-top:4px;}

  /* DISCLAIMER */
  .disclaimer{background:var(--amber-bg);border:1px solid var(--amber-b, #FDE68A);border-radius:10px;padding:0.9rem 1.1rem;display:flex;gap:10px;align-items:flex-start;margin-top:14px;}
  .disclaimer svg{width:16px;height:16px;stroke:var(--amber);fill:none;stroke-width:2;flex-shrink:0;margin-top:1px;}
  .disclaimer p{font-size:0.775rem;color:#78350F;line-height:1.6;}
</style>

<nav>
  <div class="logo">
    <div class="logo-icon"><svg viewBox="0 0 24 24"><path d="M12 2C12 2 5 9.5 5 14a7 7 0 0014 0C19 9.5 12 2 12 2z"/></svg></div>
    LifeLink
  </div>
  <div class="nav-right">
    <div class="nav-pill"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/></svg>AI Prediction Engine</div>
    <button class="btn-back">← Back to Dashboard</button>
  </div>
</nav>

<div class="page">

  <!-- HEADER -->
  <div class="page-header">
    <div class="ph-left">
      <div class="ph-label">Prediction Report</div>
      <div class="ph-title">Donor Eligibility Result</div>
      <div class="ph-sub">Generated on Friday, 29 May 2026 · 09:14 AM · Model v2.1</div>
    </div>
    <div class="ph-right">
      <button class="btn-sm btn-outline" onclick="sendPrompt('Download my AI prediction report as a PDF')">
        <svg viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Download PDF
      </button>
      <button class="btn-sm btn-red" onclick="sendPrompt('I want to book a blood donation appointment at NBTS Colombo')">
        <svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
        Book Donation
      </button>
    </div>
  </div>

  <!-- RESULT HERO -->
  <div class="result-hero">
    <div class="rh-left">
      <div class="rhc rhc1"></div><div class="rhc rhc2"></div>
      <div class="rh-badge"><span></span>AI Verdict · Logistic Regression</div>
      <div class="rh-verdict">Eligible to Donate Blood</div>
      <div class="rh-sub">All three eligibility criteria are met. The AI model predicts with high confidence that you are suitable for whole blood donation at this time.</div>
      <div class="rh-meta">
        <div class="rh-meta-item"><strong>Kasun Perera</strong>Donor name</div>
        <div class="rh-meta-item"><strong>O+</strong>Blood group</div>
        <div class="rh-meta-item"><strong>Colombo</strong>Location</div>
        <div class="rh-meta-item"><strong>4 prior</strong>Donations</div>
      </div>
    </div>
    <div class="rh-right">
      <div class="gauge-wrap">
        <svg class="gauge-svg" viewBox="0 0 140 140">
          <circle cx="70" cy="70" r="54" fill="none" stroke="rgba(255,255,255,0.15)" stroke-width="12"/>
          <circle cx="70" cy="70" r="54" fill="none" stroke="white" stroke-width="12"
            stroke-dasharray="307" stroke-dashoffset="22"
            stroke-linecap="round" transform="rotate(-90 70 70)"/>
        </svg>
        <div class="gauge-num">
          <div class="gauge-val">96</div>
          <div class="gauge-pct">% confidence</div>
        </div>
      </div>
      <div class="gauge-label">AI Confidence Score</div>
    </div>
  </div>

  <!-- FACTOR BARS + MODEL COMPARISON -->
  <div class="section-row">

    <!-- FACTOR BREAKDOWN -->
    <div class="card">
      <div class="card-hd">
        <div><div class="card-t">Eligibility Factors</div><div class="card-s">Input features used by the model</div></div>
        <span class="badge b-g">3 / 3 passed</span>
      </div>
      <div class="factor-list">
        <div class="factor-row">
          <div class="factor-top">
            <div class="factor-name">
              <div class="factor-icon fi-g"><svg viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
              Age
            </div>
            <div class="factor-vals"><span class="factor-val">28 yrs</span><span class="factor-threshold">min 18</span></div>
          </div>
          <div class="factor-track"><div class="factor-fill ff-g" style="width:86%"></div></div>
          <div class="factor-status fs-g">✓ Well above minimum — optimal donation age</div>
        </div>
        <div class="factor-row">
          <div class="factor-top">
            <div class="factor-name">
              <div class="factor-icon fi-g"><svg viewBox="0 0 24 24"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
              Weight
            </div>
            <div class="factor-vals"><span class="factor-val">65 kg</span><span class="factor-threshold">min 50 kg</span></div>
          </div>
          <div class="factor-track"><div class="factor-fill ff-g" style="width:72%"></div></div>
          <div class="factor-status fs-g">✓ 15 kg above minimum threshold</div>
        </div>
        <div class="factor-row">
          <div class="factor-top">
            <div class="factor-name">
              <div class="factor-icon fi-g"><svg viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
              Hemoglobin
            </div>
            <div class="factor-vals"><span class="factor-val">13.5 g/dL</span><span class="factor-threshold">min 12.0</span></div>
          </div>
          <div class="factor-track"><div class="factor-fill ff-g" style="width:80%"></div></div>
          <div class="factor-status fs-g">✓ 1.5 g/dL above minimum — healthy iron level</div>
        </div>
        <div class="factor-row">
          <div class="factor-top">
            <div class="factor-name">
              <div class="factor-icon fi-b"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
              Recovery Period
            </div>
            <div class="factor-vals"><span class="factor-val">56 days</span><span class="factor-threshold">required 56</span></div>
          </div>
          <div class="factor-track"><div class="factor-fill ff-g" style="width:100%"></div></div>
          <div class="factor-status fs-g">✓ Full 56-day cooldown completed</div>
        </div>
      </div>
    </div>

    <!-- MODEL COMPARISON -->
    <div class="card">
      <div class="card-hd">
        <div><div class="card-t">Model Comparison</div><div class="card-s">Both models tested on your data</div></div>
      </div>
      <div class="model-grid">
        <div class="model-card winner">
          <div class="winner-tag">Selected</div>
          <div class="model-name">Logistic Regression</div>
          <div class="model-acc-wrap">
            <div class="model-acc">94.99</div>
            <div class="model-acc-pct">%</div>
          </div>
          <div class="model-bar-track"><div class="model-bar-fill mf-red" style="width:95%"></div></div>
          <div class="model-detail">Best overall accuracy · Recommended for structured donor data · Handles linearly separable patterns well</div>
        </div>
        <div class="model-card">
          <div class="model-name">k-Nearest Neighbors</div>
          <div class="model-acc-wrap">
            <div class="model-acc">94.08</div>
            <div class="model-acc-pct">%</div>
          </div>
          <div class="model-bar-track"><div class="model-bar-fill mf-gray" style="width:94%"></div></div>
          <div class="model-detail">0.91% lower accuracy · Instance-based learning · Sensitive to feature scaling</div>
        </div>
      </div>

      <!-- MINI STATS -->
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-top:1rem;">
        <div style="text-align:center;padding:0.75rem;background:var(--gray);border-radius:8px;">
          <div style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);">2,195</div>
          <div style="font-size:0.68rem;color:var(--muted);margin-top:2px;">Training samples</div>
        </div>
        <div style="text-align:center;padding:0.75rem;background:var(--gray);border-radius:8px;">
          <div style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);">6</div>
          <div style="font-size:0.68rem;color:var(--muted);margin-top:2px;">Input features</div>
        </div>
        <div style="text-align:center;padding:0.75rem;background:var(--gray);border-radius:8px;">
          <div style="font-family:'Playfair Display',serif;font-size:1.2rem;font-weight:700;color:var(--text);">70/30</div>
          <div style="font-size:0.68rem;color:var(--muted);margin-top:2px;">Train/test split</div>
        </div>
      </div>
    </div>
  </div>

  <!-- DECISION METRICS -->
  <div class="card section-full">
    <div class="card-hd">
      <div><div class="card-t">Decision Metrics</div><div class="card-s">Individual factor scores used in the final prediction</div></div>
    </div>
    <div class="decision-grid">
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--green-bg);"><svg viewBox="0 0 24 24" style="stroke:var(--green)"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
        <div class="dec-val">28</div>
        <div class="dec-label">Age (years)</div>
        <div class="dec-badge"><span class="badge b-g">✓ Pass</span></div>
      </div>
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--green-bg);"><svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg></div>
        <div class="dec-val">65<span style="font-size:0.9rem;font-family:'DM Sans';font-weight:300">kg</span></div>
        <div class="dec-label">Weight</div>
        <div class="dec-badge"><span class="badge b-g">✓ Pass</span></div>
      </div>
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--green-bg);"><svg viewBox="0 0 24 24" style="stroke:var(--green)"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
        <div class="dec-val">13.5</div>
        <div class="dec-label">Hemoglobin (g/dL)</div>
        <div class="dec-badge"><span class="badge b-g">✓ Pass</span></div>
      </div>
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--accent-light);"><svg viewBox="0 0 24 24" style="stroke:var(--accent)"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div>
        <div class="dec-val">O<span style="font-size:1.1rem">+</span></div>
        <div class="dec-label">Blood Group</div>
        <div class="dec-badge"><span class="badge b-r">Universal Donor</span></div>
      </div>
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--blue-bg);"><svg viewBox="0 0 24 24" style="stroke:var(--blue)"><circle cx="12" cy="8" r="4"/><path d="M4 20c0-4 3.6-7 8-7s8 3 8 7"/></svg></div>
        <div class="dec-val">M</div>
        <div class="dec-label">Gender</div>
        <div class="dec-badge"><span class="badge b-b">Male</span></div>
      </div>
      <div class="dec-item">
        <div class="dec-icon" style="background:var(--green-bg);"><svg viewBox="0 0 24 24" style="stroke:var(--green)"><rect x="5" y="2" width="14" height="20" rx="2"/><line x1="9" y1="7" x2="15" y2="7"/><line x1="9" y1="11" x2="15" y2="11"/></svg></div>
        <div class="dec-val">4</div>
        <div class="dec-label">Past Donations</div>
        <div class="dec-badge"><span class="badge b-g">Experienced</span></div>
      </div>
    </div>
  </div>

  <!-- RECOMMENDATION + TIMELINE -->
  <div class="section-row">
    <div class="card">
      <div class="card-hd"><div><div class="card-t">AI Recommendation</div><div class="card-s">Based on prediction result</div></div></div>
      <div class="rec-box rec-elig" style="margin-bottom:1rem;">
        <div class="rec-icon ri-g"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div>
        <div class="rec-text">
          <div class="rec-title">Proceed with Blood Donation</div>
          <div class="rec-desc">Your health profile fully meets the donation criteria. The AI recommends scheduling a whole blood donation at your nearest NBTS centre. O+ is one of the most needed blood types in Sri Lanka.</div>
          <div class="rec-actions">
            <button class="rec-btn rb-g" onclick="sendPrompt('Book a donation appointment at NBTS Colombo for me')">
              <svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
              Book Appointment
            </button>
            <button class="rec-btn rb-o" onclick="sendPrompt('Find NBTS donation centres near Colombo')">
              <svg viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
              Find Nearest Centre
            </button>
          </div>
        </div>
      </div>
      <div class="disclaimer">
        <svg viewBox="0 0 24 24"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
        <p>This AI prediction is for guidance only and does not replace a clinical assessment. Final eligibility is confirmed by a qualified nurse or doctor at the donation centre on the day of donation.</p>
      </div>
    </div>

    <!-- PREDICTION TIMELINE -->
    <div class="card">
      <div class="card-hd"><div><div class="card-t">What Happens Next</div><div class="card-s">Your donation journey</div></div></div>
      <div class="timeline">
        <div class="tl-item">
          <div class="tl-left"><div class="tl-dot td-g"><svg viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg></div><div class="tl-line"></div></div>
          <div class="tl-body"><div class="tl-t">AI Eligibility Confirmed</div><div class="tl-s">Logistic Regression model confirmed you are eligible with 96% confidence.</div><div class="tl-date">Today · 09:14 AM</div></div>
        </div>
        <div class="tl-item">
          <div class="tl-left"><div class="tl-dot td-r"><svg viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg></div><div class="tl-line"></div></div>
          <div class="tl-body"><div class="tl-t">Book Your Appointment</div><div class="tl-s">Schedule at NBTS Colombo, Kandy, or any partner centre near you.</div><div class="tl-date">Next step</div></div>
        </div>
        <div class="tl-item">
          <div class="tl-left"><div class="tl-dot td-b"><svg viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg></div><div class="tl-line"></div></div>
          <div class="tl-body"><div class="tl-t">Attend Donation Centre</div><div class="tl-s">Nurse confirms eligibility on-site. Whole blood donation takes about 10–15 minutes.</div><div class="tl-date">On appointment day</div></div>
        </div>
        <div class="tl-item">
          <div class="tl-left"><div class="tl-dot td-g"><svg viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg></div><div class="tl-line"></div></div>
          <div class="tl-body"><div class="tl-t">Donation Complete</div><div class="tl-s">Your record is updated. Certificate issued. 56-day recovery timer starts.</div><div class="tl-date">After donation</div></div>
        </div>
        <div class="tl-item">
          <div class="tl-left"><div class="tl-dot td-gray"><svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div></div>
          <div class="tl-body"><div class="tl-t">Next Eligible Date</div><div class="tl-s">AI will automatically re-check eligibility after 56 days and notify you.</div><div class="tl-date">56 days after donation</div></div>
        </div>
      </div>
    </div>
  </div>

</div>
