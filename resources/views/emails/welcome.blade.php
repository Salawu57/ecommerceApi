<x-mail::message>
# Hello {{$user->name}}

Thank you for creating an account. Click the button below to verify your account

<x-mail::button :url="route('verify', $user->verification_token)">
verify your account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
