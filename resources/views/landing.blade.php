@extends('layouts.app')

@section('content')
    {{-- ===================== HERO ===================== --}}
    <section class="hero">
        {{-- ===== Animated FX layer ===== --}}
        <div class="hero-fx" aria-hidden="true">
            {{-- Drifting clouds (D) --}}
            <svg class="hero-clouds" viewBox="0 0 1600 700" preserveAspectRatio="xMidYMid slice">
                <g class="cloud cloud-1" fill="rgba(255,255,255,0.18)">
                    <ellipse cx="80" cy="22" rx="80" ry="22"/>
                    <ellipse cx="40" cy="30" rx="42" ry="18"/>
                    <ellipse cx="120" cy="32" rx="50" ry="18"/>
                </g>
                <g class="cloud cloud-2" fill="rgba(255,255,255,0.12)">
                    <ellipse cx="80" cy="22" rx="80" ry="22"/>
                    <ellipse cx="40" cy="30" rx="42" ry="18"/>
                    <ellipse cx="120" cy="32" rx="50" ry="18"/>
                </g>
                <g class="cloud cloud-3" fill="rgba(255,255,255,0.15)">
                    <ellipse cx="80" cy="22" rx="80" ry="22"/>
                    <ellipse cx="40" cy="30" rx="42" ry="18"/>
                    <ellipse cx="120" cy="32" rx="50" ry="18"/>
                </g>
            </svg>

            {{-- Route line US -> Aruba with sliding plane (C) --}}
            <svg class="hero-route" viewBox="0 0 1600 700" preserveAspectRatio="xMidYMid slice">
                <defs>
                    <linearGradient id="routeGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="rgba(245,209,138,0)"/>
                        <stop offset="50%" stop-color="rgba(245,209,138,0.85)"/>
                        <stop offset="100%" stop-color="rgba(245,209,138,0)"/>
                    </linearGradient>
                </defs>

                <path d="M180,210 Q620,70 1420,420" fill="none"
                      stroke="rgba(245,209,138,0.28)" stroke-width="2"
                      stroke-dasharray="6 9" stroke-linecap="round"/>

                <path class="route-draw" d="M180,210 Q620,70 1420,420" fill="none"
                      stroke="url(#routeGrad)" stroke-width="3" stroke-linecap="round"/>

                <g transform="translate(180,210)">
                    <circle class="pin-pulse" r="6" fill="rgba(245,209,138,0.3)"/>
                    <circle r="7" fill="#f5d18a"/>
                    <circle r="3" fill="#0a1832"/>
                </g>
                <g transform="translate(1420,420)">
                    <circle class="pin-pulse" r="6" fill="rgba(199,153,86,0.35)"/>
                    <circle r="7" fill="#c79956"/>
                    <circle r="3" fill="#fff"/>
                </g>

                <path id="routeCurve" d="M180,210 Q620,70 1420,420" fill="none" stroke="none"/>
                <g class="route-plane">
                    <path d="M-12,-5 L16,0 L-12,5 L-7,0 Z" fill="#fff" stroke="#0a1832" stroke-width="0.6"/>
                    <animateMotion dur="9s" repeatCount="indefinite" rotate="auto">
                        <mpath href="#routeCurve"/>
                    </animateMotion>
                </g>
            </svg>

            {{-- Paper plane on its own curve (A) --}}
            <svg class="hero-paper" viewBox="0 0 1600 700" preserveAspectRatio="xMidYMid slice">
                <path id="paperCurve"
                      d="M-80,560 C260,300 640,520 980,360 S1500,140 1720,220"
                      fill="none" stroke="none"/>
                <path d="M-80,560 C260,300 640,520 980,360 S1500,140 1720,220"
                      fill="none" stroke="rgba(255,255,255,0.18)"
                      stroke-width="1.5" stroke-dasharray="2 7" stroke-linecap="round"/>
                <g class="paper-plane">
                    <path d="M0,0 L26,8 L0,16 L7,8 Z" fill="#f5d18a" stroke="#c79956" stroke-width="0.8"/>
                    <animateMotion dur="13s" repeatCount="indefinite" rotate="auto" begin="-2s">
                        <mpath href="#paperCurve"/>
                    </animateMotion>
                </g>
            </svg>

            {{-- Floating cargo boxes (B) --}}
            <div class="cargo-box cargo-1">
                <svg viewBox="0 0 64 64">
                    <polygon points="32,6 58,18 58,44 32,58 6,44 6,18" fill="#c79956" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,6 58,18 32,30 6,18" fill="#e0b97c" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,30 32,58 6,44 6,18" fill="#a87f3f" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <line x1="32" y1="30" x2="32" y2="58" stroke="#0a1832" stroke-width="0.8"/>
                </svg>
            </div>
            <div class="cargo-box cargo-2">
                <svg viewBox="0 0 64 64">
                    <polygon points="32,6 58,18 58,44 32,58 6,44 6,18" fill="#c79956" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,6 58,18 32,30 6,18" fill="#e0b97c" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,30 32,58 6,44 6,18" fill="#a87f3f" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
            </div>
            <div class="cargo-box cargo-3">
                <svg viewBox="0 0 64 64">
                    <polygon points="32,6 58,18 58,44 32,58 6,44 6,18" fill="#c79956" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,6 58,18 32,30 6,18" fill="#e0b97c" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                    <polygon points="32,30 32,58 6,44 6,18" fill="#a87f3f" stroke="#0a1832" stroke-width="1.2" stroke-linejoin="round"/>
                </svg>
            </div>
        </div>

        <div class="hero-grid">
            <div class="hero-copy" data-reveal>
                <span class="hero-badge">
                    <span class="dot"></span> Trusted forwarding partner since 2018
                </span>
                <h1>Ship from the US to <span class="text-accent">Aruba</span>, the smarter way.</h1>
                <p class="lead">
                    Consolidation-first package forwarding with transparent pricing, dedicated client
                    and admin portals, and a workflow built for speed, accuracy, and peace of mind.
                </p>
                <div class="hero-actions">
                    <a class="btn btn-accent" href="{{ route('register') }}">
                        Get My Free Suite
                        <svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h12"/><path d="M11 5l5 5-5 5"/></svg>
                    </a>
                    <a class="btn btn-light" href="#how-it-works">See How It Works</a>
                </div>
                <ul class="hero-bullets">
                    <li>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Free signup &amp; instant suite
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Save up to 60%
                    </li>
                    <li>
                        <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                        Real-time tracking
                    </li>
                </ul>
            </div>

            <div class="hero-art" aria-hidden="true" data-reveal>
                <span class="blob one"></span>
                <span class="blob two"></span>

                <div class="hero-card stack-top">
                    <div class="hc-head">
                        <span class="hc-eyebrow">Live shipment</span>
                        <span class="hc-status">In transit</span>
                    </div>
                    <div class="hc-route">
                        <div class="hc-place">
                            <strong>Miami, FL</strong>
                            <span>Origin · Suite S2A-1041</span>
                        </div>
                        <div class="hc-line">
                            <span class="dash"></span>
                            <span class="plane">
                                <svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M2 16l20-8-7 14-3-7-10-1z"/></svg>
                            </span>
                            <span class="dash"></span>
                        </div>
                        <div class="hc-place right">
                            <strong>Oranjestad, AW</strong>
                            <span>Destination</span>
                        </div>
                    </div>
                    <div class="hc-progress">
                        <span style="width: 68%;"></span>
                    </div>
                    <div class="hc-foot">
                        <span><strong>3</strong> packages consolidated</span>
                        <span>ETA <strong>2 days</strong></span>
                    </div>
                </div>

                <div class="hero-card stack-mid">
                    <div class="mini-row">
                        <span class="mini-dot green"></span>
                        <div>
                            <strong>Invoice approved</strong>
                            <span>Package #S2A-PKG-0042</span>
                        </div>
                        <span class="mini-time">2m</span>
                    </div>
                    <div class="mini-row">
                        <span class="mini-dot gold"></span>
                        <div>
                            <strong>Ready for pickup</strong>
                            <span>Order #S2A-1041</span>
                        </div>
                        <span class="mini-time">1h</span>
                    </div>
                </div>

                <div class="hero-card stack-bottom">
                    <div class="rating">
                        <div class="stars">★★★★★</div>
                        <strong>4.9</strong>
                        <span>· 2,000+ reviews</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="trust-strip" data-reveal data-counters>
            <div class="trust-item">
                <h3><span data-counter="100">0</span>k+</h3>
                <p>Packages shipped</p>
            </div>
            <div class="trust-item">
                <h3>40&ndash;<span data-counter="60">0</span>%</h3>
                <p>Average savings</p>
            </div>
            <div class="trust-item">
                <h3>5&ndash;<span data-counter="7">0</span> days</h3>
                <p>Priority delivery</p>
            </div>
            <div class="trust-item">
                <h3><span data-counter="4.9" data-decimals="1">0</span> / 5</h3>
                <p>Customer rating</p>
            </div>
        </div>
    </section>

    {{-- ===================== FEATURES ===================== --}}
    <section id="features" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">Why Ship2Aruba</span>
            <h2>Built for reliable, end-to-end forwarding</h2>
            <p>From warehouse intake to client pickup, every step is designed to be transparent, fast, and accurate.</p>
        </div>

        <div class="grid cols-3">
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-blue">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7H14a3.5 3.5 0 0 1 0 7H6"/></svg>
                </div>
                <h3>Transparent shipping rates</h3>
                <p>Predictable costs with no hidden fees and clear invoicing on every shipment.</p>
            </article>
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-gold">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
                </div>
                <h3>Consolidation &amp; repacking</h3>
                <p>Combine multiple deliveries into one optimized shipment to lower cost and risk.</p>
            </article>
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-teal">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
                </div>
                <h3>Real-time status tracking</h3>
                <p>Full lifecycle visibility for clients and operations — from intake to shipped.</p>
            </article>
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-plum">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="9"/></svg>
                </div>
                <h3>Fast invoice workflow</h3>
                <p>Invoice approvals and ship requests move quickly through our staff queues.</p>
            </article>
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-rose">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l8 4v6c0 5-3.5 8.5-8 10-4.5-1.5-8-5-8-10V6l8-4z"/></svg>
                </div>
                <h3>Secure warehouse storage</h3>
                <p>Hold packages safely until you're ready to ship, with full insurance coverage.</p>
            </article>
            <article class="card feature-card" data-reveal>
                <div class="icon-wrap tint-amber">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.4 8.4 0 0 1-1 4 8.5 8.5 0 0 1-7.5 4.5 8.4 8.4 0 0 1-4-1L3 21l2-5.5a8.4 8.4 0 0 1-1-4 8.5 8.5 0 0 1 4.5-7.5 8.4 8.4 0 0 1 4-1A8.5 8.5 0 0 1 21 11.5z"/></svg>
                </div>
                <h3>Dedicated customer support</h3>
                <p>Friendly, knowledgeable support for smooth and stress-free shipping.</p>
            </article>
        </div>
    </section>

    {{-- ===================== HOW IT WORKS (Timeline) ===================== --}}
    <section id="how-it-works" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">How It Works</span>
            <h2>A simple four-step journey</h2>
            <p>From sign-up to delivery, the workflow is designed for both customers and our internal teams.</p>
        </div>

        <div class="how-timeline">
            <div class="how-line" aria-hidden="true"></div>

            <article class="how-step" data-reveal>
                <div class="how-bubble">
                    <span class="how-num">01</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/></svg>
                </div>
                <h3>Sign up &amp; get suite</h3>
                <p>Create your account and instantly receive a US suite address for online shopping.</p>
            </article>

            <article class="how-step" data-reveal>
                <div class="how-bubble">
                    <span class="how-num">02</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M6 7h12l-1 13H7L6 7z"/><path d="M9 7V5a3 3 0 0 1 6 0v2"/></svg>
                </div>
                <h3>Shop online</h3>
                <p>Order from your favorite US retailers using your dedicated forwarding address.</p>
            </article>

            <article class="how-step" data-reveal>
                <div class="how-bubble">
                    <span class="how-num">03</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8L12 3 3 8l9 5 9-5z"/><path d="M3 8v8l9 5 9-5V8"/></svg>
                </div>
                <h3>Consolidate &amp; repack</h3>
                <p>We securely combine packages into one optimized shipment to save you money.</p>
            </article>

            <article class="how-step" data-reveal>
                <div class="how-bubble">
                    <span class="how-num">04</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                </div>
                <h3>Ship &amp; deliver</h3>
                <p>Submit a ship request and pick up your shipment in Aruba within days.</p>
            </article>
        </div>
    </section>

    {{-- ===================== WHY US (Comparison) ===================== --}}
    <section id="why-us" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">Smarter Shipping</span>
            <h2>Why Ship2Aruba beats direct shipping</h2>
            <p>Most US retailers don't ship to Aruba — and those that do charge a premium. We change that.</p>
        </div>

        <div class="compare-card" data-reveal>
            <div class="compare-head">
                <span></span>
                <div class="col-head us">Ship2Aruba</div>
                <div class="col-head them">Traditional shipping</div>
            </div>

            <div class="compare-row">
                <div class="row-label">Average shipping cost</div>
                <div class="cell good">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    40–60% lower
                </div>
                <div class="cell bad">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Full retail rates
                </div>
            </div>

            <div class="compare-row">
                <div class="row-label">Consolidation</div>
                <div class="cell good">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Combine multiple packages
                </div>
                <div class="cell bad">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Pay per package
                </div>
            </div>

            <div class="compare-row">
                <div class="row-label">Tracking</div>
                <div class="cell good">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Real-time status updates
                </div>
                <div class="cell bad">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Limited or none
                </div>
            </div>

            <div class="compare-row">
                <div class="row-label">Customer support</div>
                <div class="cell good">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Dedicated team
                </div>
                <div class="cell bad">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Generic carrier helpdesk
                </div>
            </div>

            <div class="compare-row">
                <div class="row-label">Storage</div>
                <div class="cell good">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    30 days free
                </div>
                <div class="cell bad">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
                    Not available
                </div>
            </div>
        </div>
    </section>

    {{-- ===================== PRICING ===================== --}}
    <section id="pricing" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">Service Plans</span>
            <h2>Transparent plans for every shipment</h2>
            <p>No hidden fees. Choose the service that suits your shipping needs.</p>
        </div>

        <div class="grid cols-3">
            <div class="card pricing-card" data-reveal>
                <h3>Package Consolidation</h3>
                <p>Combine multiple packages into one shipment.</p>
                <div class="price-row"><span class="price">Free</span><span class="per">/ included</span></div>
                <ul>
                    <li>Professional repacking</li>
                    <li>Secure handling</li>
                    <li>Save up to 40–60%</li>
                    <li>Email status updates</li>
                </ul>
                <a class="btn btn-light" href="{{ route('register') }}">Get started</a>
            </div>
            <div class="card pricing-card featured" data-reveal>
                <span class="ribbon">Most Popular</span>
                <h3>Priority Plus</h3>
                <p>Fast delivery with insured tracking.</p>
                <div class="price-row"><span class="price">Free</span><span class="per">/ included</span></div>
                <ul>
                    <li>5–7 days ready for pickup</li>
                    <li>Insured packages</li>
                    <li>Real-time tracking</li>
                    <li>Priority customer support</li>
                </ul>
                <a class="btn btn-accent" href="{{ route('register') }}">Choose Plan</a>
            </div>
            <div class="card pricing-card" data-reveal>
                <h3>Storage Service</h3>
                <p>Hold packages until you're ready to ship.</p>
                <div class="price-row"><span class="price">Free*</span><span class="per">/ first 30 days</span></div>
                <ul>
                    <li>30 days free storage</li>
                    <li>Secure facility</li>
                    <li>Photo confirmation</li>
                    <li>Flexible ship-out timing</li>
                </ul>
                <a class="btn btn-light" href="{{ route('register') }}">Get started</a>
            </div>
        </div>
    </section>

    {{-- ===================== TESTIMONIALS ===================== --}}
    <section id="testimonials" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">Testimonials</span>
            <h2>Loved by customers across Aruba</h2>
            <p>Real feedback from people shipping frequently from the US to Aruba.</p>
        </div>

        <div class="testimonial-slider" data-slider>
            <div class="slides-window">
                <div class="slides-track" data-track>
                    <article class="slide">
                        <div class="stars">★★★★★</div>
                        <p>"Excellent service! Fast and reliable shipping to Aruba. The team is very professional and responsive at every step."</p>
                        <div class="person">
                            <div class="avatar">NH</div>
                            <div><h3>Naheem Heyliger</h3><span>5 months ago</span></div>
                        </div>
                    </article>
                    <article class="slide">
                        <div class="stars">★★★★★</div>
                        <p>"Great consolidation service! I saved hundreds on shipping costs and the process was simple from start to finish."</p>
                        <div class="person">
                            <div class="avatar">MS</div>
                            <div><h3>Maria Santos</h3><span>3 months ago</span></div>
                        </div>
                    </article>
                    <article class="slide">
                        <div class="stars">★★★★★</div>
                        <p>"They are reliable, affordable, and always helpful. I always get timely updates and great support whenever needed."</p>
                        <div class="person">
                            <div class="avatar">CR</div>
                            <div><h3>Carlos Rodriguez</h3><span>6 months ago</span></div>
                        </div>
                    </article>
                    <article class="slide">
                        <div class="stars">★★★★★</div>
                        <p>"Best shipping experience to Aruba. Consolidation saved me a lot and customer service is consistently excellent."</p>
                        <div class="person">
                            <div class="avatar">SJ</div>
                            <div><h3>Sarah Johnson</h3><span>4 months ago</span></div>
                        </div>
                    </article>
                </div>
            </div>

            <div class="slider-controls">
                <button class="slider-btn prev" type="button" data-prev aria-label="Previous">
                    <svg viewBox="0 0 20 20" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 4l-6 6 6 6"/></svg>
                </button>
                <button class="slider-btn next" type="button" data-next aria-label="Next">
                    <svg viewBox="0 0 20 20" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 4l6 6-6 6"/></svg>
                </button>
            </div>
        </div>
        <div class="slider-dots" data-dots></div>
    </section>

    {{-- ===================== FAQ ===================== --}}
    <section id="faq" class="section">
        <div class="section-head" data-reveal>
            <span class="eyebrow center">Frequently Asked Questions</span>
            <h2>Everything you need to know</h2>
            <p>Can't find what you're looking for? Reach out to our customer support team.</p>
        </div>

        <div class="faq-grid">
            <details class="faq-item" data-reveal>
                <summary>
                    <span>How do I get a US suite address?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Sign up in less than a minute and your unique US suite address is generated instantly. Use it whenever you check out at any US online store.</p>
            </details>
            <details class="faq-item" data-reveal>
                <summary>
                    <span>How long does shipping to Aruba take?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Standard shipments are ready for pickup within 5–7 business days after we receive your packages. You'll get real-time status updates throughout.</p>
            </details>
            <details class="faq-item" data-reveal>
                <summary>
                    <span>How does package consolidation save me money?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Instead of paying for shipping on each individual package, we combine multiple deliveries into one optimized shipment — cutting your total shipping cost by 40–60%.</p>
            </details>
            <details class="faq-item" data-reveal>
                <summary>
                    <span>Are my packages insured?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Yes. All shipments on our Priority Plus plan include full insurance coverage. Standard shipments include basic carrier coverage.</p>
            </details>
            <details class="faq-item" data-reveal>
                <summary>
                    <span>What if my package needs special handling?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Just add a note when you submit your ship request. Our team handles fragile, oversized, and high-value items every day.</p>
            </details>
            <details class="faq-item" data-reveal>
                <summary>
                    <span>Can I store packages before shipping?</span>
                    <span class="faq-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9"/></svg>
                    </span>
                </summary>
                <p>Absolutely. Your first 30 days of storage are free, so you can wait until you've finished shopping before consolidating and shipping.</p>
            </details>
        </div>
    </section>

    {{-- ===================== CTA ===================== --}}
    <section class="cta-strip" data-reveal>
        <div class="cta-pattern" aria-hidden="true"></div>
        <div class="cta-content">
            <div>
                <span class="eyebrow on-dark">Ready when you are</span>
                <h2>Start shipping smarter today</h2>
                <p>Create your free account in under a minute and get a US suite address instantly. No credit card required.</p>
            </div>
            <div class="actions">
                <a class="btn btn-accent" href="{{ route('register') }}">
                    Create Free Account
                    <svg viewBox="0 0 20 20" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 10h12"/><path d="M11 5l5 5-5 5"/></svg>
                </a>
                <a class="btn btn-light" href="{{ route('login') }}">Sign in</a>
            </div>
        </div>
    </section>
@endsection
