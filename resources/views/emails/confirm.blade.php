<x-mail::message>
# Hello {{$user->name}}

We noticed your email was recently changed, Click the button below to verify your account

<x-mail::button :url="route('verify', $user->verification_token)">
verify your account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
