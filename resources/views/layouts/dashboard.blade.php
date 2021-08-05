<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">

    @include('layouts.head')

<body class="skin-green">

    @include('layouts.header')

    @include('layouts.sidebar')

        <div class="content-wrapper">

            @yield('content')

        </div>

    @include('layouts.footer')

