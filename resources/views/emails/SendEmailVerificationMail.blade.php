@component('mail::message')
# 

Hi {{ (!empty($user->user_meta) && !empty($user->user_meta['first_name'])) ? $user->user_meta['first_name'] : '' }},

Please click the button below to verify your email address.

@component('mail::button', ['url' => url('user/verify', $user->email_verify_token)])
Verify Email Address
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
