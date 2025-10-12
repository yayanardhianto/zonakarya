@extends('frontend.layouts.master')

@section('meta_title', __('Edit Profile') . ' || ' . $setting->app_name)

@section('header')
    @include('frontend.layouts.header-layout.three')
@endsection

@section('contents')
    <!-- Breadcumb Area -->
    <x-breadcrumb :title="__('Edit Profile')" />

    <!--  Dashboard Area -->
    <section class="wsus__dashboard_profile wsus__dashboard">
        <div class="container">
            <div class="row">
                <!--  Sidebar Area -->
                @include('frontend.profile.partials.sidebar')
                <!--  Main Content Area -->
                <div class="col-lg-8 col-xl-9 ">
                    <div class="wsus__dashboard_main_contant ">
                        <h4>{{__('Update Your Information')}}</h4>
                        <form action="#" class="wsus__dashboard_profile_edit_info" method="POST" action="{{route('user.profile.update')}}">
                            @csrf
                                @method('patch')
                            <div class="row">
                                <div class="col-xl-12 ">
                                    <label>{{ __('Name') }} *</label>
                                    <input type="text" placeholder="{{__('Name')}}" value="{{ old('name', $name ?? $user->name) }}" name="name">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Phone') }} *</label>
                                    <input type="text" placeholder="{{__('Phone')}}" value="{{old('phone', $phone ?? $user->phone)}}" name="phone">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Email Address') }} *</label>
                                    <input type="email" placeholder="{{__('Email')}}" value="{{ old('email', $email ?? $user->email) }}" name="email">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Gender') }} *</label>
                                    <select class="select_2" name="gender">
                                        <option value="" disabled>{{__('Select Gender')}}</option>
                                        <option value="male"  @selected(old('gender',strtolower($user->gender)) == 'male')>{{__('Male')}}</option>
                                        <option value="female" @selected(old('gender',strtolower($user->gender)) == 'female')>{{__('Female')}}</option>
                                    </select>
                                </div>
                                
                                <div class="col-xl-6 ">
                                    <label>{{ __('Age') }} *</label>
                                    <input type="text" placeholder="{{__('Age')}}" value="{{old('age', $age ?? $user->age)}}" name="age">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Country') }} *</label>
                                    <select class="select_2 fix-height" name="country_id">
                                        <option value="">{{__('Select Country')}}</option>
                                        @foreach ($countries as $country)
                                            <option value="{{$country->id}}" @selected(old('country_id',$user->country_id) == $country->id)>{{$country->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Province') }} *</label>
                                    <input type="text" placeholder="{{__('Province')}}" value="{{old('province', $province ?? $user->province)}}" name="province">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('City') }} *</label>
                                    <input type="text" placeholder="{{__('City')}}" value="{{old('city', $city ?? $user->city)}}" name="city">
                                </div>
                                <div class="col-xl-6 ">
                                    <label>{{ __('Zip code') }} *</label>
                                    <input type="text" placeholder="{{__('Zip code')}}" value="{{old('zip_code', $zip_code ?? $user->zip_code)}}" name="zip_code">
                                </div>
                                <div class="col-xl-12 ">
                                    <label>{{ __('Address') }} *</label>
                                    <textarea rows="5"
                                            placeholder="{{__('Address')}}" name="address">{{old('address', $address ?? $user->address)}}</textarea>
                                </div>
                                <div class="col-xl-12 ">
                                    <ul class="d-flex flex-wrap">
                                        <li>
                                            <a href="{{route('user.dashboard')}}" class="btn">
                                                <span class="link-effect">
                                                    <span class="effect-1">{{__('Cancel')}}</span>
                                                    <span class="effect-1">{{__('Cancel')}}</span>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <button class="btn style2" type="submit">
                                                <span class="link-effect">
                                                    <span class="effect-1">{{__('Update info')}}</span>
                                                    <span class="effect-1">{{__('Update info')}}</span>
                                                </span>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--  Marquee Area -->
    @include('frontend.partials.marquee')
@endsection
@section('footer')
    @include('frontend.layouts.footer-layout.two')
@endsection
