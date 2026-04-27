<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<title>Prime Dine — Michelin-Starred British Restaurant, Mayfair</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400;1,500&family=Jost:wght@200;300;400;500;600&display=swap" rel="stylesheet">
<style>
/* ─── RESET & ROOT ─────────────────────────────────────────────────────────── */
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
  --gold:      #C9A84C;
  --gold-lt:   #E2C97E;
  --gold-dk:   #96721F;
  --cream:     #F5F0E8;
  --ink:       #0E0C09;
  --ink-2:     #1C1914;
  --ink-3:     #2A2520;
  --fog:       #8A8070;
  --mist:      #C4BAA8;
  --white:     #FDFAF5;
  --serif:     'Cormorant Garamond', Georgia, serif;
  --sans:      'Jost', sans-serif;
  --ease:      cubic-bezier(.4,0,.2,1);
  --ease-out:  cubic-bezier(0,.55,.45,1);
}

html { scroll-behavior: smooth; font-size: 16px; }

body {
  background: var(--ink);
  color: var(--white);
  font-family: var(--sans);
  font-weight: 300;
  line-height: 1.7;
  overflow-x: hidden;
}

/* ─── MOBILE-FRIENDLY CURSOR (hidden on touch devices) ─────────────────────── */
@media (hover: hover) and (pointer: fine) {
  body { cursor: none; }
  .cursor {
    position: fixed; top: 0; left: 0; z-index: 9999; pointer-events: none;
    mix-blend-mode: difference;
  }
  .cursor__dot { width: 8px; height: 8px; background: var(--gold); border-radius: 50%; transform: translate(-50%,-50%); transition: transform .1s var(--ease); }
  .cursor__ring { position: absolute; top: 0; left: 0; width: 36px; height: 36px; border: 1px solid rgba(201,168,76,.5); border-radius: 50%; transform: translate(-50%,-50%); transition: transform .08s var(--ease), width .3s var(--ease), height .3s var(--ease); }
  body:has(a:hover) .cursor__ring, body:has(button:hover) .cursor__ring { width: 56px; height: 56px; border-color: var(--gold); }
}
@media (hover: none) and (pointer: coarse) {
  .cursor { display: none; }
  body { cursor: auto; }
  button, a, .btn-submit, .nav__mobile-btn { cursor: pointer; }
}

/* ─── LOADER ─────────────────────────────────────────────────────────────────── */
#loader {
  position: fixed; inset: 0; z-index: 9990;
  background: var(--ink); display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 2rem;
  transition: opacity .8s var(--ease), visibility .8s;
}
#loader.hidden { opacity: 0; visibility: hidden; }
.loader__wordmark { font-family: var(--serif); font-size: clamp(1.5rem, 6vw, 2.5rem); letter-spacing: .3em; color: var(--gold); }
.loader__bar { width: 160px; height: 1px; background: var(--ink-3); position: relative; overflow: hidden; }
.loader__fill { position: absolute; inset: 0; background: var(--gold); transform: scaleX(0); transform-origin: left; animation: load 1.8s var(--ease) forwards; }
@keyframes load { to { transform: scaleX(1); } }

/* ─── NOISE OVERLAY ─────────────────────────────────────────────────────────── */
.noise {
  position: fixed; inset: 0; z-index: 1; pointer-events: none; opacity: .03;
  background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
  background-size: 128px;
}

/* ─── NAV (fully responsive) ─────────────────────────────────────────────────── */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  padding: 0 clamp(1rem, 5vw, 4rem);
  display: flex; align-items: center; justify-content: space-between;
  height: 80px;
  transition: background .4s var(--ease), box-shadow .4s var(--ease), height .3s var(--ease);
}
nav.scrolled {
  background: rgba(14,12,9,.92); backdrop-filter: blur(20px);
  height: 64px; box-shadow: 0 1px 0 rgba(201,168,76,.15);
}
.nav__logo { font-family: var(--serif); font-size: clamp(1.2rem, 5vw, 1.6rem); font-weight: 400; letter-spacing: .15em; color: var(--white); text-decoration: none; }
.nav__logo span { color: var(--gold); }
.nav__links { display: flex; gap: clamp(1rem, 3vw, 3rem); list-style: none; }
.nav__links a {
  font-size: .7rem; letter-spacing: .2em; text-transform: uppercase;
  color: var(--mist); text-decoration: none; position: relative; transition: color .3s var(--ease);
}
.nav__links a::after {
  content: ''; position: absolute; bottom: -4px; left: 0;
  width: 0; height: 1px; background: var(--gold);
  transition: width .3s var(--ease);
}
.nav__links a:hover { color: var(--white); }
.nav__links a:hover::after { width: 100%; }
.nav__cta {
  font-size: .7rem; letter-spacing: .18em; text-transform: uppercase;
  border: 1px solid rgba(201,168,76,.5); color: var(--gold);
  padding: .6rem 1.8rem; text-decoration: none;
  transition: background .3s, color .3s, border-color .3s;
}
.nav__cta:hover { background: var(--gold); color: var(--ink); border-color: var(--gold); }
.nav__mobile-btn { display: none; background: none; border: none; color: var(--white); font-size: 1.4rem; }
.nav__mobile-menu {
  display: none; position: fixed; inset: 0; z-index: 99; background: var(--ink);
  flex-direction: column; align-items: center; justify-content: center; gap: clamp(1.5rem, 8vh, 2.5rem);
}
.nav__mobile-menu.open { display: flex; }
.nav__mobile-menu a { font-family: var(--serif); font-size: clamp(2rem, 8vw, 3rem); font-weight: 300; color: var(--white); text-decoration: none; letter-spacing: .05em; }
.nav__mobile-menu a:hover { color: var(--gold); }
.nav__close { position: absolute; top: 2rem; right: 2rem; background: none; border: none; color: var(--mist); font-size: 1.4rem; }

/* ─── HERO - FULLY RESPONSIVE ───────────────────────────────────────────────── */
#hero {
  position: relative; height: 100vh; min-height: 600px;
  display: flex; align-items: flex-end;
  overflow: hidden;
}
.hero__bg {
  position: absolute; inset: 0;
  background: url('https://images.unsplash.com/photo-1559339352-11d035aa65de?ixlib=rb-4.0.3&auto=format&fit=crop&w=2074&q=90') center/cover no-repeat;
  transform: scale(1.08);
  animation: heroZoom 10s var(--ease-out) forwards;
}
@keyframes heroZoom { to { transform: scale(1); } }
.hero__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(160deg, rgba(14,12,9,.3) 0%, rgba(14,12,9,.75) 60%, rgba(14,12,9,.97) 100%);
}
.hero__content {
  position: relative; z-index: 2; padding: 0 clamp(1rem, 5vw, 4rem) 8vh;
  max-width: 900px;
}
.hero__eyebrow {
  font-size: .65rem; letter-spacing: .35em; text-transform: uppercase;
  color: var(--gold); margin-bottom: 1.5rem;
  display: flex; align-items: center; gap: 1rem;
  opacity: 0; animation: fadeUp .8s .3s var(--ease) forwards;
}
.hero__eyebrow::before { content: ''; width: 40px; height: 1px; background: var(--gold); }
.hero__title {
  font-family: var(--serif); font-size: clamp(3rem, 10vw, 8rem);
  font-weight: 300; line-height: 1; letter-spacing: -.01em; color: var(--white);
  opacity: 0; animation: fadeUp .9s .5s var(--ease) forwards;
}
.hero__title em { color: var(--gold); font-style: italic; }
.hero__sub {
  font-size: .75rem; letter-spacing: .18em; text-transform: uppercase;
  color: var(--mist); margin-top: 1.5rem; margin-bottom: 2rem;
  opacity: 0; animation: fadeUp .8s .7s var(--ease) forwards;
}
.hero__actions {
  display: flex; gap: 1rem; flex-wrap: wrap;
  opacity: 0; animation: fadeUp .8s .9s var(--ease) forwards;
}
.btn-primary, .btn-ghost {
  display: inline-block; padding: .8rem 1.8rem; font-size: .65rem;
  letter-spacing: .2em; text-transform: uppercase; text-decoration: none; transition: all .3s;
}
.btn-primary { background: var(--gold); color: var(--ink); font-weight: 500; }
.btn-primary:hover { background: var(--gold-lt); transform: translateY(-2px); }
.btn-ghost { border: 1px solid rgba(255,255,255,.25); color: var(--white); }
.btn-ghost:hover { border-color: var(--white); background: rgba(255,255,255,.05); transform: translateY(-2px); }
.hero__scroll { display: none; }

/* ─── SECTION UTILITY ───────────────────────────────────────────────────────── */
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
.reveal { opacity: 0; transform: translateY(30px); transition: opacity .7s var(--ease), transform .7s var(--ease); }
.reveal.visible { opacity: 1; transform: none; }
.reveal-delay-1 { transition-delay: .1s; }
.reveal-delay-2 { transition-delay: .2s; }

.section-inner { max-width: 1280px; margin: 0 auto; padding: 0 clamp(1rem, 5vw, 4rem); }
.section-header { margin-bottom: 3rem; }
.eyebrow {
  font-size: .65rem; letter-spacing: .3em; text-transform: uppercase;
  color: var(--gold); margin-bottom: 1rem; display: block;
}
.section-title {
  font-family: var(--serif); font-size: clamp(2rem, 5vw, 4rem);
  font-weight: 300; line-height: 1.1; color: var(--white);
}
.section-title em { color: var(--gold); font-style: italic; }
.rule { width: 48px; height: 1px; background: var(--gold); margin-top: 1.5rem; display: block; }

/* ─── STRIP - RESPONSIVE ────────────────────────────────────────────────────── */
.strip {
  padding: 1.5rem clamp(1rem, 5vw, 4rem);
  border-top: 1px solid rgba(255,255,255,.06);
  border-bottom: 1px solid rgba(255,255,255,.06);
  display: flex; flex-wrap: wrap; gap: 1rem; justify-content: center;
  background: var(--ink-2);
}
.strip__item { display: flex; align-items: center; gap: 0.6rem; white-space: nowrap; }
.strip__icon { color: var(--gold); font-size: .75rem; }
.strip__text { font-size: .6rem; letter-spacing: .15em; text-transform: uppercase; color: var(--mist); }
.strip__sep { color: var(--ink-3); display: none; }

/* ─── MENU - FULLY RESPONSIVE GRID ──────────────────────────────────────────── */
#menu { padding: 5rem 0; background: var(--ink); }
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 1px;
  margin-top: 3rem;
}
.menu-card {
  position: relative; overflow: hidden; aspect-ratio: 4/5;
}
.menu-card__img {
  width: 100%; height: 100%; object-fit: cover;
  filter: brightness(.6) saturate(.8);
  transition: transform .8s var(--ease);
}
.menu-card:hover .menu-card__img { transform: scale(1.05); }
.menu-card__overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to top, rgba(14,12,9,.95) 0%, rgba(14,12,9,.2) 60%, transparent 100%);
}
.menu-card__body {
  position: absolute; bottom: 0; left: 0; right: 0;
  padding: clamp(1rem, 5vw, 2.5rem);
}
.menu-card__tag { font-size: .6rem; letter-spacing: .25em; text-transform: uppercase; color: var(--gold); margin-bottom: .5rem; }
.menu-card__name { font-family: var(--serif); font-size: clamp(1.4rem, 5vw, 1.9rem); font-weight: 400; line-height: 1.15; color: var(--white); }
.menu-card__desc { font-size: .7rem; color: var(--mist); margin-top: .5rem; line-height: 1.5; }
.menu-card__price { font-family: var(--serif); font-size: 1.3rem; color: var(--gold); margin-top: 0.8rem; display: block; }
.menu-card__stars { display: flex; gap: 3px; margin-top: 0.5rem; }
.menu-card__stars i { font-size: .6rem; color: var(--gold); }
.menu-footer { text-align: center; margin-top: 3rem; }

/* ─── ABOUT - RESPONSIVE LAYOUT ─────────────────────────────────────────────── */
#about { padding: 5rem 0; background: var(--ink-2); }
.about-grid { display: grid; grid-template-columns: 1fr; gap: 3rem; align-items: center; }
.about-images { position: relative; display: none; }
.about-text { padding-right: 0; }
.about-text p { font-size: .9rem; color: var(--mist); line-height: 1.7; margin-bottom: 1.2rem; }
.about-stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 1rem; margin-top: 2rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,.08); }
.stat__num { font-family: var(--serif); font-size: clamp(1.8rem, 5vw, 2.8rem); font-weight: 300; color: var(--gold); line-height: 1; }
.stat__label { font-size: .6rem; letter-spacing: .1em; text-transform: uppercase; color: var(--fog); margin-top: .3rem; }
.chef-sig { margin-top: 2rem; display: flex; align-items: center; gap: 1rem; }

/* ─── EXP BAR - RESPONSIVE ──────────────────────────────────────────────────── */
.exp-bar { padding: 3rem 0; background: var(--gold); }
.exp-bar-inner {
  max-width: 1280px; margin: 0 auto; padding: 0 clamp(1rem, 5vw, 4rem);
  display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2px;
}
.exp-item { padding: 2rem 1rem; background: var(--gold); text-align: center; }
.exp-icon { font-size: 1.3rem; color: var(--ink); margin-bottom: 0.8rem; }
.exp-title { font-family: var(--serif); font-size: 1.1rem; font-weight: 500; color: var(--ink); }
.exp-desc { font-size: .65rem; letter-spacing: .05em; color: rgba(14,12,9,.6); margin-top: .3rem; }

/* ─── TESTIMONIALS - RESPONSIVE ─────────────────────────────────────────────── */
#testimonials { padding: 5rem 0; background: var(--ink); }
.testimonial-grid {
  display: grid;
  grid-template-columns: 1fr;
  gap: 1px;
  margin-top: 3rem;
  background: rgba(255,255,255,.04);
}
.tcard { padding: 2rem; background: var(--ink); }
.tcard__quote { font-family: var(--serif); font-size: 2.5rem; line-height: 1; color: var(--gold); margin-bottom: 1rem; opacity: .4; }
.tcard__text { font-family: var(--serif); font-style: italic; font-size: 1rem; line-height: 1.6; color: var(--mist); }
.tcard__stars { display: flex; gap: 4px; margin: 1rem 0; }
.tcard__stars i { font-size: .6rem; color: var(--gold); }
.tcard__author { font-size: .7rem; letter-spacing: .15em; text-transform: uppercase; color: var(--white); }
.tcard__role { font-size: .6rem; color: var(--fog); margin-top: .2rem; }

/* ─── RESERVATION & CONTACT - RESPONSIVE ─────────────────────────────────────── */
#contact { padding: 5rem 0; background: var(--ink-2); }
.contact-grid { display: grid; grid-template-columns: 1fr; gap: 3rem; margin-top: 3rem; }
.form-panel { padding: clamp(1.5rem, 5vw, 3rem); background: var(--ink); border: 1px solid rgba(255,255,255,.06); }
.form-panel__title { font-family: var(--serif); font-size: clamp(1.5rem, 5vw, 2rem); font-weight: 300; }
.field { margin-bottom: 1.2rem; }
.field label { display: block; font-size: .6rem; letter-spacing: .2em; text-transform: uppercase; color: var(--fog); margin-bottom: 0.5rem; }
.field input, .field textarea, .field select {
  width: 100%; padding: 0.8rem 1rem;
  background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1);
  color: var(--white); font-family: var(--sans); font-size: .85rem; font-weight: 300;
  transition: border-color .3s; outline: none;
}
.field input:focus, .field textarea:focus, .field select:focus { border-color: var(--gold); background: rgba(201,168,76,.04); }
.field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
.btn-submit {
  width: 100%; padding: 1rem;
  background: var(--gold); color: var(--ink);
  border: none; font-family: var(--sans); font-size: .65rem;
  letter-spacing: .25em; text-transform: uppercase; font-weight: 500;
  transition: background .3s;
  margin-top: 0.5rem;
}
.btn-submit:hover { background: var(--gold-lt); }
.form-msg { margin-top: 1rem; font-size: .75rem; text-align: center; }
.msg-success { color: #8FCB8A; }
.msg-error { color: #E07C7C; }

.info-panel { display: flex; flex-direction: column; gap: 2rem; }
.info-block { padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,.06); }
.info-label { font-size: .6rem; letter-spacing: .25em; text-transform: uppercase; color: var(--gold); margin-bottom: 1rem; }
.info-text { font-size: .85rem; color: var(--mist); line-height: 1.7; }
.hours-row { display: flex; justify-content: space-between; font-size: .8rem; color: var(--mist); padding: .3rem 0; border-bottom: 1px solid rgba(255,255,255,.04); }
.social-links { display: flex; gap: 1rem; flex-wrap: wrap; }
.social-links a {
  width: 38px; height: 38px; border: 1px solid rgba(255,255,255,.12);
  display: flex; align-items: center; justify-content: center;
  color: var(--fog); text-decoration: none; font-size: .85rem;
  transition: border-color .3s, color .3s;
}
.social-links a:hover { border-color: var(--gold); color: var(--gold); }

/* ─── NEWSLETTER - RESPONSIVE ───────────────────────────────────────────────── */
.newsletter { padding: 4rem 0; background: var(--ink); border-top: 1px solid rgba(255,255,255,.06); }
.newsletter-inner { max-width: 500px; margin: 0 auto; text-align: center; padding: 0 1.5rem; }
.newsletter h3 { font-family: var(--serif); font-size: clamp(1.6rem, 5vw, 2.4rem); font-weight: 300; margin-bottom: 0.8rem; }
.newsletter p { font-size: .8rem; color: var(--fog); margin-bottom: 2rem; }
.newsletter-form { display: flex; flex-direction: column; gap: 0.8rem; }
.newsletter-form input {
  padding: 0.9rem 1.2rem; background: rgba(255,255,255,.05);
  border: 1px solid rgba(255,255,255,.12);
  color: var(--white); font-family: var(--sans); font-size: .85rem;
  outline: none;
}
.newsletter-form input:focus { border-color: var(--gold); }
.newsletter-form button {
  padding: 0.9rem 1.5rem; background: var(--gold); color: var(--ink);
  border: none; font-family: var(--sans); font-size: .65rem;
  letter-spacing: .2em; text-transform: uppercase; font-weight: 500;
}
@media (min-width: 500px) {
  .newsletter-form { flex-direction: row; }
  .newsletter-form input { flex: 1; border-right: none; }
  .newsletter-form button { white-space: nowrap; }
}

/* ─── FOOTER - RESPONSIVE ───────────────────────────────────────────────────── */
footer { padding: 3rem 0 2rem; background: var(--ink-2); border-top: 1px solid rgba(255,255,255,.06); }
.footer-inner { max-width: 1280px; margin: 0 auto; padding: 0 clamp(1rem, 5vw, 4rem); }
.footer-top { display: grid; grid-template-columns: 1fr; gap: 2rem; padding-bottom: 2rem; border-bottom: 1px solid rgba(255,255,255,.06); }
.footer-logo { font-family: var(--serif); font-size: 1.8rem; color: var(--white); margin-bottom: 0.8rem; }
.footer-logo span { color: var(--gold); }
.footer-about { font-size: .8rem; color: var(--fog); line-height: 1.6; }
.footer-col h5 { font-size: .6rem; letter-spacing: .2em; text-transform: uppercase; color: var(--white); margin-bottom: 1rem; }
.footer-col ul { list-style: none; }
.footer-col ul li { margin-bottom: 0.6rem; }
.footer-col ul li a { font-size: .8rem; color: var(--fog); text-decoration: none; transition: color .3s; }
.footer-col ul li a:hover { color: var(--gold); }
.footer-col .info-line { font-size: .8rem; color: var(--fog); margin-bottom: 0.5rem; display: flex; align-items: flex-start; gap: 0.7rem; }
.footer-col .info-line i { color: var(--gold); font-size: .8rem; margin-top: .1rem; }
.footer-bottom { padding-top: 1.5rem; display: flex; flex-direction: column; gap: 1rem; text-align: center; align-items: center; }
.footer-copy { font-size: .65rem; color: var(--fog); letter-spacing: .05em; }
.footer-awards { display: flex; gap: 1rem; flex-wrap: wrap; justify-content: center; }
.award-badge { font-size: .55rem; letter-spacing: .12em; text-transform: uppercase; color: var(--gold); padding: .3rem .8rem; border: 1px solid rgba(201,168,76,.3); }

/* ─── TABLET & DESKTOP BREAKPOINTS ──────────────────────────────────────────── */
@media (min-width: 640px) {
  .footer-top { grid-template-columns: repeat(2, 1fr); }
  .testimonial-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (min-width: 768px) {
  .nav__links, .nav__cta { display: flex; }
  .nav__mobile-btn { display: none; }
  .hero__scroll { display: flex; position: absolute; bottom: 2rem; right: 2rem; flex-direction: column; align-items: center; gap: .5rem; color: var(--fog); font-size: .55rem; letter-spacing: .25em; text-transform: uppercase; opacity: 0; animation: fadeUp .8s 1.2s var(--ease) forwards; }
  .hero__scroll-line { width: 1px; height: 50px; background: linear-gradient(to bottom, var(--gold), transparent); animation: scrollPulse 2s 1.5s infinite; }
  @keyframes scrollPulse { 0%,100%{opacity:.4;transform:scaleY(.6)} 50%{opacity:1;transform:scaleY(1)} }
  .contact-grid { grid-template-columns: 1fr 1fr; gap: 3rem; }
  .about-grid { grid-template-columns: 1fr 1fr; gap: 4rem; }
  .about-images { display: block; }
  .menu-grid { grid-template-columns: repeat(3,1fr); }
  .testimonial-grid { grid-template-columns: repeat(3,1fr); }
  .footer-top { grid-template-columns: 1.4fr 1fr 1fr 1fr; }
  .footer-bottom { flex-direction: row; justify-content: space-between; text-align: left; }
  .strip { justify-content: space-between; }
  .strip__sep { display: inline; }
}
@media (min-width: 1024px) {
  .hero__actions { gap: 1.5rem; }
  .btn-primary, .btn-ghost { padding: .9rem 2.8rem; font-size: .7rem; }
}

/* Font Awesome */
@import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css');
</style>
</head>
<body>

<div class="cursor" id="cursor">
  <div class="cursor__dot" id="cursorDot"></div>
  <div class="cursor__ring" id="cursorRing"></div>
</div>

<div class="noise"></div>

<div id="loader">
  <div class="loader__wordmark">PRIME DINE</div>
  <div class="loader__bar"><div class="loader__fill"></div></div>
</div>

<!-- ─── NAV ─────────────────────────────────────────────────────────────────── -->
<nav id="mainNav">
  <a href="#" class="nav__logo"><span>Prime</span> Dine</a>
  <ul class="nav__links">
    <li><a href="#hero">Home</a></li>
    <li><a href="#menu">Menu</a></li>
    <li><a href="#about">About</a></li>
    <li><a href="#testimonials">Reviews</a></li>
    <li><a href="#contact">Contact</a></li>
  </ul>
  <a href="#contact" class="nav__cta">Reserve a Table</a>
  <button class="nav__mobile-btn" id="mobileBtn" aria-label="Open menu">&#9776;</button>
</nav>

<div class="nav__mobile-menu" id="mobileMenu">
  <button class="nav__close" id="mobileClose" aria-label="Close menu">&#10005;</button>
  <a href="#hero" class="mobile-link">Home</a>
  <a href="#menu" class="mobile-link">Menu</a>
  <a href="#about" class="mobile-link">About</a>
  <a href="#testimonials" class="mobile-link">Reviews</a>
  <a href="#contact" class="mobile-link">Contact</a>
</div>

<!-- ─── HERO ─────────────────────────────────────────────────────────────────── -->
<section id="hero">
  <div class="hero__bg"></div>
  <div class="hero__overlay"></div>
  <div class="hero__content">
    <div class="hero__eyebrow">Mayfair, London &middot; Est. 2005</div>
    <h1 class="hero__title">The Art of<br><em>British</em> Cuisine</h1>
    <p class="hero__sub">Two Michelin Stars &nbsp;&middot;&nbsp; Chef James Harrington</p>
    <div class="hero__actions">
      <a href="#contact" class="btn-primary">Reserve Your Table</a>
      <a href="#menu" class="btn-ghost">View the Menu</a>
    </div>
  </div>
  <div class="hero__scroll">
    <div class="hero__scroll-line"></div>
    <span>Scroll</span>
  </div>
</section>

<!-- ─── STRIP ─────────────────────────────────────────────────────────────────── -->
<div class="strip reveal">
  <div class="strip__item"><i class="strip__icon fas fa-award"></i><span class="strip__text">2 Michelin Stars</span></div>
  <span class="strip__sep">/</span>
  <div class="strip__item"><i class="strip__icon fas fa-wine-glass-alt"></i><span class="strip__text">400-Label Wine Cellar</span></div>
  <span class="strip__sep">/</span>
  <div class="strip__item"><i class="strip__icon fas fa-leaf"></i><span class="strip__text">Farm-to-Table Sourcing</span></div>
  <span class="strip__sep">/</span>
  <div class="strip__item"><i class="strip__icon fas fa-utensils"></i><span class="strip__text">Private Dining</span></div>
  <span class="strip__sep">/</span>
  <div class="strip__item"><i class="strip__icon fas fa-star"></i><span class="strip__text">AA 5 Rosettes</span></div>
</div>

<!-- ─── MENU ─────────────────────────────────────────────────────────────────── -->
<section id="menu">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="eyebrow">Signature Selection</span>
      <h2 class="section-title">A Menu of <em>Distinction</em></h2>
      <span class="rule"></span>
    </div>
  </div>
  <div class="menu-grid" style="max-width:1280px;margin:0 auto">
    <div class="menu-card reveal">
      <img src="https://images.unsplash.com/photo-1600803907087-f56d462fd26b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=85" alt="Wagyu Ribeye" class="menu-card__img">
      <div class="menu-card__overlay"></div>
      <div class="menu-card__body">
        <div class="menu-card__tag">Main Course</div>
        <h3 class="menu-card__name">Wagyu Ribeye</h3>
        <div class="menu-card__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-alt"></i></div>
        <p class="menu-card__desc">28-day dry-aged, truffle pomme purée, bone marrow jus</p>
        <span class="menu-card__price">£48</span>
      </div>
    </div>
    <div class="menu-card reveal reveal-delay-1">
      <img src="https://images.unsplash.com/photo-1550367083-9fa5411cb303?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=85" alt="Cornish Lobster" class="menu-card__img">
      <div class="menu-card__overlay"></div>
      <div class="menu-card__body">
        <div class="menu-card__tag">Seafood</div>
        <h3 class="menu-card__name">Cornish Lobster</h3>
        <div class="menu-card__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
        <p class="menu-card__desc">Bisque beurre blanc, samphire, sea herbs, Jersey Royals</p>
        <span class="menu-card__price">£62</span>
      </div>
    </div>
    <div class="menu-card reveal reveal-delay-2">
      <img src="https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=85" alt="Black Truffle Risotto" class="menu-card__img">
      <div class="menu-card__overlay"></div>
      <div class="menu-card__body">
        <div class="menu-card__tag">Vegetarian</div>
        <h3 class="menu-card__name">Black Truffle Risotto</h3>
        <div class="menu-card__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
        <p class="menu-card__desc">Périgord truffle, aged Parmigiano, wild mushroom consommé</p>
        <span class="menu-card__price">£32</span>
      </div>
    </div>
  </div>
  <div class="menu-footer reveal">
    <a href="full-menu.php" class="btn-ghost">View Full Menu</a>
  </div>
</section>

<!-- ─── ABOUT ─────────────────────────────────────────────────────────────────── -->
<section id="about">
  <div class="section-inner">
    <div class="about-grid">
      <div class="about-images reveal">
        <img src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?ixlib=rb-4.0.3&auto=format&fit=crop&w=600&q=85" alt="Restaurant Interior" style="width:100%;aspect-ratio:4/5;object-fit:cover;filter:saturate(.75)">
      </div>
      <div class="about-text reveal reveal-delay-1">
        <span class="eyebrow">Our Story</span>
        <h2 class="section-title">Crafting Memories<br><em>Since 2005</em></h2>
        <span class="rule"></span>
        <div style="height:1.5rem"></div>
        <p>Prime Dine was founded with a singular vision: to redefine what British fine dining could be. Nestled on a quiet street in Mayfair, our restaurant bridges classical traditions with modern technique.</p>
        <p>Chef James Harrington, formerly of The Fat Duck, leads a team of 24 chefs. Every plate expresses seasonal produce sourced from artisan British farms and foragers.</p>
        <div class="about-stats">
          <div><div class="stat__num">2</div><div class="stat__label">Michelin Stars</div></div>
          <div><div class="stat__num">15+</div><div class="stat__label">Award-Winning Chefs</div></div>
          <div><div class="stat__num">1,200+</div><div class="stat__label">Five-Star Events</div></div>
        </div>
        <div class="chef-sig"><div style="width:40px;height:1px;background:var(--gold-dk)"></div><div style="font-family:var(--serif);font-style:italic;font-size:.9rem;color:var(--mist)">— Chef James Harrington</div></div>
      </div>
    </div>
  </div>
</section>

<!-- ─── EXPERIENCE BAR ─────────────────────────────────────────────────────────── -->
<div class="exp-bar reveal">
  <div class="exp-bar-inner">
    <div class="exp-item"><div class="exp-icon"><i class="fas fa-book-open"></i></div><div class="exp-title">Tasting Menu</div><div class="exp-desc">7-course, £145 pp</div></div>
    <div class="exp-item"><div class="exp-icon"><i class="fas fa-wine-bottle"></i></div><div class="exp-title">Wine Pairing</div><div class="exp-desc">Master Sommelier, £85</div></div>
    <div class="exp-item"><div class="exp-icon"><i class="fas fa-door-open"></i></div><div class="exp-title">Private Dining</div><div class="exp-desc">The Harrington Room, seats 18</div></div>
    <div class="exp-item"><div class="exp-icon"><i class="fas fa-glass-cheers"></i></div><div class="exp-title">Occasions</div><div class="exp-desc">Weddings & Corporate</div></div>
  </div>
</div>

<!-- ─── TESTIMONIALS ──────────────────────────────────────────────────────────── -->
<section id="testimonials">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="eyebrow">Guest Voices</span>
      <h2 class="section-title">What Our <em>Guests</em> Say</h2>
      <span class="rule"></span>
    </div>
  </div>
  <div class="testimonial-grid" style="max-width:1280px;margin:0 auto">
    <div class="tcard reveal">
      <div class="tcard__quote">&ldquo;</div>
      <p class="tcard__text">An extraordinary culinary journey. Every course was a masterpiece. The service was impeccable.</p>
      <div class="tcard__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
      <div class="tcard__author">Alexander Thompson</div>
      <div class="tcard__role">Food Critic, The Guardian</div>
    </div>
    <div class="tcard reveal reveal-delay-1">
      <div class="tcard__quote">&ldquo;</div>
      <p class="tcard__text">The tasting menu with wine pairing is unforgettable. The truffle risotto alone is worth the journey.</p>
      <div class="tcard__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
      <div class="tcard__author">Isabelle Laurent</div>
      <div class="tcard__role">Regular Guest, Paris</div>
    </div>
    <div class="tcard reveal reveal-delay-2">
      <div class="tcard__quote">&ldquo;</div>
      <p class="tcard__text">The finest dining experience in London. The private room made our anniversary truly special.</p>
      <div class="tcard__stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
      <div class="tcard__author">James &amp; Sarah Whitfield</div>
      <div class="tcard__role">Anniversary Dinner</div>
    </div>
  </div>
</section>

<!-- ─── RESERVATION & CONTACT ──────────────────────────────────────────────────── -->
<section id="contact">
  <div class="section-inner">
    <div class="section-header reveal">
      <span class="eyebrow">Reservations</span>
      <h2 class="section-title">Secure Your <em>Table</em></h2>
      <span class="rule"></span>
    </div>
    <div class="contact-grid">
      <div class="form-panel reveal">
        <div class="form-panel__head">
          <div class="form-panel__title">Make a Reservation</div>
          <div class="form-panel__sub">Tables available Tuesday–Sunday</div>
        </div>
        <form id="reservationForm" method="POST">
          <input type="hidden" name="form_type" value="reservation">
          <div class="field"><label>Full Name</label><input type="text" name="name" required></div>
          <div class="field"><label>Email</label><input type="email" name="email" required></div>
          <div class="field-row">
            <div class="field"><label>Date</label><input type="date" name="date" required></div>
            <div class="field"><label>Time</label><input type="time" name="time" required></div>
          </div>
          <div class="field"><label>Guests</label><select name="guests" required><?php for($i=1;$i<=20;$i++) echo "<option value='$i'>$i " . ($i==1?'Guest':'Guests') . "</option>"; ?></select></div>
          <button type="submit" class="btn-submit">Confirm Reservation</button>
          <div class="form-msg" id="reservationMessage"></div>
        </form>
      </div>
      <div class="info-panel reveal reveal-delay-1">
        <div class="form-panel">
          <div class="form-panel__head">
            <div class="form-panel__title">Send a Message</div>
            <div class="form-panel__sub">We respond within 24h</div>
          </div>
          <form id="contactForm" method="POST">
            <input type="hidden" name="form_type" value="contact">
            <div class="field"><label>Name</label><input type="text" name="name" required></div>
            <div class="field"><label>Email</label><input type="email" name="email" required></div>
            <div class="field"><label>Message</label><textarea name="message" rows="3" required></textarea></div>
            <button type="submit" class="btn-submit">Send Message</button>
            <div class="form-msg" id="contactMessage"></div>
          </form>
        </div>
        <div class="info-block"><div class="info-label">Opening Hours</div><div class="hours-row"><span>Mon–Thu</span><span>12:00–22:00</span></div><div class="hours-row"><span>Fri–Sat</span><span>12:00–23:00</span></div><div class="hours-row"><span>Sunday</span><span>13:00–21:00</span></div></div>
        <div class="info-block"><div class="info-label">Contact</div><p class="info-text"><strong>15 Manchester St, London W1U 3AE</strong><br>+44 20 7946 0138<br>hello@primedine.co.uk</p></div>
        <div class="info-block"><div class="info-label">Follow Us</div><div class="social-links"><a href="#"><i class="fab fa-instagram"></i></a><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fab fa-twitter"></i></a><a href="#"><i class="fab fa-tripadvisor"></i></a></div></div>
      </div>
    </div>
  </div>
</section>

<!-- ─── NEWSLETTER ─────────────────────────────────────────────────────────────── -->
<div class="newsletter reveal">
  <div class="newsletter-inner">
    <span class="eyebrow">Join Our Circle</span>
    <h3>Exclusive Invitations &amp; <em style="font-style:italic;color:var(--gold)">Seasonal Menus</em></h3>
    <p>Subscribe for priority bookings and culinary events.</p>
    <div class="newsletter-form">
      <input type="email" placeholder="Your email address">
      <button type="button">Subscribe</button>
    </div>
  </div>
</div>

<!-- ─── FOOTER ─────────────────────────────────────────────────────────────────── -->
<footer>
  <div class="footer-inner">
    <div class="footer-top">
      <div><div class="footer-logo"><span>Prime</span> Dine</div><p class="footer-about">Michelin-starred British fine dining in Mayfair, London. Open since 2005.</p><div class="social-links" style="margin-top:1rem"><a href="#"><i class="fab fa-instagram"></i></a><a href="#"><i class="fab fa-facebook-f"></i></a><a href="#"><i class="fab fa-twitter"></i></a><a href="#"><i class="fab fa-tripadvisor"></i></a></div></div>
      <div class="footer-col"><h5>Navigate</h5><ul><li><a href="#hero">Home</a></li><li><a href="#menu">Menu</a></li><li><a href="#about">About</a></li><li><a href="#testimonials">Reviews</a></li><li><a href="#contact">Contact</a></li></ul></div>
      <div class="footer-col"><h5>Contact</h5><div class="info-line"><i class="fas fa-map-marker-alt"></i><span>15 Manchester St,<br>London W1U 3AE</span></div><div class="info-line"><i class="fas fa-phone"></i><span>+44 20 7946 0138</span></div><div class="info-line"><i class="fas fa-envelope"></i><span>hello@primedine.co.uk</span></div></div>
      <div class="footer-col"><h5>Hours</h5><div class="info-line"><i class="fas fa-clock"></i><span>Mon–Thu: 12pm–10pm<br>Fri–Sat: 12pm–11pm<br>Sun: 1pm–9pm</span></div></div>
    </div>
    <div class="footer-bottom"><div class="footer-copy">© 2025 Prime Dine Ltd. All rights reserved.</div><div class="footer-awards"><div class="award-badge">★★ Michelin</div><div class="award-badge">AA 5 Rosettes</div><div class="award-badge">Square Meal Top 100</div></div></div>
  </div>
</footer>

<script>
/* Loader */
window.addEventListener('load', () => setTimeout(() => document.getElementById('loader')?.classList.add('hidden'), 1500));

/* Custom cursor (only on hover devices) */
const dot = document.getElementById('cursorDot');
const ring = document.getElementById('cursorRing');
if (dot && ring && window.matchMedia('(hover: hover) and (pointer: fine)').matches) {
  let mx = 0, my = 0, rx = 0, ry = 0;
  document.addEventListener('mousemove', e => { mx = e.clientX; my = e.clientY; });
  function loop() {
    rx += (mx - rx) * .12;
    ry += (my - ry) * .12;
    dot.style.transform = `translate(${mx}px,${my}px) translate(-50%,-50%)`;
    ring.style.transform = `translate(${rx}px,${ry}px) translate(-50%,-50%)`;
    requestAnimationFrame(loop);
  }
  loop();
}

/* Nav scroll */
const navEl = document.getElementById('mainNav');
window.addEventListener('scroll', () => navEl?.classList.toggle('scrolled', window.scrollY > 60), { passive: true });

/* Mobile menu */
const mobileMenu = document.getElementById('mobileMenu');
document.getElementById('mobileBtn')?.addEventListener('click', () => mobileMenu?.classList.add('open'));
document.getElementById('mobileClose')?.addEventListener('click', () => mobileMenu?.classList.remove('open'));
document.querySelectorAll('.mobile-link').forEach(a => a.addEventListener('click', () => mobileMenu?.classList.remove('open')));

/* Smooth scroll */
document.querySelectorAll('a[href^="#"]').forEach(a => {
  a.addEventListener('click', e => {
    const target = document.querySelector(a.getAttribute('href'));
    if (target) { e.preventDefault(); target.scrollIntoView({ behavior: 'smooth', block: 'start' }); }
  });
});

/* Scroll reveal */
const observer = new IntersectionObserver(entries => {
  entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); observer.unobserve(e.target); } });
}, { threshold: 0.12 });
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

/* AJAX forms */
function handleForm(formId, msgId) {
  const form = document.getElementById(formId);
  if (!form) return;
  form.addEventListener('submit', e => {
    e.preventDefault();
    const msg = document.getElementById(msgId);
    msg.innerHTML = '<span style="color:var(--fog);font-size:.75rem">Submitting...</span>';
    fetch(window.location.href, { method: 'POST', body: new FormData(form) })
      .then(r => r.text())
      .then(html => {
        const doc = new DOMParser().parseFromString(html, 'text/html');
        const newMsg = doc.getElementById(msgId)?.innerHTML;
        msg.innerHTML = newMsg || '<span class="msg-success">Submitted successfully.</span>';
        form.reset();
        setTimeout(() => msg.innerHTML = '', 5000);
      })
      .catch(() => msg.innerHTML = '<span class="msg-error">Server error. Please try again.</span>');
  });
}
handleForm('reservationForm', 'reservationMessage');
handleForm('contactForm', 'contactMessage');

/* Date min attribute */
const dateInput = document.querySelector('input[name="date"]');
if (dateInput) dateInput.min = new Date().toISOString().split('T')[0];
</script>
</body>
</html>