
<style>
    :root {
        --primary: #1D4ED8;
        --primary-dark: #1E3A8A;
        --primary-light: #3B82F6;
        --white: #FFFFFF;
        --off-white: #F8FAFC;
        --gray-light: #F1F5F9;
        --gray: #888;
        --text: #1E293B;
        --text-soft: #555;
    }

    * { box-sizing: border-box; margin: 0; padding: 0; }

    body {
        font-family: 'Georgia', serif;
        background: var(--off-white);
        color: var(--text);
    }

    .privacy-hero {
        background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 60%, #172554 100%);
        padding: 80px 20px 60px;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .privacy-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }

    .privacy-hero .badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255,255,255,0.15);
        border: 1px solid rgba(255,255,255,0.3);
        color: #fff;
        padding: 6px 18px;
        border-radius: 30px;
        font-size: 0.8rem;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 20px;
    }

    .privacy-hero h1 {
        font-size: clamp(2rem, 5vw, 3.2rem);
        color: #fff;
        font-weight: 700;
        letter-spacing: -0.5px;
        margin-bottom: 12px;
    }

    .privacy-hero p {
        color: rgba(255,255,255,0.75);
        font-size: 1rem;
        max-width: 520px;
        margin: 0 auto;
    }

    .privacy-hero .meta {
        margin-top: 24px;
        display: flex;
        justify-content: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .privacy-hero .meta span {
        color: rgba(255,255,255,0.65);
        font-size: 0.82rem;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .privacy-layout {
        max-width: 1100px;
        margin: 0 auto;
        padding: 60px 20px;
        display: grid;
        grid-template-columns: 240px 1fr;
        gap: 48px;
        align-items: start;
    }

    @media (max-width: 768px) {
        .privacy-layout {
            grid-template-columns: 1fr;
            gap: 32px;
        }
        .sidebar { display: none; }
    }

    .sidebar {
        position: sticky;
        top: 24px;
    }

    .toc-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        overflow: hidden;
    }

    .toc-card .toc-head {
        background: var(--primary);
        color: #fff;
        padding: 14px 18px;
        font-size: 0.78rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        font-weight: 700;
        font-family: 'Georgia', serif;
    }

    .toc-card ul {
        list-style: none;
        padding: 10px 0;
    }

    .toc-card ul li a {
        display: block;
        padding: 9px 18px;
        color: var(--text-soft);
        text-decoration: none;
        font-size: 0.85rem;
        border-left: 3px solid transparent;
        transition: all 0.2s;
        line-height: 1.4;
    }

    .toc-card ul li a:hover,
    .toc-card ul li a.active {
        color: var(--primary);
        border-left-color: var(--primary);
        background: #EFF6FF;
    }

    .privacy-content section {
        margin-bottom: 48px;
        scroll-margin-top: 80px;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 20px;
        padding-bottom: 14px;
        border-bottom: 2px solid #E2E8F0;
    }

    .section-icon {
        width: 42px;
        height: 42px;
        background: linear-gradient(135deg, var(--primary), var(--primary-light));
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 1.1rem;
        flex-shrink: 0;
    }

    .section-header h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: var(--text);
    }

    .section-num {
        font-size: 0.72rem;
        color: var(--primary);
        font-weight: 700;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: block;
    }

    .privacy-content p {
        color: var(--text-soft);
        line-height: 1.8;
        margin-bottom: 14px;
        font-size: 0.95rem;
    }

    .privacy-content ul {
        list-style: none;
        margin: 0 0 16px 0;
        padding: 0;
    }

    .privacy-content ul li {
        padding: 7px 0 7px 22px;
        position: relative;
        color: var(--text-soft);
        font-size: 0.95rem;
        line-height: 1.6;
        border-bottom: 1px dashed #E2E8F0;
    }

    .privacy-content ul li:last-child { border-bottom: none; }

    .privacy-content ul li::before {
        content: '▸';
        position: absolute;
        left: 0;
        color: var(--primary);
        font-size: 0.75rem;
        top: 9px;
    }

    .highlight-box {
        background: linear-gradient(135deg, #EFF6FF, #F8FAFC);
        border: 1px solid rgba(29,78,216,0.15);
        border-left: 4px solid var(--primary);
        border-radius: 8px;
        padding: 18px 20px;
        margin: 20px 0;
    }

    .highlight-box p {
        margin: 0;
        font-size: 0.92rem;
        color: var(--text);
    }

    .highlight-box strong { color: var(--primary); }

    .contact-card {
        background: linear-gradient(135deg, var(--primary-dark), var(--primary));
        border-radius: 14px;
        padding: 30px;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 24px;
        flex-wrap: wrap;
    }

    .contact-card .icon-wrap {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.15);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        flex-shrink: 0;
    }

    .contact-card h3 {
        font-size: 1.1rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    .contact-card p {
        color: rgba(255,255,255,0.75);
        font-size: 0.9rem;
        margin: 0 0 12px;
    }

    .contact-card a {
        display: inline-block;
        background: #fff;
        color: var(--primary);
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        transition: opacity 0.2s;
    }

    .contact-card a:hover { opacity: 0.9; }

    .back-top {
        text-align: center;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 1px solid #E2E8F0;
    }

    .back-top a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary);
        text-decoration: none;
        font-size: 0.88rem;
        font-weight: 600;
        transition: gap 0.2s;
    }

    .back-top a:hover { gap: 12px; }
</style>

{{-- Hero --}}
<section class="privacy-hero">
    <div class="badge">📜 Legal</div>
    <h1>Terms of Service</h1>
    <p>The terms that govern your use of LifeLink as a donor, hospital, or administrator.</p>
    <div class="meta">
        <span>📅 Last updated: January 2025</span>
        <span>📄 Version 1.0</span>
        <span>🌐 Applies to: lifelink.lk</span>
    </div>
</section>

{{-- Layout --}}
<div class="privacy-layout">

    <aside class="sidebar">
        <div class="toc-card">
            <div class="toc-head">📋 Contents</div>
            <ul>
                <li><a href="#acceptance">1. Acceptance of Terms</a></li>
                <li><a href="#eligibility">2. Eligibility & Accounts</a></li>
                <li><a href="#donor">3. Donor Responsibilities</a></li>
                <li><a href="#hospital">4. Hospital Responsibilities</a></li>
                <li><a href="#ai">5. AI-Assisted Matching Disclaimer</a></li>
                <li><a href="#prohibited">6. Prohibited Uses</a></li>
                <li><a href="#liability">7. Limitation of Liability</a></li>
                <li><a href="#termination">8. Termination</a></li>
                <li><a href="#law">9. Governing Law</a></li>
                <li><a href="#changes">10. Changes to These Terms</a></li>
                <li><a href="#contact">11. Contact Us</a></li>
            </ul>
        </div>
    </aside>

    <main class="privacy-content">

        <section id="acceptance">
            <div class="section-header">
                <div class="section-icon">✅</div>
                <div>
                    <span class="section-num">Section 01</span>
                    <h2>Acceptance of Terms</h2>
                </div>
            </div>

            <div class="highlight-box">
                <p><strong>Plain language summary:</strong> By creating an account or using LifeLink, you agree to these Terms. If you don't agree, please don't use the platform.</p>
            </div>

            <p>These Terms of Service ("Terms") govern your access to and use of LifeLink ("we", "us", "our"), a blood donor management platform serving donors, hospitals, and administrators across Sri Lanka.</p>
            <p>By registering a Donor, Hospital, or Administrator account, you confirm that you have read, understood, and agree to be bound by these Terms and by our <a href="{{ route('privacy') }}" style="color:var(--primary);">Privacy Policy</a>.</p>
        </section>

        <section id="eligibility">
            <div class="section-header">
                <div class="section-icon">👤</div>
                <div>
                    <span class="section-num">Section 02</span>
                    <h2>Eligibility & Accounts</h2>
                </div>
            </div>

            <ul>
                <li>You must be at least 18 years old to register as a donor, consistent with Sri Lankan blood donation age requirements.</li>
                <li>You must provide accurate, current information — your medical details directly affect the eligibility predictions and matches the platform generates.</li>
                <li>Hospital accounts are subject to admin verification before being marked as verified on the platform.</li>
                <li>You are responsible for keeping your login credentials confidential and for all activity under your account.</li>
            </ul>
        </section>

        <section id="donor">
            <div class="section-header">
                <div class="section-icon">💧</div>
                <div>
                    <span class="section-num">Section 03</span>
                    <h2>Donor Responsibilities</h2>
                </div>
            </div>

            <ul>
                <li>Keep your health profile (weight, hemoglobin, medical conditions, last donation date) up to date, since it directly drives your AI eligibility result.</li>
                <li>A donation you make outside the platform should still be recorded so your donation history and cooldown period stay accurate.</li>
                <li>Responding "Available" to a blood request is not a binding medical clearance — final eligibility is always confirmed by clinical staff at the point of donation.</li>
            </ul>

            <div class="highlight-box">
                <p><strong>Important:</strong> LifeLink's AI eligibility check is a pre-screening convenience, not a substitute for a clinical interview and testing at the donation centre.</p>
            </div>
        </section>

        <section id="hospital">
            <div class="section-header">
                <div class="section-icon">🏥</div>
                <div>
                    <span class="section-num">Section 04</span>
                    <h2>Hospital Responsibilities</h2>
                </div>
            </div>

            <ul>
                <li>Submit blood requests accurately — blood group, units, urgency, and ward — so donor matching and forecasting stay meaningful.</li>
                <li>Contact matched donors respectfully, and only for the purpose of the blood request they were matched against.</li>
                <li>Mark a request as fulfilled once resolved, so the platform's shortage and demand data stays accurate for other hospitals.</li>
            </ul>
        </section>

        <section id="ai">
            <div class="section-header">
                <div class="section-icon">🤖</div>
                <div>
                    <span class="section-num">Section 05</span>
                    <h2>AI-Assisted Matching Disclaimer</h2>
                </div>
            </div>

            <p>LifeLink uses machine learning models — evaluated and reported honestly, with real accuracy figures published on the platform — to predict donor eligibility, rank compatible donors, and forecast blood demand.</p>
            <ul>
                <li>These predictions are decision-support tools, not guarantees. A donor shown as "eligible" may still be found unsuitable during clinical screening.</li>
                <li>Demand and shortage forecasts are estimates based on historical request data and should not replace a hospital's own clinical judgement.</li>
                <li>If the AI service is temporarily unavailable, the platform falls back to simple rule-based logic, clearly labelled as a fallback rather than an AI result.</li>
            </ul>
        </section>

        <section id="prohibited">
            <div class="section-header">
                <div class="section-icon">🚫</div>
                <div>
                    <span class="section-num">Section 06</span>
                    <h2>Prohibited Uses</h2>
                </div>
            </div>

            <ul>
                <li>Creating a donor, hospital, or admin account with false identity or health information.</li>
                <li>Using the platform to harass, spam, or contact donors or hospitals for any purpose unrelated to a genuine blood request.</li>
                <li>Attempting to access another user's account, another role's protected pages, or to bypass authentication.</li>
                <li>Scraping, reverse-engineering, or reselling data obtained through the platform.</li>
            </ul>
        </section>

        <section id="liability">
            <div class="section-header">
                <div class="section-icon">⚖️</div>
                <div>
                    <span class="section-num">Section 07</span>
                    <h2>Limitation of Liability</h2>
                </div>
            </div>

            <p>LifeLink is provided as an academic prototype. While every effort is made to keep AI predictions, donor data, and matching logic accurate, we do not guarantee uninterrupted availability or clinical-grade accuracy of any prediction. LifeLink is not liable for outcomes arising from reliance on AI-generated eligibility, matching, or forecasting results without independent clinical verification.</p>
        </section>

        <section id="termination">
            <div class="section-header">
                <div class="section-icon">🔒</div>
                <div>
                    <span class="section-num">Section 08</span>
                    <h2>Termination</h2>
                </div>
            </div>

            <p>You may request deletion of your account at any time. We may suspend or terminate an account that violates these Terms, provides false information, or is used for a prohibited purpose described in Section 6.</p>
        </section>

        <section id="law">
            <div class="section-header">
                <div class="section-icon">🏛️</div>
                <div>
                    <span class="section-num">Section 09</span>
                    <h2>Governing Law</h2>
                </div>
            </div>

            <p>These Terms are governed by the laws of Sri Lanka, consistent with LifeLink's design for the Sri Lankan healthcare context.</p>
        </section>

        <section id="changes">
            <div class="section-header">
                <div class="section-icon">🔄</div>
                <div>
                    <span class="section-num">Section 10</span>
                    <h2>Changes to These Terms</h2>
                </div>
            </div>

            <p>We may update these Terms from time to time. Significant changes will update the "Last updated" date above. Continued use of LifeLink after an update constitutes acceptance of the revised Terms.</p>
        </section>

        <section id="contact">
            <div class="section-header">
                <div class="section-icon">📬</div>
                <div>
                    <span class="section-num">Section 11</span>
                    <h2>Contact Us</h2>
                </div>
            </div>

            <p>Questions about these Terms can be directed to our team:</p>

            <div class="contact-card">
                <div class="icon-wrap">📜</div>
                <div>
                    <h3>LifeLink Support Team</h3>
                    <p>We aim to respond to all enquiries within 5 business days.</p>
                    <a href="{{ route('contact') }}">Contact Us →</a>
                </div>
            </div>
        </section>

        <div class="back-top">
            <a href="#top">↑ Back to top</a>
        </div>

    </main>
</div>

<script>
    const sections = document.querySelectorAll('section[id]');
    const tocLinks = document.querySelectorAll('.toc-card a');

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                tocLinks.forEach(a => a.classList.remove('active'));
                const active = document.querySelector(`.toc-card a[href="#${entry.target.id}"]`);
                if (active) active.classList.add('active');
            }
        });
    }, { rootMargin: '-30% 0px -60% 0px' });

    sections.forEach(s => observer.observe(s));
</script>
