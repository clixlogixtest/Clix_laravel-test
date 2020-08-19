{{-- pageConfigs variable pass to Helper's updatePageConfig function to update page configuration  --}}
@isset($pageConfigs)
  {!! Helper::updatePageConfig($pageConfigs) !!}
@endisset

<!DOCTYPE html>
@php
// confiData variable layoutClasses array in Helper.php file.
  $configData = Helper::applClasses();
@endphp

<html class="loading"
  lang="@if(session()->has('locale')){{session()->get('locale')}}@else{{$configData['defaultLanguage']}}@endif"
  data-textdirection="{{ env('MIX_CONTENT_DIRECTION') === 'rtl' ? 'rtl' : 'ltr' }}">
<!-- BEGIN: Head-->

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @php
  $org = DB::Table('organisations')->select('organisation_name')->where('organisation_id', '=', auth()->user()->organisation_id)->get(); //echo $org['0']->image;
  $org = json_encode($org);
  $org = json_decode($org, true);
  $orgTitle = 'HUXLEY';
  if($org){
    $orgTitle = @$org['0']['organisation_name'];
  }
  @endphp

  <title>@yield('title') | {{$orgTitle}}</title>
  <link rel="apple-touch-icon" href="{{asset('images/favicon/apple-touch-icon-152x152.png')}}">
  <link rel="shortcut icon" type="image/x-icon" href="{{asset('images/favicon/favicon-32x32.png')}}">

  {{-- Include core + vendor Styles --}}
  @include('panels.styles')

</head>
<!-- END: Head-->

{{-- @isset(config('custom.custom.mainLayoutType'))
@endisset --}}
@if(!empty($configData['mainLayoutType']) && isset($configData['mainLayoutType']))
  @include(($configData['mainLayoutType'] === 'horizontal-menu') ? 'layouts.horizontalLayoutMaster':'layouts.verticalLayoutMaster')
@else
{{-- if mainLaoutType is empty or not set then its print below line  --}}
<h1>{{'mainLayoutType Option is empty in config custom.php file.'}}</h1>
@endif
{!!  GoogleReCaptchaV3::init() !!}
</html>