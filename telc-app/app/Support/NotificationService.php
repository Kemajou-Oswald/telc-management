<?php

namespace App\Support;

use App\Models\BankDeposit;
use App\Models\ExamSession;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotificationService
{
    public static function notifySuperAdminsOfDeposit(BankDeposit $deposit): void
    {
        $deposit->loadMissing(['center', 'examSession', 'submitter']);

        $recipients = User::query()
            ->where('role', User::ROLE_SUPER_ADMIN)
            ->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $subject = 'Nouveau depot bancaire en attente';
        $body = self::depositMessage($deposit);

        self::sendEmail($recipients, $subject, $body, optional($deposit->submitter)->email, optional($deposit->submitter)->name);
        self::sendWhatsApp($recipients, $body);
    }

    public static function notifySubmitterOfDepositValidated(BankDeposit $deposit): void
    {
        $deposit->loadMissing(['center', 'examSession', 'submitter', 'validator']);

        $submitter = $deposit->submitter;

        if (! $submitter || $submitter->role !== User::ROLE_SECRETARY) {
            return;
        }

        $subject = 'Depot bancaire valide';
        $body = self::depositValidatedMessage($deposit);
        $recipients = collect([$submitter]);

        self::sendEmail($recipients, $subject, $body);
        self::sendWhatsApp($recipients, $body);
    }

    public static function notifyStudentOfRegistration(Registration $registration): void
    {
        $registration->loadMissing(['student', 'center', 'examSession', 'registrar']);

        $student = $registration->student;

        if (! $student || ! $student->email) {
            return;
        }

        $subject = 'Paiement recu - inscription TELC';
        $body = self::registrationConfirmationMessage($registration);
        $recipients = collect([$student]);

        $replyToEmail = optional($registration->center)->email ?: optional($registration->registrar)->email;
        $replyToName = optional($registration->center)->name ?: optional($registration->registrar)->name;

        self::sendEmail($recipients, $subject, $body, $replyToEmail, $replyToName);
    }

    public static function notifySecretariesOfRegistrationPeriod(ExamSession $session): void
    {
        $recipients = self::secretariesForSession($session);

        if ($recipients->isEmpty()) {
            return;
        }

        $subject = 'Periode d inscription annoncee';
        $body = self::registrationPeriodMessage($session);

        self::sendEmail($recipients, $subject, $body);
        self::sendWhatsApp($recipients, $body);
    }

    public static function notifySecretariesOfSessionClosed(ExamSession $session): void
    {
        $recipients = self::secretariesForSession($session);

        if ($recipients->isEmpty()) {
            return;
        }

        $subject = 'Session fermee';
        $body = self::sessionClosedMessage($session);

        self::sendEmail($recipients, $subject, $body);
        self::sendWhatsApp($recipients, $body);
    }

    protected static function secretariesForSession(ExamSession $session): Collection
    {
        $session->loadMissing(['centers', 'center']);

        $centerIds = $session->centers->pluck('id')->filter()->values();
        if ($centerIds->isEmpty() && $session->center_id) {
            $centerIds = collect([(int) $session->center_id]);
        }

        if ($centerIds->isEmpty()) {
            return collect();
        }

        $ids = $centerIds->all();

        return User::query()
            ->where('role', User::ROLE_SECRETARY)
            ->where(function ($query) use ($ids) {
                $query->whereIn('center_id', $ids)
                    ->orWhereHas('centers', function ($builder) use ($ids) {
                        $builder->whereIn('centers.id', $ids);
                    });
            })
            ->orderBy('name')
            ->get()
            ->unique('id')
            ->values();
    }

    protected static function sessionCenterNames(ExamSession $session): string
    {
        $session->loadMissing(['centers', 'center']);
        $names = $session->centers->pluck('name')->filter()->values();

        if ($names->isEmpty() && optional($session->center)->name) {
            $names = collect([optional($session->center)->name]);
        }

        return $names->isNotEmpty() ? $names->implode(', ') : 'Centre non defini';
    }

    protected static function sendEmail(Collection $recipients, string $subject, string $body, ?string $replyToEmail = null, ?string $replyToName = null): void
    {
        $emails = $recipients->pluck('email')->filter()->unique()->values();

        if ($emails->isEmpty()) {
            return;
        }

        try {
            Mail::send([
                'html' => 'emails.basic',
                'text' => 'emails.basic-text',
            ], [
                'subject' => $subject,
                'body' => $body,
            ], function ($message) use ($emails, $subject, $replyToEmail, $replyToName) {
                $message->to($emails->all());
                $message->subject($subject);
                if ($replyToEmail) {
                    $message->replyTo($replyToEmail, $replyToName ?: null);
                }
            });
        } catch (\Throwable $exception) {
            Log::warning('Email notification failed.', [
                'subject' => $subject,
                'error' => $exception->getMessage(),
            ]);
        }
    }

    protected static function sendWhatsApp(Collection $recipients, string $body): void
    {
        $phones = $recipients->pluck('phone')->filter()->unique()->values();

        if ($phones->isEmpty()) {
            return;
        }

        foreach ($phones as $phone) {
            self::sendWhatsAppMessage($phone, $body);
        }
    }

    protected static function sendWhatsAppMessage(string $phone, string $body): void
    {
        $provider = config('services.whatsapp.provider');

        if ($provider !== 'twilio') {
            return;
        }

        $sid = config('services.whatsapp.twilio_sid');
        $token = config('services.whatsapp.twilio_token');
        $from = config('services.whatsapp.twilio_from');

        if (! $sid || ! $token || ! $from) {
            Log::warning('WhatsApp configuration missing.');
            return;
        }

        $baseUrl = rtrim((string) config('services.whatsapp.twilio_base_url', 'https://api.twilio.com/2010-04-01'), '/');
        $fromNumber = self::startsWith($from, 'whatsapp:') ? $from : 'whatsapp:' . $from;
        $cleanPhone = preg_replace('/\s+/', '', $phone) ?: $phone;
        $toNumber = self::startsWith($cleanPhone, 'whatsapp:') ? $cleanPhone : 'whatsapp:' . $cleanPhone;
        $url = $baseUrl . '/Accounts/' . $sid . '/Messages.json';

        try {
            $response = Http::asForm()
                ->withBasicAuth($sid, $token)
                ->post($url, [
                    'From' => $fromNumber,
                    'To' => $toNumber,
                    'Body' => $body,
                ]);

            if (! $response->successful()) {
                Log::warning('WhatsApp notification failed.', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);
            }
        } catch (\Throwable $exception) {
            Log::warning('WhatsApp notification error.', [
                'error' => $exception->getMessage(),
            ]);
        }
    }

    protected static function depositMessage(BankDeposit $deposit): string
    {
        $center = optional($deposit->center)->name ?: 'Centre non defini';
        $session = optional($deposit->examSession)->title ?: 'Session non definie';
        $submitter = optional($deposit->submitter)->name ?: 'Utilisateur';
        $submitterEmail = optional($deposit->submitter)->email ?: 'Non defini';
        $submitterPhone = optional($deposit->submitter)->phone ?: 'Non defini';
        $amount = number_format((float) $deposit->amount, 0, ',', ' ');
        $date = optional($deposit->deposit_date)->format('d/m/Y') ?: 'Date non definie';
        $status = $deposit->status ?? '';
        $statusLabel = [
            BankDeposit::STATUS_PENDING => 'En attente',
            BankDeposit::STATUS_VALIDATED => 'Valide',
            BankDeposit::STATUS_REJECTED => 'Rejete',
        ][$status] ?? (strlen($status) ? ucfirst($status) : 'Non defini');

        return "Depot bancaire en attente de validation.\n"
            . "Candidat / deposant: {$submitter}\n"
            . "Email deposant: {$submitterEmail}\n"
            . "Telephone deposant: {$submitterPhone}\n"
            . "Centre: {$center}\n"
            . "Session: {$session}\n"
            . "Montant: {$amount} FCFA\n"
            . "Date depot: {$date}\n"
            . "Statut: {$statusLabel}\n";
    }

    protected static function depositValidatedMessage(BankDeposit $deposit): string
    {
        $center = optional($deposit->center)->name ?: 'Centre non defini';
        $session = optional($deposit->examSession)->title ?: 'Session non definie';
        $validator = optional($deposit->validator)->name ?: 'Administration';
        $amount = number_format((float) $deposit->amount, 0, ',', ' ');
        $date = optional($deposit->deposit_date)->format('d/m/Y') ?: 'Date non definie';
        $status = $deposit->status ?? '';
        $statusLabel = [
            BankDeposit::STATUS_PENDING => 'En attente',
            BankDeposit::STATUS_VALIDATED => 'Valide',
            BankDeposit::STATUS_REJECTED => 'Rejete',
        ][$status] ?? (strlen($status) ? ucfirst($status) : 'Non defini');
        $note = trim((string) ($deposit->admin_note ?? ''));
        $noteLine = $note !== '' ? "Note admin: {$note}\n" : '';

        return "Votre depot bancaire a ete valide.\n"
            . "Centre: {$center}\n"
            . "Session: {$session}\n"
            . "Montant: {$amount} FCFA\n"
            . "Date depot: {$date}\n"
            . "Statut: {$statusLabel}\n"
            . $noteLine
            . "Valide par: {$validator}\n";
    }

    protected static function registrationPeriodMessage(ExamSession $session): string
    {
        $center = self::sessionCenterNames($session);
        $opensAt = optional($session->opens_at)->format('d/m/Y') ?: 'Non definie';
        $closesAt = optional($session->closes_at)->format('d/m/Y') ?: 'Non definie';
        $examDate = optional($session->exam_date)->format('d/m/Y') ?: 'Non definie';
        $price = number_format((float) $session->price, 0, ',', ' ');

        return "Nouvelle periode d inscription.\n"
            . "Session: {$session->title}\n"
            . "Centre: {$center}\n"
            . "Inscriptions: {$opensAt} au {$closesAt}\n"
            . "Examen: {$examDate}\n"
            . "Niveau: {$session->level}\n"
            . "Montant: {$price} FCFA\n";
    }

    protected static function sessionClosedMessage(ExamSession $session): string
    {
        $center = self::sessionCenterNames($session);
        $examDate = optional($session->exam_date)->format('d/m/Y') ?: 'Non definie';

        return "Session fermee par l administration.\n"
            . "Session: {$session->title}\n"
            . "Centre: {$center}\n"
            . "Date examen: {$examDate}\n";
    }

    protected static function registrationConfirmationMessage(Registration $registration): string
    {
        $registration->loadMissing(['student', 'center', 'examSession']);

        $student = $registration->student;
        $studentName = optional($student)->full_name ?: 'Etudiant';
        $studentEmail = optional($student)->email ?: 'Non renseigne';
        $dob = optional(optional($student)->date_of_birth)->format('d/m/Y') ?: 'Non renseignee';
        $place = optional($student)->place_of_birth ?: 'Non renseigne';
        $gender = Student::genderLabel(optional($student)->gender) ?: 'Non renseigne';
        $language = Student::languageLabel(optional($student)->base_language) ?: 'Non renseignee';
        $city = optional($student)->city ?: 'Non renseignee';
        $country = optional($student)->country ?: 'Non renseigne';

        $center = optional($registration->center)->name ?: 'Centre non defini';
        $centerEmail = optional($registration->center)->email;

        $session = optional($registration->examSession)->title ?: 'Session non definie';
        $level = optional($registration->examSession)->level ?: 'Non defini';
        $examDate = optional(optional($registration->examSession)->exam_date)->format('d/m/Y') ?: 'Non definie';

        $registeredAt = optional($registration->registered_at)->format('d/m/Y H:i') ?: now()->format('d/m/Y H:i');
        $paidAt = optional($registration->paid_at)->format('d/m/Y H:i') ?: now()->format('d/m/Y H:i');
        $deadline = now()->addDays(2)->format('d/m/Y H:i');
        $amount = number_format((float) $registration->amount, 0, ',', ' ');
        $paymentStatus = $registration->payment_status === Registration::PAYMENT_PAID ? 'Paye' : 'En attente';
        $receipt = $registration->receipt_number ?: 'N/A';

        $message = "Bonjour {$studentName},\n\n"
            . "Votre paiement a ete recu et votre inscription TELC est confirmee.\n"
            . "Reference: {$receipt}\n"
            . "Date d inscription: {$registeredAt}\n"
            . "Paiement recu le: {$paidAt}\n"
            . "Centre: {$center}\n"
            . "Session: {$session}\n"
            . "Niveau: {$level}\n"
            . "Date examen: {$examDate}\n"
            . "Montant: {$amount} FCFA\n"
            . "Statut paiement: {$paymentStatus}\n\n"
            . "Recapitulatif de vos informations:\n"
            . "Nom complet: {$studentName}\n"
            . "Email: {$studentEmail}\n"
            . "Date de naissance: {$dob}\n"
            . "Lieu de naissance: {$place}\n"
            . "Sexe: {$gender}\n"
            . "Langue de base: {$language}\n"
            . "Ville: {$city}\n"
            . "Pays: {$country}\n\n"
            . "Merci de verifier vos informations avant le {$deadline}.\n"
            . "Apres ce delai, toute correction entraine des frais de 80 euros.\n"
            . "Si vous constatez une erreur, contactez votre centre des que possible.";

        if ($centerEmail) {
            $message .= "\nContact centre: {$centerEmail}";
        }

        return $message . "\n";
    }

    protected static function startsWith(string $value, string $prefix): bool
    {
        return $prefix !== '' && strpos($value, $prefix) === 0;
    }
}
