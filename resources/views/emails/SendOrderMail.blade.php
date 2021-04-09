@component('mail::message')
# Hello Customer,

Thank you for your order. Your order details are as below:-

<br>

Order No: {{ $order['order_id'] }}

@component('mail::table')
| Company Name | Package | Addon Service | Price |
| ------------ |:-------:| -------------:| -----:|
@foreach($order['order_details'] as $key => $order_detail)
| {{ $order_detail['company_name'] }} | {{ $order_detail['package_name'] }} | {{ $order_detail['addon_service_name'] }} | {{ ($order_detail['addon_service_name'] == 'N/A') ? $order_detail['package_price'] : $order_detail['addon_service_price'] }} |
@endforeach
@endcomponent

<span style="float:right;">Grand Total: {{ $order['grand_total'] }}</span>

<br>
<br>

Thanks,<br>
{{ config('app.name') }}
@endcomponent
