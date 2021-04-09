@component('mail::message')
# Hi {{ strstr($data['email'], '@', true) }}

Company Name : {{ $data['company_name'] }}

Package Name : {{ $data['package_name'] }}

{{ $data['title'] }}.

<!-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent -->

Thanks,<br>
{{ config('app.name') }}
@endcomponent
