<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact — LifeLink</title>
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
    .hero{padding:130px 5% 70px;background:linear-gradient(160deg,#1A0A0B 0%,var(--red-dark) 55%,#3D0A11 100%);position:relative;overflow:hidden;text-align:center;}
    .hero::before{content:'';position:absolute;top:-80px;left:50%;transform:translateX(-50%);width:500px;height:500px;border-radius:50%;background:rgba(255,255,255,0.03);}
    .hero-tag{display:inline-flex;align-items:center;gap:0.5rem;background:rgba(255,255,255,0.1);border:1px solid rgba(255,255,255,0.15);border-radius:20px;padding:0.35rem 0.9rem;font-size:0.75rem;color:rgba(255,255,255,0.8);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:1.5rem;}
    .hero-tag span{width:6px;height:6px;background:#FF6B7A;border-radius:50%;display:inline-block;}
    .hero h1{font-family:'Playfair Display',serif;font-size:clamp(2.2rem,5vw,3.5rem);font-weight:900;color:white;line-height:1.15;margin-bottom:1rem;}
    .hero h1 em{font-style:italic;color:rgba(255,180,180,0.9);}
    .hero p{font-size:1rem;color:rgba(255,255,255,0.65);max-width:520px;margin:0 auto;line-height:1.7;}

    /* CONTENT */
    .main-section{padding:80px 5%;}
    .section-inner{max-width:1100px;margin:0 auto;}

    /* CONTACT GRID */
    .contact-grid{display:grid;grid-template-columns:1fr 1.4fr;gap:4rem;align-items:start;}

    /* INFO PANEL */
    .info-panel{}
    .info-panel h2{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;margin-bottom:0.75rem;}
    .info-panel p{font-size:0.9rem;color:var(--muted);line-height:1.7;margin-bottom:2rem;}
    .contact-cards{display:flex;flex-direction:column;gap:1rem;}
    .contact-card{background:var(--off);border-radius:12px;padding:1.25rem 1.5rem;border:1px solid var(--gray-b);display:flex;align-items:flex-start;gap:1rem;transition:all 0.25s;}
    .contact-card:hover{border-color:var(--red-mid);background:var(--red-light);}
    .cc-icon{width:40px;height:40px;background:white;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;border:1px solid var(--gray-b);}
    .cc-icon svg{width:18px;height:18px;stroke:var(--red);}
    .cc-label{font-size:0.7rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.2rem;}
    .cc-val{font-size:0.88rem;font-weight:500;}
    .cc-sub{font-size:0.75rem;color:var(--muted);margin-top:0.15rem;}

    /* EMERGENCY BOX */
    .emergency-box{background:linear-gradient(135deg,#1A0A0B,var(--red-dark));border-radius:14px;padding:1.5rem;margin-top:1.5rem;position:relative;overflow:hidden;}
    .emergency-box::before{content:'';position:absolute;top:-20px;right:-20px;width:100px;height:100px;background:rgba(255,255,255,0.04);border-radius:50%;}
    .em-tag{font-size:0.68rem;font-weight:700;color:#FF8A8A;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.5rem;}
    .em-title{font-family:'Playfair Display',serif;font-size:1.1rem;font-weight:700;color:white;margin-bottom:0.4rem;}
    .em-text{font-size:0.8rem;color:rgba(255,255,255,0.6);line-height:1.6;margin-bottom:1rem;}
    .em-phone{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:700;color:white;}

    /* FORM */
    .form-panel{background:white;border-radius:16px;border:1px solid var(--gray-b);padding:2.5rem;box-shadow:0 8px 40px rgba(0,0,0,0.06);}
    .form-title{font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:700;margin-bottom:0.4rem;}
    .form-sub{font-size:0.83rem;color:var(--muted);margin-bottom:2rem;line-height:1.5;}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem;}
    .form-group{display:flex;flex-direction:column;gap:0.4rem;margin-bottom:1rem;}
    .form-group label{font-size:0.72rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;}
    .form-group label span{color:var(--red);}
    .form-group input,.form-group select,.form-group textarea{padding:0.65rem 0.9rem;border:1.5px solid var(--gray-b);border-radius:9px;font-family:'DM Sans',sans-serif;font-size:0.85rem;color:var(--text);background:var(--off);outline:none;transition:all 0.2s;}
    .form-group input:focus,.form-group select:focus,.form-group textarea:focus{border-color:var(--red);background:white;box-shadow:0 0 0 3px rgba(200,25,42,0.08);}
    .form-group textarea{resize:vertical;min-height:120px;line-height:1.6;}
    .form-group .error{font-size:0.73rem;color:var(--red);margin-top:0.2rem;}
    .submit-btn{width:100%;padding:0.85rem;background:var(--red);color:white;border:none;border-radius:10px;font-family:'DM Sans',sans-serif;font-size:0.9rem;font-weight:600;cursor:pointer;transition:all 0.2s;display:flex;align-items:center;justify-content:center;gap:0.5rem;}
    .submit-btn:hover{background:var(--red-dark);transform:translateY(-1px);box-shadow:0 6px 20px rgba(200,25,42,0.3);}
    .submit-btn svg{width:16px;height:16px;}

    /* SUBJECT PILLS */
    .subject-pills{display:flex;flex-wrap:wrap;gap:0.5rem;margin-bottom:1.25rem;}
    .sp{padding:0.3rem 0.85rem;border-radius:20px;font-size:0.77rem;border:1.5px solid var(--gray-b);cursor:pointer;transition:all 0.2s;background:white;color:var(--muted);}
    .sp:hover,.sp.sel{background:var(--red-light);border-color:var(--red-mid);color:var(--red);}
    .sp-label{font-size:0.68rem;font-weight:600;color:var(--muted);text-transform:uppercase;letter-spacing:0.07em;margin-bottom:0.5rem;}

    /* SUCCESS ALERT */
    .alert-success{background:var(--green-l);border:1px solid #BBF7D0;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.85rem;color:var(--green);display:flex;align-items:center;gap:0.6rem;}
    .alert-success svg{width:16px;height:16px;flex-shrink:0;}
    .alert-error{background:var(--red-light);border:1px solid var(--red-mid);border-radius:10px;padding:1rem 1.25rem;margin-bottom:1.5rem;font-size:0.82rem;color:var(--red-dark);}
    .alert-error ul{padding-left:1.2rem;margin-top:0.3rem;}
    .alert-error li{margin-bottom:0.2rem;}

    /* MAP PLACEHOLDER */
    .map-section{padding:0 5% 80px;}
    .map-wrap{max-width:1100px;margin:0 auto;background:var(--off);border-radius:16px;border:1px solid var(--gray-b);padding:3rem;text-align:center;}
    .map-wrap h3{font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;margin-bottom:0.5rem;}
    .map-wrap p{font-size:0.85rem;color:var(--muted);margin-bottom:1.5rem;}
    .location-pills{display:flex;flex-wrap:wrap;gap:0.75rem;justify-content:center;}
    .lp{padding:0.45rem 1rem;background:white;border:1px solid var(--gray-b);border-radius:8px;font-size:0.8rem;color:var(--text);display:flex;align-items:center;gap:0.4rem;}
    .lp::before{content:'📍';font-size:0.75rem;}

    /* FOOTER */
    footer{background:#1A0A0B;padding:3rem 5% 2rem;}
    .footer-inner{max-width:1100px;margin:0 auto;}
    .footer-grid{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:3rem;margin-bottom:2.5rem;}
    .footer-brand p{font-size:0.82rem;color:rgba(255,255,255,0.45);line-height:1.7;margin-top:0.75rem;}
    .footer-col h4{font-size:0.72rem;font-weight:600;color:rgba(255,255,255,0.4);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:1rem;}
    .footer-col a{display:block;font-size:0.82rem;color:rgba(255,255,255,0.55);text-decoration:none;margin-bottom:0.5rem;transition:color 0.2s;}
    .footer-col a:hover{color:white;}
    .footer-bottom{border-top:1px solid rgba(255,255,255,0.08);padding-top:1.5rem;display:flex;justify-content:space-between;align-items:center;}
    .footer-copy{font-size:0.75rem;color:rgba(255,255,255,0.3);}

    @keyframes fadeUp{from{opacity:0;transform:translateY(24px);}to{opacity:1;transform:translateY(0);}}
    .hero > *{animation:fadeUp 0.6s ease both;}

    @media(max-width:768px){
      .contact-grid,.footer-grid,.form-row{grid-template-columns:1fr;}
      .nav-links{display:none;}
    }
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
    <a href="/find-donors">Find Donors</a>
    <a href="/contact" class="active">Contact</a>
    <a href="/privacy">Privacy</a>
  </div>
  <div class="nav-btns">
    <a href="{{ route('donor.login') }}" class="btn-ghost">Sign In</a>
    <a href="{{ route('donor.register') }}" class="btn-primary">Register as Donor</a>
  </div>
</nav>

<!-- HERO -->
<div class="hero">
  <div class="hero-tag"><span></span> Get in Touch</div>
  <h1>We're here to <em>help</em></h1>
  <p>Have a question, a partnership proposal, or need urgent blood donor support? Reach out — we respond within 24 hours.</p>
</div>

<!-- MAIN SECTION -->
<div class="main-section">
  <div class="section-inner">
    <div class="contact-grid">

      <!-- LEFT: Info -->
      <div class="info-panel">
        <h2>Contact LifeLink</h2>
        <p>Whether you're a donor, hospital administrator, or researcher — we want to hear from you.</p>

        <div class="contact-cards">
          <div class="contact-card">
            <div class="cc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg></div>
            <div>
              <div class="cc-label">Email</div>
              <div class="cc-val">support@lifelink.lk</div>
              <div class="cc-sub">General enquiries & technical support</div>
            </div>
          </div>
          <div class="contact-card">
            <div class="cc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013.07 9.8 19.79 19.79 0 01.22 1.18 2 2 0 012.18 0h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L6.91 7.91a16 16 0 006.16 6.16l1.27-.63a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z"/></svg></div>
            <div>
              <div class="cc-label">Phone</div>
              <div class="cc-val">+94 11 269 3000</div>
              <div class="cc-sub">Mon–Fri, 8:00 AM – 5:00 PM</div>
            </div>
          </div>
          <div class="contact-card">
            <div class="cc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><path d="M21 10c0 7-9 13-9 13S3 17 3 10a9 9 0 0118 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div>
              <div class="cc-label">Address</div>
              <div class="cc-val">National Blood Transfusion Service</div>
              <div class="cc-sub">Baseline Road, Colombo 08, Sri Lanka</div>
            </div>
          </div>
          <div class="contact-card">
            <div class="cc-icon"><svg viewBox="0 0 24 24" fill="none" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg></div>
            <div>
              <div class="cc-label">Response Time</div>
              <div class="cc-val">Within 24 hours</div>
              <div class="cc-sub">We aim to respond to all messages quickly</div>
            </div>
          </div>
        </div>

        <div class="emergency-box">
          <div class="em-tag">🚨 Blood Emergency</div>
          <div class="em-title">Need blood urgently?</div>
          <div class="em-text">For critical blood requests, contact the NBTS emergency line directly. Hospitals can submit urgent requests through the Hospital Dashboard.</div>
          <div class="em-phone">1990</div>
        </div>
      </div>

      <!-- RIGHT: Form -->
      <div class="form-panel">
        <div class="form-title">Send us a message</div>
        <div class="form-sub">Fill in the form below and our team will get back to you shortly.</div>

        @if(session('contact_success'))
        <div class="alert-success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
          Thank you! Your message has been sent. We'll reply within 24 hours.
        </div>
        @endif

        @if($errors->any())
        <div class="alert-error">
          <strong>Please fix the following:</strong>
          <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
        </div>
        @endif

        <form method="POST" action="/contact/send">
          @csrf

          <div class="form-row">
            <div class="form-group">
              <label>First Name <span>*</span></label>
              <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Kasun">
              @error('first_name')<div class="error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
              <label>Last Name <span>*</span></label>
              <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Perera">
              @error('last_name')<div class="error">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="form-group">
            <label>Email Address <span>*</span></label>
            <input type="email" name="email" value="{{ old('email') }}" placeholder="kasun@example.com">
            @error('email')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label>Phone (Optional)</label>
            <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="+94 71 234 5678">
          </div>

          <div class="form-group">
            <label>Your Role</label>
            <select name="role">
              <option value="">Select your role</option>
              <option value="donor"     {{ old('role')==='donor'     ?'selected':'' }}>Blood Donor</option>
              <option value="hospital"  {{ old('role')==='hospital'  ?'selected':'' }}>Hospital / Medical Staff</option>
              <option value="researcher"{{ old('role')==='researcher'?'selected':'' }}>Researcher / Academic</option>
              <option value="other"     {{ old('role')==='other'     ?'selected':'' }}>Other</option>
            </select>
          </div>

          <div class="form-group">
            <div class="sp-label">Subject</div>
            <div class="subject-pills">
              @foreach(['General Enquiry','Technical Support','Hospital Partnership','Donor Registration Help','Research Collaboration','Blood Emergency','Feedback'] as $subj)
              <div class="sp {{ old('subject')===$subj?'sel':'' }}" onclick="selectSubject(this,'{{ $subj }}')">{{ $subj }}</div>
              @endforeach
            </div>
            <input type="hidden" name="subject" id="subjectInput" value="{{ old('subject') }}">
            @error('subject')<div class="error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group">
            <label>Message <span>*</span></label>
            <textarea name="message" placeholder="Tell us how we can help you…">{{ old('message') }}</textarea>
            @error('message')<div class="error">{{ $message }}</div>@enderror
          </div>

          <button type="submit" class="submit-btn">
            Send Message
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
          </button>
        </form>
      </div>

    </div>
  </div>
</div>

<!-- NBTS LOCATIONS -->
<div class="map-section">
  <div class="map-wrap">
    <h3>NBTS Donation Centres</h3>
    <p>Find your nearest National Blood Transfusion Service centre across Sri Lanka.</p>
    <div class="location-pills">
      @foreach(['Colombo (Baseline Rd)','Kandy (Lady Perera Rd)','Galle (Karapitiya)','Jaffna (Hospital Rd)','Kurunegala (Dambulla Rd)','Anuradhapura (Maithripala Rd)','Batticaloa','Ratnapura','Badulla','Trincomalee'] as $loc)
      <div class="lp">{{ $loc }}</div>
      @endforeach
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
          <span class="logo-text" style="color:white;">LifeLink</span>
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

<script>
function selectSubject(el, val) {
  document.querySelectorAll('.sp').forEach(s => s.classList.remove('sel'));
  el.classList.add('sel');
  document.getElementById('subjectInput').value = val;
}
// Restore on old() reload
const saved = document.getElementById('subjectInput').value;
if (saved) {
  document.querySelectorAll('.sp').forEach(s => {
    if (s.textContent.trim() === saved) s.classList.add('sel');
  });
}
</script>

</body>
</html>