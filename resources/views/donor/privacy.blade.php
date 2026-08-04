
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

    /* ── Hero ── */
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

    /* ── Layout ── */
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

    /* ── Sidebar TOC ── */
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

    /* ── Content ── */
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

    /* Highlight box */
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

    /* Data table */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin: 20px 0;
        font-size: 0.88rem;
    }

    .data-table th {
        background: var(--primary);
        color: #fff;
        padding: 11px 16px;
        text-align: left;
        font-weight: 600;
        font-family: 'Georgia', serif;
    }

    .data-table td {
        padding: 11px 16px;
        border-bottom: 1px solid #E2E8F0;
        color: var(--text-soft);
        vertical-align: top;
    }

    .data-table tr:nth-child(even) td { background: #F8FAFC; }
    .data-table tr:last-child td { border-bottom: none; }

    /* Rights grid */
    .rights-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin: 20px 0;
    }

    .right-card {
        background: #fff;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 18px;
        text-align: center;
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .right-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(29,78,216,0.1);
    }

    .right-card .icon {
        font-size: 1.8rem;
        margin-bottom: 10px;
    }

    .right-card h4 {
        font-size: 0.88rem;
        font-weight: 700;
        color: var(--text);
        margin-bottom: 6px;
    }

    .right-card p {
        font-size: 0.78rem;
        color: var(--gray);
        margin: 0;
        line-height: 1.5;
    }

    /* Contact card */
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

    /* Back to top */
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
    <div class="badge">🔒 Legal</div>
    <h1>Privacy Policy</h1>
    <p>How LifeLink collects, uses, and protects your personal and health information.</p>
    <div class="meta">
        <span>📅 Last updated: January 2025</span>
        <span>📄 Version 2.1</span>
        <span>🌐 Applies to: lifelink.lk</span>
    </div>
</section>

{{-- Layout --}}
<div class="privacy-layout">

    {{-- Sidebar TOC --}}
    <aside class="sidebar">
        <div class="toc-card">
            <div class="toc-head">📋 Contents</div>
            <ul>
                <li><a href="#overview">1. Overview</a></li>
                <li><a href="#collection">2. Data We Collect</a></li>
                <li><a href="#use">3. How We Use It</a></li>
                <li><a href="#sharing">4. Sharing & Disclosure</a></li>
                <li><a href="#security">5. Data Security</a></li>
                <li><a href="#retention">6. Data Retention</a></li>
                <li><a href="#rights">7. Your Rights</a></li>
                <li><a href="#cookies">8. Cookies</a></li>
                <li><a href="#children">9. Children</a></li>
                <li><a href="#changes">10. Policy Changes</a></li>
                <li><a href="#contact">11. Contact Us</a></li>
            </ul>
        </div>
    </aside>

    {{-- Main Content --}}
    <main class="privacy-content">

        {{-- 1. Overview --}}
        <section id="overview">
            <div class="section-header">
                <div class="section-icon">🛡️</div>
                <div>
                    <span class="section-num">Section 01</span>
                    <h2>Overview</h2>
                </div>
            </div>

            <div class="highlight-box">
                <p><strong>Plain language summary:</strong> LifeLink is a blood donor management platform. We collect your health and personal information only to connect donors with recipients and ensure safe blood donation. We never sell your data.</p>
            </div>

            <p>LifeLink ("we", "us", or "our") operates a blood donor management system that serves donors, healthcare facilities, and administrators across Sri Lanka. This Privacy Policy explains what personal data we collect, why we collect it, how it is used, and your rights regarding that data.</p>
            <p>By registering on LifeLink or using our services, you agree to the terms of this Privacy Policy. If you do not agree, please do not use our platform.</p>
        </section>

        {{-- 2. Data We Collect --}}
        <section id="collection">
            <div class="section-header">
                <div class="section-icon">📂</div>
                <div>
                    <span class="section-num">Section 02</span>
                    <h2>Data We Collect</h2>
                </div>
            </div>

            <p>We collect the following categories of information:</p>

            <table class="data-table">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Examples</th>
                        <th>Purpose</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Identity</strong></td>
                        <td>Full name, NIC number, date of birth</td>
                        <td>Donor verification & account</td>
                    </tr>
                    <tr>
                        <td><strong>Contact</strong></td>
                        <td>Phone number, email, district</td>
                        <td>Notifications & location matching</td>
                    </tr>
                    <tr>
                        <td><strong>Health</strong></td>
                        <td>Blood group, weight, hemoglobin, last donation date</td>
                        <td>Eligibility assessment</td>
                    </tr>
                    <tr>
                        <td><strong>Donation History</strong></td>
                        <td>Dates, locations, volume donated</td>
                        <td>Safety & scheduling</td>
                    </tr>
                    <tr>
                        <td><strong>Technical</strong></td>
                        <td>IP address, browser type, device info</td>
                        <td>Security & analytics</td>
                    </tr>
                </tbody>
            </table>

            <p>Health data is classified as <strong>sensitive personal data</strong> and is subject to additional protections under our data handling practices.</p>
        </section>

        {{-- 3. How We Use It --}}
        <section id="use">
            <div class="section-header">
                <div class="section-icon">⚙️</div>
                <div>
                    <span class="section-num">Section 03</span>
                    <h2>How We Use Your Data</h2>
                </div>
            </div>

            <p>Your data is used exclusively for the following purposes:</p>
            <ul>
                <li>Determining and displaying your blood donation eligibility status</li>
                <li>Matching eligible donors with urgent blood requests in your district</li>
                <li>Sending appointment reminders and post-donation follow-ups</li>
                <li>Generating anonymised public statistics (e.g., available donors by blood group)</li>
                <li>Admin oversight to maintain data quality and platform integrity</li>
                <li>Improving our AI-based eligibility prediction model</li>
                <li>Complying with applicable health regulations in Sri Lanka</li>
            </ul>

            <div class="highlight-box">
                <p><strong>We do not:</strong> sell your data, use it for advertising, share it with third-party marketers, or use it for any purpose outside blood donation services.</p>
            </div>
        </section>

        {{-- 4. Sharing --}}
        <section id="sharing">
            <div class="section-header">
                <div class="section-icon">🔗</div>
                <div>
                    <span class="section-num">Section 04</span>
                    <h2>Sharing & Disclosure</h2>
                </div>
            </div>

            <p>We may share your information only in the following limited circumstances:</p>
            <ul>
                <li><strong>Healthcare facilities:</strong> Hospitals and blood banks may see your blood group, eligibility status, and contact information when there is an urgent need matching your profile — only with your prior consent.</li>
                <li><strong>Service providers:</strong> We use trusted third-party services (e.g., SMS gateways, hosting) under strict data processing agreements.</li>
                <li><strong>Legal obligation:</strong> We may disclose data if required by Sri Lankan law, court order, or governmental authority.</li>
                <li><strong>Emergency situations:</strong> In life-threatening situations where donor matching is critical, limited data may be shared with medical professionals.</li>
            </ul>
            <p>We never share your NIC number, full donation history, or detailed health metrics with any public-facing interface.</p>
        </section>

        {{-- 5. Security --}}
        <section id="security">
            <div class="section-header">
                <div class="section-icon">🔐</div>
                <div>
                    <span class="section-num">Section 05</span>
                    <h2>Data Security</h2>
                </div>
            </div>

            <p>We implement industry-standard security measures to protect your data:</p>
            <ul>
                <li>All data is encrypted in transit using TLS 1.2+ (HTTPS)</li>
                <li>Sensitive fields (NIC, health data) are encrypted at rest</li>
                <li>Admin access is role-based with full audit logging</li>
                <li>Regular security reviews and vulnerability assessments</li>
                <li>Automatic session timeouts for authenticated users</li>
                <li>Passwords are hashed using bcrypt — we never store plain-text passwords</li>
            </ul>

            <div class="highlight-box">
                <p>If you suspect unauthorised access to your account, <strong>change your password immediately</strong> and contact us at <strong>security@lifelink.lk</strong>.</p>
            </div>
        </section>

        {{-- 6. Retention --}}
        <section id="retention">
            <div class="section-header">
                <div class="section-icon">🗓️</div>
                <div>
                    <span class="section-num">Section 06</span>
                    <h2>Data Retention</h2>
                </div>
            </div>

            <p>We retain your data for as long as your account is active or as needed to provide services:</p>
            <ul>
                <li><strong>Active donor accounts:</strong> Retained indefinitely while the account is active</li>
                <li><strong>Donation records:</strong> Retained for 7 years for health and regulatory compliance</li>
                <li><strong>Deleted accounts:</strong> Personal identifiers anonymised within 30 days of deletion request</li>
                <li><strong>Technical logs:</strong> Retained for 90 days then automatically deleted</li>
            </ul>
            <p>You may request deletion of your account at any time. Donation records may be retained in anonymised form for statistical and safety purposes.</p>
        </section>

        {{-- 7. Rights --}}
        <section id="rights">
            <div class="section-header">
                <div class="section-icon">⚖️</div>
                <div>
                    <span class="section-num">Section 07</span>
                    <h2>Your Rights</h2>
                </div>
            </div>

            <p>As a LifeLink user, you have the following rights over your personal data:</p>

            <div class="rights-grid">
                <div class="right-card">
                    <div class="icon">👁️</div>
                    <h4>Access</h4>
                    <p>Request a copy of all data we hold about you</p>
                </div>
                <div class="right-card">
                    <div class="icon">✏️</div>
                    <h4>Rectification</h4>
                    <p>Correct inaccurate or outdated information</p>
                </div>
                <div class="right-card">
                    <div class="icon">🗑️</div>
                    <h4>Erasure</h4>
                    <p>Request deletion of your account and personal data</p>
                </div>
                <div class="right-card">
                    <div class="icon">⏸️</div>
                    <h4>Restriction</h4>
                    <p>Limit how we process your data in certain situations</p>
                </div>
                <div class="right-card">
                    <div class="icon">📤</div>
                    <h4>Portability</h4>
                    <p>Export your data in a structured, readable format</p>
                </div>
                <div class="right-card">
                    <div class="icon">🚫</div>
                    <h4>Objection</h4>
                    <p>Object to processing for certain purposes</p>
                </div>
            </div>

            <p>To exercise any of these rights, contact us at <strong>privacy@lifelink.lk</strong>. We will respond within 30 days.</p>
        </section>

        {{-- 8. Cookies --}}
        <section id="cookies">
            <div class="section-header">
                <div class="section-icon">🍪</div>
                <div>
                    <span class="section-num">Section 08</span>
                    <h2>Cookies & Tracking</h2>
                </div>
            </div>

            <p>LifeLink uses the following types of cookies:</p>
            <ul>
                <li><strong>Essential cookies:</strong> Required for login sessions and security (cannot be disabled)</li>
                <li><strong>Preference cookies:</strong> Remember your language and display settings</li>
                <li><strong>Analytics cookies:</strong> Anonymous usage statistics to improve the platform (opt-out available)</li>
            </ul>
            <p>We do not use advertising cookies or third-party tracking pixels. You can manage cookie preferences in your browser settings. Disabling essential cookies will prevent you from logging in.</p>
        </section>

        {{-- 9. Children --}}
        <section id="children">
            <div class="section-header">
                <div class="section-icon">👦</div>
                <div>
                    <span class="section-num">Section 09</span>
                    <h2>Children's Privacy</h2>
                </div>
            </div>

            <p>LifeLink is intended for individuals aged <strong>18 and above</strong>, consistent with blood donation age requirements in Sri Lanka. We do not knowingly collect personal data from anyone under 18.</p>
            <p>If we discover that a user under 18 has registered, we will promptly delete their account and associated data. If you believe a minor has registered, please notify us at <strong>privacy@lifelink.lk</strong>.</p>
        </section>

        {{-- 10. Changes --}}
        <section id="changes">
            <div class="section-header">
                <div class="section-icon">🔄</div>
                <div>
                    <span class="section-num">Section 10</span>
                    <h2>Changes to This Policy</h2>
                </div>
            </div>

            <p>We may update this Privacy Policy from time to time. When we make significant changes, we will:</p>
            <ul>
                <li>Update the "Last updated" date at the top of this page</li>
                <li>Send an email notification to all registered users</li>
                <li>Display a notice on the platform for 30 days</li>
            </ul>
            <p>Continued use of LifeLink after a policy update constitutes acceptance of the revised terms. We encourage you to review this page periodically.</p>
        </section>

        {{-- 11. Contact --}}
        <section id="contact">
            <div class="section-header">
                <div class="section-icon">📬</div>
                <div>
                    <span class="section-num">Section 11</span>
                    <h2>Contact Us</h2>
                </div>
            </div>

            <p>For any privacy-related questions, data requests, or concerns, please reach out to our Data Protection team:</p>

            <div class="contact-card">
                <div class="icon-wrap">🛡️</div>
                <div>
                    <h3>LifeLink Data Protection Team</h3>
                    <p>We aim to respond to all privacy inquiries within 5 business days.</p>
                    <a href="{{ route('contact') }}">Contact Us →</a>
                </div>
                <div style="margin-left:auto; color:rgba(255,255,255,0.8); font-size:0.85rem; line-height:2;">
                    📧 privacy@lifelink.lk<br>
                    🔒 security@lifelink.lk<br>
                    📍 Colombo, Sri Lanka
                </div>
            </div>
        </section>

        {{-- Back to top --}}
        <div class="back-top">
            <a href="#top">↑ Back to top</a>
        </div>

    </main>
</div>


<script>
    // Highlight active TOC link on scroll
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
