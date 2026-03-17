<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body { margin: 0; padding: 0; background: #f3f4f6; color: #111827; font-family: Arial, Helvetica, sans-serif; }
        .wrapper { padding: 24px; }
        .card { background: #ffffff; border-radius: 12px; padding: 24px; max-width: 640px; margin: 0 auto; box-shadow: 0 10px 24px rgba(0,0,0,0.08); }
        .title { font-size: 20px; font-weight: 700; margin: 0 0 16px; }
        .content { font-size: 14px; line-height: 1.6; white-space: normal; }
        .footer { max-width: 640px; margin: 16px auto 0; font-size: 12px; color: #6b7280; text-align: center; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="card">
            <div class="title">{{ $subject }}</div>
            <div class="content">{!! nl2br(e($body)) !!}</div>
        </div>
        <div class="footer">Cet email a ete genere automatiquement.</div>
    </div>
</body>
</html>
