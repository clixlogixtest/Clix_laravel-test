
Hi,

<br><br>

You received an email from : {{ $first_name }} {{ $last_name }} 

<br><br>

Here are the details:

<br><br>

Name:  {{ $first_name }} {{ $last_name }} <br>

Email:  {{ $email }} <br>

Subject:  {{ $subject }} <br>

Message:  {!! $msg !!} <br>

<br><br>

Thanks,<br>
{{ config('app.name') }}
