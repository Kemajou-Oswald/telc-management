@push('styles')
<style>
.sf-page{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(280px,360px);gap:22px;align-items:start}
.sf-card{position:relative;background:rgba(255,255,255,.92);border:1px solid rgba(220,220,220,.92);border-radius:24px;box-shadow:0 24px 44px rgba(15,23,42,.08);overflow:hidden;backdrop-filter:blur(10px)}
.sf-card--main::before,.sf-card--side::before{content:'';position:absolute;inset:0 0 auto 0;height:4px;background:linear-gradient(90deg,#1a1a1a 0%,#3a3a3a 55%,#d9d9d9 100%)}
.sf-hero{padding:24px 26px 18px;background:linear-gradient(135deg,rgba(250,250,250,.96),rgba(242,242,242,.98));border-bottom:1px solid rgba(225,225,225,.92)}
.sf-hero__eyebrow{display:inline-flex;align-items:center;gap:8px;padding:7px 12px;border-radius:999px;background:#fff;border:1px solid rgba(220,220,220,.95);font-size:10px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;color:#6f6f6f}
.sf-hero__title{margin-top:14px;font-family:'Syne',sans-serif;font-size:27px;line-height:1.08;color:var(--navy)}
.sf-hero__text{margin-top:10px;max-width:58ch;font-size:13px;line-height:1.7;color:#6f6f6f}
.sf-hero__meta{display:flex;gap:10px;flex-wrap:wrap;margin-top:16px}
.sf-pill{display:inline-flex;align-items:center;gap:7px;padding:8px 12px;border-radius:999px;background:rgba(255,255,255,.86);border:1px solid rgba(220,220,220,.92);font-size:12px;font-weight:600;color:#555555}
.sf-form{padding:22px;display:grid;gap:18px}
.sf-section{padding:18px;border:1px solid rgba(228,228,228,.96);border-radius:20px;background:linear-gradient(180deg,rgba(255,255,255,.98),rgba(245,245,245,.96))}
.sf-section__head{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;margin-bottom:14px}
.sf-section__title{font-size:15px;font-weight:700;color:var(--navy)}
.sf-section__text{font-size:12.25px;line-height:1.6;color:#7a7a7a;max-width:48ch}
.sf-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px 16px}
.sf-grid .field--full{grid-column:1 / -1}
.sf-card .field{display:flex;flex-direction:column;gap:7px}
.sf-card .field label{font-size:11px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#707070}
.sf-card .field input,.sf-card .field select,.sf-card .field textarea{width:100%;min-height:46px;padding:12px 14px;border:1px solid #e0e0e0;border-radius:16px;background:#f8f8f8;color:#1a1a1a;box-shadow:inset 0 1px 0 rgba(255,255,255,.7);transition:border-color .18s,box-shadow .18s,background .18s}
.sf-card .field textarea{min-height:118px;resize:vertical}
.sf-card .field input:focus,.sf-card .field select:focus,.sf-card .field textarea:focus{border-color:#2a2a2a;box-shadow:0 0 0 4px rgba(26,26,26,.12);background:#fff;outline:none}
.sf-help{font-size:12px;line-height:1.55;color:#868686}
.sf-sidebar{display:grid;gap:18px}
.sf-sidebox{padding:22px}
.sf-sidebox__title{font-size:14px;font-weight:700;color:var(--navy)}
.sf-sidebox__text{margin-top:8px;font-size:12.5px;line-height:1.7;color:#777777}
.sf-checks{display:grid;gap:10px;margin-top:16px}
.sf-check{display:flex;gap:10px;align-items:flex-start;padding:11px 12px;border-radius:16px;background:#f8f8f8;border:1px solid rgba(225,225,225,.92);font-size:12.5px;line-height:1.55;color:#555555}
.sf-check__dot{width:9px;height:9px;border-radius:50%;background:linear-gradient(135deg,#1f4b8f,#2a2a2a);margin-top:5px;flex-shrink:0}
.sf-summary{display:grid;gap:10px;margin-top:16px}
.sf-summary__row{display:flex;align-items:center;justify-content:space-between;gap:10px;padding:11px 0;border-bottom:1px solid rgba(230,230,230,.92);font-size:12.5px;color:#5f5f5f}
.sf-summary__row:last-child{border-bottom:none;padding-bottom:0}
.sf-summary__value{font-weight:700;color:var(--navy)}
.sf-actions{display:flex;justify-content:space-between;align-items:center;gap:12px;padding-top:4px}
.sf-actions__meta{font-size:12px;color:#8a8a8a}
@media (max-width: 1080px){.sf-page{grid-template-columns:1fr}.sf-sidebar{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media (max-width: 720px){.sf-grid,.sf-sidebar{grid-template-columns:1fr}.sf-hero,.sf-form,.sf-sidebox{padding:18px}.sf-section{padding:16px}.sf-actions{flex-direction:column;align-items:stretch}.sf-actions .btn{width:100%;justify-content:center}}
</style>
@endpush
