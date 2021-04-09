@component('mail::message')
# <#>

Dear Customer, {{ !empty($user->otp) ? $user->otp : '' }} is the One Time Password (OTP) for your forgot Password to the {{ config('app.name') }} App. This OTP is valid for next 15 minutes. Please do not share this with anyone.

<!-- @component('mail::button', ['url' => '#'])
Button Text
@endcomponent -->

Thanks,<br>
{{ config('app.name') }}
@endcomponent
