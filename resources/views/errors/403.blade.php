@extends('errors::minimal')

@section('title', __('Illegal access attempt'))
@section('code', '403')
@section('message', __('Illegal access attempt. Your IP address has been recorded'))
