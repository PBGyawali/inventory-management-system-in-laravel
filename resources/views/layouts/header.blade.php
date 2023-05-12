        <!DOCTYPE html>
        <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
		<head>
		<html class='no-js' lang='en'>
		<meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
		<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible' />
		<script type="text/javascript" src="<?php echo env('JS_URL').'jquery.min.js'?>"></script>
		<script type="text/javascript" src="<?php echo env('JS_URL').'popper.min.js'?>"></script>
		<script type="text/javascript" src="<?php echo env('JS_URL')?>jquery-confirm.min.js"></script>
		<link rel="stylesheet" href="<?php echo env('CSS_URL')?>bootstrap.min.css">
		<link rel="stylesheet" href="<?php echo env('CSS_URL')?>jquery-confirm.min.css">
		<script type="text/javascript" src="<?php echo env('JS_URL')?>bootstrap.bundle.min.js"></script>
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
		<script type="text/javascript" src="<?php echo env('JS_URL')?>datatables.min.js"></script>
		<script type="text/javascript" src="<?php echo env('JS_URL')?>dataTables.responsive.min.js"></script>
        <link href="<?php echo env('CSS_URL')?>datatables.min.css" rel="stylesheet"/>
		<link rel="stylesheet" href="<?php echo env('CSS_URL').'parsley.css'?>" >
		<script type="text/javascript" src="<?php echo env('JS_URL').'parsley.min.js'?>"></script>
        <script type="text/javascript" src="<?php echo env('JS_URL').'app.js'?>"></script>
		<title><?php echo ucwords($page??'').' '.($website??'').' '.strtoupper(config('app.name'))?></title>
		<link href="https://cdn.datatables.net/v/bs5/dt-1.13.4/r-2.4.1/datatables.min.css" rel="stylesheet"/>
        @include('layouts.sidebar')
