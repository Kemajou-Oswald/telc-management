<?php

namespace App\Http\Controllers;

use App\Models\Center;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Support\SimplePdfBuilder;
use App\Support\SimpleXlsxBuilder;
use App\Support\NotificationService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegistrationController extends Controller
{
    public function index(Request $request)
    {
        $query = $this->filteredRegistrationsQuery($request)
            ->latest('registrations.registered_at');

        $response = view('registrations.index', [
            'centerOptions' => $this->filterCenterOptions(),
            'overview' => $this->registrationOverview(clone $query),
            'registrations' => $query->paginate(15),
            'sessionOptions' => $this->availableSessionsQuery(false, $request)->get(),
            'user' => $this->authUser(),
        ]);
        return response($response)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function export(Request $request)
    {
        $this->authorizeExportAccess();

        $orderedQuery = $this->orderedRegistrationsForExport($request);
        $overview = $this->registrationOverview(clone $orderedQuery);
        $preview = (clone $orderedQuery)->paginate(12);
        $levelBreakdown = (clone $orderedQuery)
            ->reorder()
            ->select('exam_sessions.level', DB::raw('count(registrations.id) as total'))
            ->join('exam_sessions', 'exam_sessions.id', '=', 'registrations.exam_session_id')
            ->groupBy('exam_sessions.level')
            ->orderBy('exam_sessions.level')
            ->get();
        $centerBreakdown = (clone $orderedQuery)
            ->reorder()
            ->select('centers.name', DB::raw('count(registrations.id) as total'))
            ->join('centers', 'centers.id', '=', 'registrations.center_id')
            ->groupBy('centers.name')
            ->orderByDesc('total')
            ->take(6)
            ->get();

        $response = view('registrations.export', [
            'centerOptions' => $this->filterCenterOptions(),
            'levelBreakdown' => $levelBreakdown,
            'centerBreakdown' => $centerBreakdown,
            'overview' => $overview,
            'preview' => $preview,
            'sessionOptions' => $this->availableSessionsQuery(false, $request)->get(),
            'selectedSessionIds' => $this->requestedSessionIds($request),
            'user' => $this->authUser(),
        ]);
        return response($response)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function downloadExport(Request $request)
    {
        $this->authorizeExportAccess();

        if (! $request->boolean('raw')) {
            $returnUrl = route('registrations.export', $request->query());
            $html = '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
                . '<meta name="viewport" content="width=device-width,initial-scale=1">'
                . '<title>Export XLSX</title>'
                . '<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;padding:24px;line-height:1.4} .card{max-width:720px;margin:10vh auto;padding:18px 18px;border:1px solid #e6eefc;border-radius:14px;background:#fff} .muted{color:#667085;font-size:14px} .spin{display:inline-block;width:12px;height:12px;border:2px solid #cbd5e1;border-top-color:#2563eb;border-radius:999px;animation:rot 0.8s linear infinite;margin-right:8px;vertical-align:-2px}@keyframes rot{to{transform:rotate(360deg)}}</style>'
                . '</head><body>'
                . '<div class="card">'
                . '<div><span class="spin"></span><strong>Export XLSX en cours...</strong></div>'
                . '<div class="muted" style="margin-top:8px">Le telechargement va demarrer. Cette page se fermera automatiquement.</div>'
                . '</div>'
                . '<script>(function(){'
                . 'function tok(){if(window.crypto&&crypto.randomUUID)return crypto.randomUUID();return Date.now().toString(36)+Math.random().toString(36).slice(2);} '
                . 'function getC(n){var p=document.cookie.split(\"; \");for(var i=0;i<p.length;i++){var s=p[i];if(s.indexOf(n+\"=\")===0)return decodeURIComponent(s.slice(n.length+1));}return \"\";} '
                . 'function clr(n){document.cookie=n+\"=; Max-Age=0; path=/\";} '
                . 'var t=tok();clr(\"download_token\");'
                . 'var u=new URL(window.location.href);u.searchParams.set(\"raw\",\"1\");u.searchParams.set(\"download_token\",t);'
                . 'var f=document.createElement(\"iframe\");f.style.display=\"none\";f.src=u.toString();document.body.appendChild(f);'
                . 'var back=' . json_encode($returnUrl) . ';'
                . 'var st=Date.now();var it=setInterval(function(){'
                . 'if(getC(\"download_token\")===t){clr(\"download_token\");clearInterval(it);window.location.href=back;return;}'
                . 'if(Date.now()-st>240000){clearInterval(it);window.location.href=back;}'
                . '},250);'
                . '})();</script>'
                . '</body></html>';

            return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }

        $timestamp = now()->format('Ymd_His');
        $filename  = 'TELC_Inscriptions_' . $timestamp . '.xlsx';

        $rows = $this->exportRowsTelc($request);
        $xlsx = SimpleXlsxBuilder::buildTelc($rows);

        $token = (string) $request->query('download_token', '');

        $response = response($xlsx, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);

        if ($token !== '') {
            $response->cookie(
                'download_token',
                $token,
                5,
                '/',
                null,
                $request->isSecure(),
                false,
                false,
                'Lax'
            );
        }

        return $response;
    }

    public function downloadExportPdf(Request $request)
    {
        $this->authorizeExportAccess();

        if (! $request->boolean('raw')) {
            $returnUrl = route('registrations.export', $request->query());
            $html = '<!doctype html><html lang="fr"><head><meta charset="utf-8">'
                . '<meta name="viewport" content="width=device-width,initial-scale=1">'
                . '<title>Export PDF</title>'
                . '<style>body{font-family:system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;padding:24px;line-height:1.4} .card{max-width:720px;margin:10vh auto;padding:18px 18px;border:1px solid #e6eefc;border-radius:14px;background:#fff} .muted{color:#667085;font-size:14px} .spin{display:inline-block;width:12px;height:12px;border:2px solid #cbd5e1;border-top-color:#2563eb;border-radius:999px;animation:rot 0.8s linear infinite;margin-right:8px;vertical-align:-2px}@keyframes rot{to{transform:rotate(360deg)}}</style>'
                . '</head><body>'
                . '<div class="card">'
                . '<div><span class="spin"></span><strong>Generation du PDF en cours...</strong></div>'
                . '<div class="muted" style="margin-top:8px">Le telechargement va demarrer. Cette page se fermera automatiquement.</div>'
                . '</div>'
                . '<script>(function(){'
                . 'function tok(){if(window.crypto&&crypto.randomUUID)return crypto.randomUUID();return Date.now().toString(36)+Math.random().toString(36).slice(2);} '
                . 'function getC(n){var p=document.cookie.split(\"; \");for(var i=0;i<p.length;i++){var s=p[i];if(s.indexOf(n+\"=\")===0)return decodeURIComponent(s.slice(n.length+1));}return \"\";} '
                . 'function clr(n){document.cookie=n+\"=; Max-Age=0; path=/\";} '
                . 'var t=tok();clr(\"download_token\");'
                . 'var u=new URL(window.location.href);u.searchParams.set(\"raw\",\"1\");u.searchParams.set(\"download_token\",t);'
                . 'var f=document.createElement(\"iframe\");f.style.display=\"none\";f.src=u.toString();document.body.appendChild(f);'
                . 'var back=' . json_encode($returnUrl) . ';'
                . 'var st=Date.now();var it=setInterval(function(){'
                . 'if(getC(\"download_token\")===t){clr(\"download_token\");clearInterval(it);window.location.href=back;return;}'
                . 'if(Date.now()-st>240000){clearInterval(it);window.location.href=back;}'
                . '},250);'
                . '})();</script>'
                . '</body></html>';

            return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
        }

        $timestamp   = now()->format('Ymd_His');
        $filename    = 'TELC_Inscriptions_' . $timestamp . '.pdf';
        $disposition = 'attachment; filename="' . $filename . '"';

        $rows     = $this->exportRowsTelc($request);
        $pdf = SimplePdfBuilder::buildTelc($rows);

        $token = (string) $request->query('download_token', '');

        $response = response($pdf, 200, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => $disposition,
        ]);

        if ($token !== '') {
            $response->cookie(
                'download_token',
                $token,
                5,
                '/',
                null,
                $request->isSecure(),
                false,
                false,
                'Lax'
            );
        }

        return $response;
    }

    public function create(Request $request)
    {
        $this->onlyCenterOperations();

        return view('registrations.create', [
            'accessibleCenters' => $this->accessibleCenters(),
            'selectedCenterId'  => $this->currentCenterId($request),
            'sessions'          => $this->availableSessionsQuery(true, $request)->get(),
            'user'              => $this->authUser(),
        ]);
    }

    public function store(Request $request)
    {
        $this->onlyCenterOperations();

        $data = $request->validate([
            'center_id'       => ['nullable', 'exists:centers,id'],
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255'],
            'date_of_birth'   => ['nullable', 'date'],
            'place_of_birth'  => ['nullable', 'string', 'max:255'],
            'base_language'   => ['nullable', 'string', 'max:255'],
            'gender'          => ['nullable', 'string', 'max:50'],
            'city'            => ['nullable', 'string', 'max:255'],
            'country'         => ['required', 'string', 'max:255'],
            'exam_session_id' => ['required', 'exists:exam_sessions,id'],
        ]);

        $centerId = $this->resolveOperationalCenterId($request);
        if (! $centerId) {
            throw ValidationException::withMessages([
                'center_id' => 'Selectionnez le centre qui porte cette inscription.',
            ]);
        }

        $session = $this->availableSessionsQuery(true, $request)->whereKey($data['exam_session_id'])->first();
        if (! $session) {
            throw ValidationException::withMessages([
                'exam_session_id' => 'Cette session ne fait pas partie du perimetre autorise.',
            ]);
        }

        if (ExamSession::supportsMultiCenters()) {
            $allowedCenterIds = $session->centers()->pluck('centers.id')->all();
            if ($session->center_id) {
                $allowedCenterIds[] = (int) $session->center_id;
            }

            if (! in_array((int) $centerId, array_unique($allowedCenterIds), true)) {
                throw ValidationException::withMessages([
                    'center_id' => 'Le centre selectionne ne correspond pas a la session choisie.',
                ]);
            }
        } elseif ($session->center_id && (int) $session->center_id !== (int) $centerId) {
            throw ValidationException::withMessages([
                'center_id' => 'Le centre selectionne ne correspond pas a la session choisie.',
            ]);
        }

        $normalizedFirstName = Str::lower(trim($data['first_name']));
        $normalizedLastName = Str::lower(trim($data['last_name']));

        $existingStudent = Student::whereRaw('LOWER(first_name) = ?', [$normalizedFirstName])
            ->whereRaw('LOWER(last_name) = ?', [$normalizedLastName])
            ->first();

        $registration = DB::transaction(function () use ($centerId, $data, $existingStudent, $session) {
            $student = $existingStudent ?: Student::create([
                'first_name'     => $data['first_name'],
                'last_name'      => $data['last_name'],
                'email'          => $data['email'] ?? null,
                'date_of_birth'  => $data['date_of_birth'] ?? null,
                'place_of_birth' => $data['place_of_birth'] ?? null,
                'base_language'  => Student::normalizeLanguage($data['base_language'] ?? null),
                'gender'         => Student::normalizeGender($data['gender'] ?? null),
                'city'           => $data['city'] ?? null,
                'country'        => $data['country'],
                'center_id'      => $centerId,
            ]);

            if ($existingStudent) {
                $updates = [];

                if (! $student->email && ! empty($data['email'])) {
                    $updates['email'] = $data['email'];
                }

                if (! $student->date_of_birth && ! empty($data['date_of_birth'])) {
                    $updates['date_of_birth'] = $data['date_of_birth'];
                }

                if (! $student->place_of_birth && ! empty($data['place_of_birth'])) {
                    $updates['place_of_birth'] = $data['place_of_birth'];
                }

                if (! $student->base_language && ! empty($data['base_language'])) {
                    $updates['base_language'] = Student::normalizeLanguage($data['base_language']);
                }

                if (! $student->gender && ! empty($data['gender'])) {
                    $updates['gender'] = Student::normalizeGender($data['gender']);
                }

                if (! $student->city && ! empty($data['city'])) {
                    $updates['city'] = $data['city'];
                }

                if (! $student->country && ! empty($data['country'])) {
                    $updates['country'] = $data['country'];
                }

                if (! empty($updates)) {
                    $student->update($updates);
                }
            }

            $alreadyExists = Registration::where('student_id', $student->id)
                ->where('exam_session_id', $session->id)
                ->where('status', Registration::STATUS_ACTIVE)
                ->exists();

            if ($alreadyExists) {
                throw ValidationException::withMessages([
                    'first_name' => 'Cet etudiant est deja inscrit a cette session.',
                ]);
            }

            $created = Registration::create([
                'student_id'     => $student->id,
                'center_id'      => $centerId,
                'exam_session_id' => $session->id,
                'registered_by'  => $this->authUser()->id,
                'amount'         => $session->price,
                'payment_status' => Registration::PAYMENT_PENDING,
                'status'         => Registration::STATUS_ACTIVE,
                'receipt_number' => 'TELC-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'registered_at'  => now(),
            ]);

            $this->logAudit($created, 'created', $this->formatRegistrationAuditDetails('Inscription creee', $created));

            return $created;
        });
        return redirect()->route('registrations.index')
            ->with('success', 'Etudiant inscrit avec succes. Le recu sera disponible apres paiement.');
    }

    public function edit(Registration $registration)
    {
        $this->authorizeRegistration($registration);
        $this->authorizeRegistrationModification($registration);
        $registration->load(['student', 'center', 'examSession', 'audits.user']);

        $response = view('registrations.edit', [
            'registration' => $registration,
            'sessions'     => ExamSession::with('center')->orderBy('exam_date')->orderBy('opens_at')->get(),
            'user'         => $this->authUser(),
        ]);
        return response($response)->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function update(Request $request, Registration $registration)
    {
        $this->authorizeRegistration($registration);
        $this->authorizeRegistrationModification($registration);
        $registration->load('student');

        $data = $request->validate([
            'first_name'      => ['required', 'string', 'max:255'],
            'last_name'       => ['required', 'string', 'max:255'],
            'email'           => ['nullable', 'email', 'max:255'],
            'date_of_birth'   => ['nullable', 'date'],
            'place_of_birth'  => ['nullable', 'string', 'max:255'],
            'base_language'   => ['nullable', 'string', 'max:255'],
            'gender'          => ['nullable', 'string', 'max:50'],
            'city'            => ['nullable', 'string', 'max:255'],
            'country'         => ['required', 'string', 'max:255'],
            'exam_session_id' => ['required', 'exists:exam_sessions,id'],
            'payment_status'  => ['required', 'in:pending,paid'],
        ]);

        $isMarkingPaid = $registration->payment_status !== Registration::PAYMENT_PAID
            && $data['payment_status'] === Registration::PAYMENT_PAID;

        if ($isMarkingPaid && (int) $registration->registered_by !== (int) $this->authUser()->id) {
            abort(403);
        }

        $session = ExamSession::findOrFail($data['exam_session_id']);

        $registration->student->update([
            'first_name'     => $data['first_name'],
            'last_name'      => $data['last_name'],
            'email'          => $data['email'] ?? null,
            'date_of_birth'  => $data['date_of_birth'] ?? null,
            'place_of_birth' => $data['place_of_birth'] ?? null,
            'base_language'  => Student::normalizeLanguage($data['base_language'] ?? null),
            'gender'         => Student::normalizeGender($data['gender'] ?? null),
            'city'           => $data['city'] ?? null,
            'country'        => $data['country'],
        ]);

        $registration->update([
            'exam_session_id' => $session->id,
            'amount'          => $session->price,
            'payment_status'  => $data['payment_status'],
            'paid_at'         => $data['payment_status'] === Registration::PAYMENT_PAID ? ($registration->paid_at ?: now()) : null,
            'status'          => Registration::STATUS_ACTIVE,
        ]);

        $this->logAudit($registration, 'updated', $this->formatRegistrationAuditDetails('Correction', $registration));

        if ($isMarkingPaid) {
            $this->logAudit($registration, 'paid', $this->formatRegistrationAuditDetails('Paiement marque comme recu', $registration));
            NotificationService::notifyStudentOfRegistration($registration);
        }

        return redirect()->route('registrations.edit', $registration)->with('success', 'Inscription corrigee avec succes.');
    }

    public function cancel(Request $request, Registration $registration)
    {
        $this->onlySuperAdmin();

        $data = $request->validate([
            'cancel_reason' => ['required', 'string'],
        ]);

        $registration->update([
            'status'        => Registration::STATUS_CANCELLED,
            'cancelled_at'  => now(),
            'cancel_reason' => $data['cancel_reason'],
        ]);

        $this->logAudit($registration, 'cancelled', $this->formatRegistrationAuditDetails('Annulation', $registration, $data['cancel_reason']));
        return redirect()->route('registrations.index')->with('success', 'Inscription annulee.');
    }

    public function pay(Registration $registration)
    {
        $this->onlyCenterOperations();
        $this->authorizeRegistration($registration);
        $this->authorizeRegistrationModification($registration);

        if ((int) $registration->registered_by !== (int) $this->authUser()->id) {
            abort(403);
        }

        if ($registration->status === Registration::STATUS_CANCELLED) {
            abort(403);
        }

        if ($registration->payment_status !== Registration::PAYMENT_PAID) {
            $registration->update([
                'payment_status' => Registration::PAYMENT_PAID,
                'paid_at'        => now(),
            ]);
            $this->logAudit($registration, 'paid', $this->formatRegistrationAuditDetails('Paiement marque comme recu', $registration));
            NotificationService::notifyStudentOfRegistration($registration);
        }

        return redirect()->route('registrations.index')->with('success', 'Paiement marque comme recu.');
    }

    public function receipt(Registration $registration)
    {
        $this->authorizeRegistration($registration);

        if ($registration->payment_status !== Registration::PAYMENT_PAID) {
            return redirect()->route('registrations.index')
                ->with('error', 'Le recu est disponible uniquement apres paiement.');
        }

        $registration->load(['student', 'center', 'examSession', 'registrar']);

        return view('receipts.show', [
            'registration' => $registration,
            'user'         => $this->authUser(),
            'centerName'   => $registration->center->name,
        ]);
    }

    private function generateStructuredPdfContent($registration)
    {
        $content = [
            '═══════════════════════════════════════════════════════════════',
            '                    TARA INSTITUT ET DER BESTE BETREUER',
            '                           PRÉINSCRIPTION TELC',
            '═══════════════════════════════════════════════════════════════',
            '',
            'REÇU D\'INSCRIPTION',
            '───────────────────────────────────────────────────────────────────',
            '',
            'Numéro du reçu : ' . $registration->receipt_number,
            'Date d\'émission : ' . (optional($registration->registered_at)->format('d/m/Y H:i') ?: 'Non renseigné'),
            'Centre : ' . (optional($registration->center)->name ?: 'Non renseigné'),
            '',
            '═══════════════════════════════════════════════════════════════',
            '                              ÉTUDIANT',
            '═══════════════════════════════════════════════════════════════',
            '',
            'Nom complet : ' . (optional($registration->student)->full_name ?: 'Non renseigné'),
            'Email : ' . (optional($registration->student)->email ?: 'Non renseigné'),
            'Date de naissance : ' . (optional(optional($registration->student)->date_of_birth)->format('d/m/Y') ?: 'Non renseignée'),
            'Lieu de naissance : ' . (optional($registration->student)->place_of_birth ?: 'Non renseigné'),
            'Nationalité : ' . (optional($registration->student)->country ?: 'Non renseignée'),
            'Ville : ' . (optional($registration->student)->city ?: 'Non renseignée'),
            'Langue de base : ' . (\App\Models\Student::languageLabel(optional($registration->student)->base_language) ?: 'Non renseignée'),
            'Sexe : ' . (\App\Models\Student::genderLabel(optional($registration->student)->gender) ?: 'Non renseigné'),
            '',
            '═══════════════════════════════════════════════════════════════',
            '                         SESSION D\'EXAMEN',
            '═══════════════════════════════════════════════════════════════',
            '',
            'Titre de la session : ' . (optional($registration->examSession)->title ?: 'Non renseigné'),
            'Niveau : ' . (optional($registration->examSession)->level ?: 'Non renseigné'),
            'Ville de l\'examen : ' . (optional($registration->examSession)->city ?: 'Non renseigné'),
            '',
            '═══════════════════════════════════════════════════════════════',
            '                            PAIEMENT',
            '═══════════════════════════════════════════════════════════════',
            '',
            'MONTANT DÛ : ' . str_pad(number_format($registration->amount, 0, ',', ' ') . ' FCFA', 40, ' ', STR_PAD_BOTH),
            '',
            'Statut du paiement : ' . ($registration->payment_status === \App\Models\Registration::PAYMENT_PAID ? '✓ PAYÉ' : '○ EN ATTENTE'),
            'Statut de l\'inscription : ' . ($registration->status === 'active' ? '✓ ACTIVE' : '✗ ANNULÉE'),
            '',
            'Enregistré par : ' . (optional($registration->registrar)->name ?: 'Système'),
            'Date d\'enregistrement : ' . (optional($registration->registered_at)->format('d/m/Y H:i') ?: 'Non renseigné'),
            '',
            '═══════════════════════════════════════════════════════════════',
            '                           VALIDATION',
            '═══════════════════════════════════════════════════════════════',
            '',
            '┌─────────────────────────────────────┐  ┌─────────────────────────────────────┐',
            '│            CACHET DU CENTRE          │  │      SIGNATURE DU CHEF DE CENTRE     │',
            '│                                     │  │                                     │',
            '│                                     │  │                                     │',
            '│                                     │  │                                     │',
            '│                                     │  │                                     │',
            '│                                     │  │                                     │',
            '└─────────────────────────────────────┘  └─────────────────────────────────────┘',
            '',
            '═══════════════════════════════════════════════════════════════',
            '',
            'Ce document est généré automatiquement et fait foi de l\'inscription.',
            '',
            'Référence : ' . $registration->receipt_number,
            '',
            '═══════════════════════════════════════════════════════════════',
        ];

        return $content;
    }

    protected function authorizeRegistration(Registration $registration)
    {
        if ($this->isCenterScoped() && ! in_array((int) $registration->center_id, $this->accessibleCenterIds(), true)) {
            abort(403);
        }
    }

    protected function authorizeExportAccess()
    {
        abort_unless($this->isSuperAdmin() || $this->isCenterAdmin() || $this->isSecretary(), 403);
    }

    protected function logAudit(Registration $registration, $action, $details)
    {
        $registration->audits()->create([
            'user_id' => optional($this->authUser())->id,
            'action'  => $action,
            'details' => $details,
        ]);
    }

    protected function authorizeRegistrationModification(Registration $registration): void
    {
        if ($this->isSecretary() && (int) $registration->registered_by !== (int) $this->authUser()->id) {
            abort(403);
        }
    }

    protected function formatRegistrationAuditDetails(string $action, Registration $registration, string $extra = ''): string
    {
        $registration->loadMissing('student');
        $studentName = optional($registration->student)->full_name ?: 'Etudiant';
        $receipt = $registration->receipt_number ?: 'N/A';
        $actor = optional($this->authUser())->name ?: 'Systeme';
        $detail = trim($extra);

        $base = $action . ' | ' . $studentName . ' | Recu ' . $receipt . ' | Par ' . $actor;

        return $detail ? ($base . ' | ' . $detail) : $base;
    }

    protected function filteredRegistrationsQuery(Request $request)
    {
        $query = Registration::with(['student', 'center', 'examSession.center', 'registrar']);
        $this->applyCenterScope($query, 'registrations.center_id', $request);

        if ($request->filled('q')) {
            $search = trim((string) $request->input('q'));

            $query->where(function ($builder) use ($search) {
                $builder->where('receipt_number', 'like', '%' . $search . '%')
                    ->orWhereHas('student', function ($studentQuery) use ($search) {
                        $studentQuery->where('first_name', 'like', '%' . $search . '%')
                            ->orWhere('last_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%')
                            ->orWhere('country', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('center', function ($centerQuery) use ($search) {
                        $centerQuery->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('examSession', function ($sessionQuery) use ($search) {
                        $sessionQuery->where('title', 'like', '%' . $search . '%')
                            ->orWhere('level', 'like', '%' . $search . '%')
                            ->orWhere('city', 'like', '%' . $search . '%');
                    });
            });
        }

        $sessionIds = $this->requestedSessionIds($request);
        if (! empty($sessionIds)) {
            $query->whereIn('exam_session_id', $sessionIds);
        } elseif ($request->filled('session_id')) {
            $query->where('exam_session_id', (int) $request->input('session_id'));
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', (string) $request->input('payment_status'));
        }

        if ($request->filled('level')) {
            $level = (string) $request->input('level');
            $query->whereHas('examSession', function ($builder) use ($level) {
                $builder->where('level', $level);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', (string) $request->input('status'));
        }

        return $query;
    }

    protected function registrationOverview(Builder $query)
    {
        $total   = (clone $query)->count();
        $paid    = (clone $query)->where('payment_status', Registration::PAYMENT_PAID)->count();
        $pending = max($total - $paid, 0);
        $amount  = (clone $query)->sum('amount');

        return [
            'total'   => $total,
            'paid'    => $paid,
            'pending' => $pending,
            'amount'  => $amount,
        ];
    }

    protected function requestedSessionIds(Request $request)
    {
        return collect((array) $request->input('session_ids', []))
            ->map(fn($value) => (int) $value)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    protected function orderedRegistrationsForExport(Request $request)
    {
        return $this->filteredRegistrationsQuery($request)
            ->leftJoin('exam_sessions as export_sessions', 'export_sessions.id', '=', 'registrations.exam_session_id')
            ->leftJoin('students as export_students', 'export_students.id', '=', 'registrations.student_id')
            ->select('registrations.*')
            ->orderByRaw('case when export_sessions.exam_date is null then 1 else 0 end')
            ->orderBy('export_sessions.exam_date')
            ->orderBy('export_sessions.opens_at')
            ->orderBy('export_students.last_name')
            ->orderBy('export_students.first_name');
    }

    protected function exportRows(Request $request)
    {
        return $this->orderedRegistrationsForExport($request)
            ->get()
            ->map(function ($registration) {
                return [
                    'last_name'    => optional($registration->student)->last_name,
                    'first_name'   => optional($registration->student)->first_name,
                    'email'        => optional($registration->student)->email,
                    'date_of_birth' => optional(optional($registration->student)->date_of_birth)->format('d.m.Y'),
                    'place_of_birth' => optional($registration->student)->place_of_birth,
                    'base_language' => Student::languageLabel(optional($registration->student)->base_language),
                    'gender'       => Student::genderLabel(optional($registration->student)->gender),
                    'city'         => optional($registration->student)->city,
                    'country'      => optional($registration->student)->country,
                    'center'       => optional($registration->center)->name,
                    'session'      => optional($registration->examSession)->title,
                    'session_date' => optional(optional($registration->examSession)->exam_date)->format('d.m.Y'),
                ];
            });
    }

    protected function exportRowsTelc(Request $request)
    {
        $maxRows = (int) env('EXPORT_MAX_ROWS', 2000);

        $registrations = $this->orderedRegistrationsForExport($request)
            ->take($maxRows + 1)
            ->get();

        if ($registrations->count() > $maxRows) {
            throw ValidationException::withMessages([
                'export' => 'Export trop volumineux (' . $registrations->count() . ' lignes). Filtrez davantage ou exportez par centre/session. Limite: ' . $maxRows . ' lignes.',
            ]);
        }

        return $registrations
            ->map(function ($registration) {
                $student = $registration->student;
                return [
                    optional($student)->last_name ?? '',                                          // 1.  Nachname
                    optional($student)->first_name ?? '',                                         // 2.  Vorname
                    optional($student)->email ?? '',                                              // 3.  E-Mail (optional)
                    ($student && $student->date_of_birth
                        ? $student->date_of_birth->format('d.m.Y')
                        : ''),                                                                    // 4.  Geburtsdatum TT.MM.JJJJ
                    optional($student)->place_of_birth ?? '',                                     // 5.  Geburtsort
                    Student::languageLabel(optional($student)->base_language) ?? '',              // 6.  Muttersprache
                    Student::genderLabel(optional($student)->gender) ?? '',                       // 7.  Geschlecht
                    '',                                                                           // 8.  Teilnehmernummer (vide)
                    '',                                                                           // 9.  Teilprüfung (vide)
                    '',                                                                           // 10. Vorkommnisse (vide)
                    optional($student)->country ?? '',                                            // 11. Geburtsland
                    optional($student)->city ?? '',                                               // 12. Wohnort Stadt
                    optional($student)->country ?? '',                                            // 13. Wohnort Land
                    '',                                                                           // 14. BAMF-Kennnziffer (vide)
                ];
            })
            ->all();
    }

    protected function exportPdfLines(Request $request, $rows)
    {
        $lines = [
            '# Tara Institut et Der Beste Betreuer',
            '## Préinscription TELC',
            '---',
            '### Rapport des inscriptions',
            'Généré le: ' . now()->format('d/m/Y H:i'),
        ];

        $selectedCenterId = $this->selectedCenterFilter($request);
        if ($selectedCenterId) {
            $center = $this->filterCenterOptions()->firstWhere('id', $selectedCenterId);
            if ($center) {
                $lines[] = 'Centre: ' . $center->name;
            }
        }

        if ($request->filled('level')) {
            $lines[] = 'Niveau: ' . $request->input('level');
        }

        $selectedSessionIds = $this->requestedSessionIds($request);
        if (! empty($selectedSessionIds)) {
            $lines[] = 'Sessions sélectionnées: ' . count($selectedSessionIds);
        }

        $lines[] = 'Total inscriptions: ' . $rows->count();
        $lines[] = '---';
        $lines[] = '### Détails';

        if ($rows->isEmpty()) {
            $lines[] = 'Aucune inscription ne correspond aux filtres choisis.';
            return $lines;
        }

        foreach ($rows as $index => $row) {
            $lines[] = ($index + 1) . '. ' . trim(($row['last_name'] ?: '') . ' ' . ($row['first_name'] ?: ''));
            $lines[] = '- Centre: ' . ($row['center'] ?: 'Non renseigné');
            $lines[] = '- Session: ' . ($row['session'] ?: 'Non renseignée');
            $lines[] = '- Date session: ' . ($row['session_date'] ?: 'Non définie');
            $lines[] = '- Email: ' . ($row['email'] ?: 'Non renseigné');
            $lines[] = '- Naissance: ' . ($row['date_of_birth'] ?: 'Non renseignée') . ' - ' . ($row['place_of_birth'] ?: 'Non renseigné');
            $lines[] = '- Langue: ' . ($row['base_language'] ?: 'Non renseignée') . ' | Sexe: ' . ($row['gender'] ?: 'Non renseigné');
            $lines[] = '- Ville / Pays: ' . trim(($row['city'] ?: 'Ville N/A') . ' / ' . ($row['country'] ?: 'Pays N/A'));
            $lines[] = '';
        }

        return $lines;
    }

    protected function buildTelcPdfLines($rows)
    {
        $lines = [
            'TELC Anmeldungen - Teilnehmerliste',
            '',
            'Teilnehmende: Bitte achten Sie darauf, alle Felder auszufüllen.',
            '',
            'Nur für DTZ / DTB: Bitte nur vom BAMF vergebene BAMF-Kennziffer hier eintragen. Beispiel DTZ: 1234567   Beispiel DTB: B123456',
            '',
        ];

        $w   = [18, 16, 28, 16, 14, 14, 12, 18, 12, 14, 14, 14];
        $sep = '+' . implode('+', array_map(fn($x) => str_repeat('-', $x + 2), $w)) . '+';
        $h1  = '| Nachname           | Vorname          | E-Mail (optional)          | Geburtsdatum     | Geburtsort     | Muttersprache  | Geschlecht   | Teilnehmernummer / Bognummer   | Vollpris       | Geburtsland    | Wohnort Stadt  | Wohnort Land   |';
        $h2  = '|                    |                  |                            | (TT.MM.JJJJ)     |                | (Bitte ausw.)  | (Bitte ausw.)| (Bitte stets Seitenzugabe...)  | (Bitte ausw.)  | (Bitte ausw.)  |                | (Bitte ausw.)  |';

        $lines[] = $sep;
        $lines[] = $h1;
        $lines[] = $h2;
        $lines[] = $sep;

        foreach ($rows as $row) {
            $cells = [
                str_pad(substr($row[0] ?? '', 0, 18), 18),
                str_pad(substr($row[1] ?? '', 0, 16), 16),
                str_pad(substr($row[2] ?? '', 0, 26), 26),
                str_pad(substr($row[3] ?? '', 0, 14), 14),
                str_pad(substr($row[4] ?? '', 0, 12), 12),
                str_pad(substr($row[5] ?? '', 0, 12), 12),
                str_pad(substr($row[6] ?? '', 0, 10), 10),
                str_pad(substr($row[7] ?? '', 0, 30), 30),
                str_pad(substr($row[8] ?? '', 0, 12), 12),
                str_pad(substr($row[9] ?? '', 0, 12), 12),
                str_pad(substr($row[10] ?? '', 0, 12), 12),
                str_pad(substr($row[11] ?? '', 0, 12), 12),
            ];
            $lines[] = '| ' . implode(' | ', $cells) . ' |';
        }

        $lines[] = $sep;
        $lines[] = '';
        $lines[] = 'Generiert am: ' . now()->format('d.m.Y H:i');
        $lines[] = 'Total: ' . count($rows) . ' Teilnehmer';

        return $lines;
    }

    protected function filterCenterOptions()
    {
        if ($this->isSuperAdmin()) {
            return Center::where('is_active', true)->orderBy('name')->get();
        }

        return $this->accessibleCenters();
    }
}











